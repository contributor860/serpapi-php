<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleFlightsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();

    // Dates are computed rather than hardcoded so the test does not expire.
    $this->search_params = [
      'engine' => 'google_flights',
      'departure_id' => 'PEK',
      'arrival_id' => 'AUS',
      'outbound_date' => date('Y-m-d', strtotime('+30 days')),
      'return_date' => date('Y-m-d', strtotime('+37 days')),
      'currency' => 'USD',
      'hl' => 'en',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'best_flights', 'Error on `google_flights` engine: no `best_flights`');
  }
}
