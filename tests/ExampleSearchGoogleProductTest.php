<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleProductTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_product',
      'q' => 'coffee',
      'product_id' => '4887235756540435899',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'product_results', 'Error on `google_product` engine: no `product_results`');
  }
}
