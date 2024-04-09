<?php

class ExampleSearchGoogleAutocompleteTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_autocomplete',
      'q' => 'coffee'
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
    $this->assertObjectHasAttribute('suggestions', $response, "Error on `{google_autocomplete}` engine not has `{suggestions}`");
  }
}
