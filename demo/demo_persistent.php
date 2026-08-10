<?php

/**
 * Persistent connection benchmark.
 *
 * Compares a client that reuses its connection against one that opens a new
 * one per request. Uses the Location API, which needs no API key.
 *
 * Usage:
 *  php demo/demo_persistent.php
 */

require __DIR__ . '/../vendor/autoload.php';

use SerpApi\Client;

const REQUESTS = 10;

/**
 * @return float elapsed seconds
 */
function benchmark(Client $client): float {
  $start = microtime(true);

  for ($i = 0; $i < REQUESTS; $i++) {
    $client->location(['q' => 'Austin', 'limit' => 1]);
  }

  return microtime(true) - $start;
}

$without = benchmark(new Client(['persistent' => false]));
printf("persistent off: %6.0f ms  (%.1f req/s)\n", $without * 1000, REQUESTS / $without);

$client = new Client(['persistent' => true]);
$with = benchmark($client);
$client->close();
printf("persistent on:  %6.0f ms  (%.1f req/s)\n", $with * 1000, REQUESTS / $with);

printf("\nspeedup: %.1fx\n", $without / $with);
echo "done\n";
exit(0);
