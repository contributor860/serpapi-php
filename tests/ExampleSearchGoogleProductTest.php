<?php

class ExampleSearchGoogleProductTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_product',
      'q' => 'coffee',
      'product_id' => '4172129135583325756'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('product_results', $response, "Error on `{google_product}` engine not has `{product_results}`");
  }
}
