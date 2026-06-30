<?php

namespace SerpApi\Tests;

class ExampleSearchYahooTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'yahoo',
      'p' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `yahoo` engine: no `organic_results`');
  }
}
