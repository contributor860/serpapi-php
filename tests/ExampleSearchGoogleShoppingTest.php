<?php

class ExampleSearchGoogleShoppingTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_shopping',
      'q' => 'coffee',
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'shopping_results', "Error on `{google_shopping}` engine not has `{shopping_results}`");
  }
}
