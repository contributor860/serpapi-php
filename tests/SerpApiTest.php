<?php

class SerpApiTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_API_key_not_exist() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('API_KEY must be present');
    $search = new SerpApi();
    $search->search($this->search_params);
  }

  function test_if_empty_engine() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('engine must be present');
    $search = new SerpApi($this->api_key, '');
    $search->search($this->search_params);
  }

  function test_if_API_key_error() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessageMatches('/Invalid API key/i');
    $search = new SerpApi('not_valid_Key');
    $search->search();
  }

  function test_account() {
    $search = new SerpApi($this->api_key);
    $response = $search->account();
    $this->assertEquals($search->api_key, $response->api_key);
  }

  function test_html() {
    $search = new SerpApi($this->api_key);
    $response = $search->html($this->search_params);
    $this->assertGreaterThan(10000, strlen($response));
  }

  function test_search() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
    $this->assertGreaterThan(5, strlen($response->organic_results[0]->title));
  }

  function test_location_method() {
    $search = new SerpApi($this->api_key);
    $location_list = $search->location(["q" => "Austin", "limit" => 3]);
    $this->assertEquals(200635, $location_list[0]->google_id);
  }

  function test_search_archive_if_miss_id() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('search_id must be present');
    $search = new SerpApi($this->api_key);
    $search->search_archive();
  }

  function test_search_archive_method() {
    $search = new SerpApi($this->api_key);
    $result = $search->search($this->search_params);
    $archived_result = $search->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
