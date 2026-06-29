<?php

class ExampleSearchAppleAppStoreTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'apple_app_store',
      'term' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{apple_app_store}` engine not has `{organic_results}`");
  }
}
