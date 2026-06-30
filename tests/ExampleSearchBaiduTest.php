<?php

namespace SerpApi\Tests;

class ExampleSearchBaiduTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'baidu',
      'q' => 'coffee',
    ];
  }

  public function test_if_result_exist() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `baidu` engine: no `organic_results`');
  }
}
