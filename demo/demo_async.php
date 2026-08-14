<?php

/**
 * Non-blocking searches at scale.
 *
 * With `async => true` the backend accepts the search and returns
 * immediately instead of waiting for the search engine. Submitting a batch
 * first and collecting the results afterwards through the Search Archive API
 * is much faster than running the searches one after another.
 *
 * Usage:
 *  export SERPAPI_KEY="your secret key"
 *  php demo/demo_async.php
 */

require __DIR__ . '/../vendor/autoload.php';

use SerpApi\Client;

/**
 * Read search_metadata off a decoded response, failing loudly on anything
 * unexpected rather than on a property access further down.
 *
 * @param object|array<string, mixed>|string $response
 */
function search_metadata($response): object {
  if (!is_object($response) || !isset($response->search_metadata)) {
    throw new RuntimeException('response carries no search_metadata');
  }

  return $response->search_metadata;
}

$api_key = getenv('SERPAPI_KEY');
if (empty($api_key)) {
  fwrite(STDERR, "SERPAPI_KEY environment variable must be set\n");
  exit(1);
}

$companies = ['meta', 'amazon', 'apple', 'netflix', 'google'];

// Persistent mode keeps a single connection open for the whole batch.
$client = new Client([
  'engine'     => 'google',
  'api_key'    => $api_key,
  'async'      => true,
  'persistent' => true,
]);

// Submit every search without waiting for its results.
$pending = [];
foreach ($companies as $company) {
  $result = $client->search(['q' => $company]);
  $pending[search_metadata($result)->id] = $company;
  echo "submitted: {$company}\n";
}

echo "\ncollecting ", count($pending), " results\n";

// Collect the results, putting back the searches still in progress.
$deadline = time() + 60;
while (!empty($pending) && time() < $deadline) {
  foreach ($pending as $search_id => $company) {
    $archived = $client->search_archive((string) $search_id);
    $status = search_metadata($archived)->status;

    if ($status === 'Success' || $status === 'Cached') {
      $count = is_object($archived) && isset($archived->organic_results)
        ? count((array) $archived->organic_results)
        : 0;
      printf("  %-8s %s (%d organic results)\n", $company, $status, $count);
      unset($pending[$search_id]);
    }
  }

  if (!empty($pending)) {
    sleep(1);
  }
}

$client->close();

if (!empty($pending)) {
  fwrite(STDERR, 'timed out waiting for: ' . implode(', ', $pending) . "\n");
  exit(1);
}

echo "done\n";
exit(0);
