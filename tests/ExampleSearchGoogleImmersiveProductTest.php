<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleImmersiveProductTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_immersive_product',
      'q' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'immersive_product_results', 'Error on `google_immersive_product` engine: no `immersive_product_results`');
  }
}
