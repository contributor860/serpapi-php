<?php

/**
 * Basic search.
 *
 * Prerequisites:
 *  - composer install
 *  - export API_KEY="your secret key"  (get one at https://serpapi.com/dashboard)
 *
 * Usage:
 *  php demo/demo.php
 */

require __DIR__ . '/../vendor/autoload.php';

use SerpApi\Client;

$api_key = getenv('API_KEY');
if (empty($api_key)) {
  fwrite(STDERR, "API_KEY environment variable must be set\n");
  exit(1);
}

// Client initialization with default parameters.
$client = new Client([
  'engine'  => 'google',
  'api_key' => $api_key,
]);

$results = $client->search(['q' => 'coffee']);

if (empty($results->organic_results)) {
  fwrite(STDERR, "no organic results found\n");
  exit(1);
}

foreach ($results->organic_results as $result) {
  printf("%d. %s\n   %s\n", $result->position, $result->title, $result->link);
}

$client->close();
echo "done\n";
exit(0);
