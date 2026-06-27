<?php

class ExampleSearchHomeDepotTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'home_depot',
      'q' => 'table'
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
    $this->assertObjectHasAttribute('products', $response, "Error on `{home_depot}` engine not has `{products}`");
  }
}
