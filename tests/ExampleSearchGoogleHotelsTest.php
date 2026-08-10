<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleHotelsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();

    // Dates are computed rather than hardcoded so the test does not expire.
    $this->search_params = [
      'engine' => 'google_hotels',
      'q' => 'Bali Resorts',
      'check_in_date' => date('Y-m-d', strtotime('+30 days')),
      'check_out_date' => date('Y-m-d', strtotime('+31 days')),
      'adults' => '2',
      'currency' => 'USD',
      'gl' => 'us',
      'hl' => 'en',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'properties', 'Error on `google_hotels` engine: no `properties`');
  }
}
