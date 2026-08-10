<?php

/**
 * Google Autocomplete suggestions.
 *
 * Shows default search parameters set once on the client (client, hl, gl)
 * and reused by every call, so only the query changes per search.
 *
 * Usage:
 *  export API_KEY="your secret key"
 *  php demo/demo_suggest.php
 */

require __DIR__ . '/../vendor/autoload.php';

use SerpApi\Client;

$api_key = getenv('API_KEY');
if (empty($api_key)) {
  fwrite(STDERR, "API_KEY environment variable must be set\n");
  exit(1);
}

$client = new Client([
  'engine'     => 'google_autocomplete',
  'api_key'    => $api_key,
  'client'     => 'safari',
  'hl'         => 'en',
  'gl'         => 'us',
  'persistent' => false,
  'timeout'    => 10,
]);

$results = $client->search(['q' => 'coffee']);

if (empty($results->suggestions)) {
  fwrite(STDERR, "no suggestions found\n");
  exit(1);
}

foreach ($results->suggestions as $suggestion) {
  echo ' - ', $suggestion->value, "\n";
}

echo "done\n";
exit(0);
