<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleTrendsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_trends',
      'q' => 'coffee,milk,bread,pasta,steak',
      'data_type' => 'TIMESERIES',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'interest_over_time', 'Error on `google_trends` engine: no `interest_over_time`');
  }
}
