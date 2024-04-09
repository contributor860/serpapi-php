<?php

class ExampleSearchNaverTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'naver',
      'query' => 'coffee'
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
    $this->assertObjectHasAttribute('ads_results', $response, "Error on `{naver}` engine not has `{ads_results}`");
  }
}
