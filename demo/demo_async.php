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
 *  export API_KEY="your secret key"
 *  php demo/demo_async.php
 */

require __DIR__ . '/../vendor/autoload.php';

use SerpApi\Client;

$api_key = getenv('API_KEY');
if (empty($api_key)) {
  fwrite(STDERR, "API_KEY environment variable must be set\n");
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
  $pending[$result->search_metadata->id] = $company;
  echo "submitted: {$company}\n";
}

echo "\ncollecting ", count($pending), " results\n";

// Collect the results, putting back the searches still in progress.
$deadline = time() + 60;
while (!empty($pending) && time() < $deadline) {
  foreach ($pending as $search_id => $company) {
    $archived = $client->search_archive($search_id);
    $status = $archived->search_metadata->status;

    if ($status === 'Success' || $status === 'Cached') {
      $count = isset($archived->organic_results) ? count($archived->organic_results) : 0;
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
