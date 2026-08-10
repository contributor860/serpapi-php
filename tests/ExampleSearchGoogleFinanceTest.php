<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleFinanceTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_finance',
      'q' => 'GOOG:NASDAQ',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'markets', 'Error on `google_finance` engine: no `markets`');
  }
}
