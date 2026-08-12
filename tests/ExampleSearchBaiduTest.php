<?php

namespace SerpApi\Tests;

class ExampleSearchBaiduTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $searchParams;

  protected function setUp(): void {
    parent::setUp();
    $this->searchParams = [
      'engine' => 'baidu',
      'q' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->searchParams);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `baidu` engine: no `organic_results`');
  }
}
