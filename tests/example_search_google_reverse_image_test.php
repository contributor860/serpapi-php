<?php

class ExampleSearchGoogleReverseImageTest extends \PHPUnit\Framework\TestCase {
  
  private $_search_params;
  private $_api_key;

  protected function setUp(): void {
    $this->_search_params = [
      'engine' => 'google_reverse_image',
      'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'
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
    $this->assertObjectHasAttribute('image_sizes', $response, "Error on `{google_reverse_image}` engine not has `{image_sizes}`");
  }
}
