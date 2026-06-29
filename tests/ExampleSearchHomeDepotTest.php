<?php

class ExampleSearchHomeDepotTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'home_depot',
      'q' => 'table'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'products', "Error on `{home_depot}` engine not has `{products}`");
  }
}
