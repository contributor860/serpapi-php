<?php

class ExampleSearchGooglePlayTest extends \PHPUnit\Framework\TestCase {
  
  private $_search_params;
  private $_api_key;

  protected function setUp(): void {
    $this->_search_params = [
      'engine' => 'google_play',
      'q' => 'kite',
      'store' => 'apps'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->_search_params['api_key'] = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->_search_params['api_key'] = getenv('API_KEY');
    } else {
      $this->_search_params['api_key'] = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->_search_params);
    $response = $search->get_json();
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{google_play}` engine not has `{organic_results}`");
  }
}
