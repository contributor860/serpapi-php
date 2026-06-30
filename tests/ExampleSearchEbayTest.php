<?php

namespace SerpApi\Tests;

class ExampleSearchEbayTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'ebay',
      '_nkw' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `ebay` engine: no `organic_results`');
  }
}
