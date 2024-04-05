<?php

class serpapiTest extends \PHPUnit\Framework\TestCase {

  private $_search_params;
  private $_api_key;

  protected function setUp(): void {
    $this->_search_params = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->_search_params['api_key'] = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->_search_params['api_key'] = getenv('API_KEY');
    } else {
      $this->_search_params['api_key'] = "demo";
    }
 }

  function test_if_API_key_not_exist() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('API_KEY must be present');
    unset($this->_search_params['api_key']);
    $search = new SerpApi($this->_search_params);
    $search->get_json();
  }

  function test_if_API_key_error() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('Invalid API key. Your API key should be here: https://serpapi.com/manage-api-key');
    $search = new SerpApi(['api_key' => 'not_valid_Key']);
    $search->get_json();
  }

  function test_get_account() {
    $search = new SerpApi($this->_search_params);
    $response = $search->get_account();
    $this->assertEquals($search->_api_key, $response->api_key);
  }

  function test_if_miss_parametrs_in_get_html() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('parameters must be an array and has a value');
    $search = new SerpApi();
    $search->get_html();
  }

  function test_get_html() {
    $search = new SerpApi($this->_search_params);
    $response = $search->get_html();
    $this->assertGreaterThan(10000, strlen($response));
  }

  function test_if_miss_parametrs_in_get_json() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('parameters must be an array and has a value');
    $search = new SerpApi();
    $search->get_json();
  }

  function test_get_json() {
    $search = new SerpApi($this->_search_params);
    $response = $search->get_json();
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
    $this->assertGreaterThan(5, strlen($response->organic_results[0]->title));
  }

  function test_google_get_location_method() {
    $client = new SerpApi($this->_search_params);
    $location_list = $client->get_location('Austin', 3);
    $this->assertEquals(200635, $location_list[0]->google_id);
  }

  function test_get_search_archive_if_miss_id() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('search_id must be present');
    $client = new SerpApi($this->_search_params);
    $client->get_search_archive();
  }

  function test_get_search_archive_method() {
    $client = new SerpApi($this->_search_params);
    $result = $client->get_json();
    $archived_result = $client->get_search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
