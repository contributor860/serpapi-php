<?php

class ExampleSearchGoogleMapsTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_maps',
      'q' => 'pizza',
      'll' => '@40.7455096,-74.0083012,15.1z',
      'type' => 'search'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('local_results', $response, "Error on `{google_maps}` engine not has `{local_results}`");
  }
}
