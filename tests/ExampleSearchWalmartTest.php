<?php

class ExampleSearchWalmartTest extends SerpApiTestCase {

  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'walmart',
      'query' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', "Error on `{walmart}` engine not has `{organic_results}`");
  }
}
