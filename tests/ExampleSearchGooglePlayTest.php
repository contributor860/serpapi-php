<?php

class ExampleSearchGooglePlayTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_play',
      'q' => 'kite',
      'store' => 'apps'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', "Error on `{google_play}` engine not has `{organic_results}`");
  }
}
