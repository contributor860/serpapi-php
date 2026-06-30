<?php

namespace SerpApi\Tests;

class ExampleSearchWalmartTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'walmart',
      'query' => 'coffee',
    ];
  }

  public function test_if_result_exist() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `walmart` engine: no `organic_results`');
  }
}
