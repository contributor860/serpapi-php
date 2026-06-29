<?php

class ExampleSearchGoogleMapsTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_maps',
      'q' => 'pizza',
      'll' => '@40.7455096,-74.0083012,15.1z',
      'type' => 'search'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'local_results', "Error on `{google_maps}` engine not has `{local_results}`");
  }
}
