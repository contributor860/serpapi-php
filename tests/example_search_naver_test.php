<?php

class ExampleSearchNaverTest extends \PHPUnit\Framework\TestCase {

  private $_search_params;
  private $_api_key;

  protected function setUp(): void {
    $this->_search_params = [
      'engine' => 'naver',
      'query' => 'coffee'
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
    $this->assertObjectHasAttribute('ads_results', $response, "Error on `{naver}` engine not has `{ads_results}`");
  }
}
