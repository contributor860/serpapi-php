<?php

class ExampleSearchGoogleLocalServicesTest extends \PHPUnit\Framework\TestCase {
  
  private $_search_params;
  private $_api_key;

  protected function setUp(): void {
    $this->_search_params = [
      'engine' => 'google_local_services',
      'q' => 'electrician',
      'data_cid' => '6745062158417646970'
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
    $this->assertObjectHasAttribute('local_ads', $response, "Error on `{google_local_services}` engine not has `{local_ads}`");
  }
}
