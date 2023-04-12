<?php

class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->API_KEY = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->API_KEY = getenv('API_KEY');
    } else {
      $this->API_KEY = "demo";
    }
 }

  function test_if_API_key_not_exist() {
    $this->expectException(SerpApiSearchException::class);
    $this->expectExceptionMessage('API_KEY must be present');
    new SerpApiSearch();
  }

  function test_if_API_key_error() {
    $this->expectException(SerpApiSearchException::class);
    $this->expectExceptionMessage('Invalid API key. Your API key should be here: https://serpapi.com/manage-api-key');
    $search = new SerpApiSearch('not_valid_Key');
    $search->get_json($this->QUERY);
  }

  function test_get_account() {
    $search = new SerpApiSearch($this->API_KEY);
    $response = $search->get_account();
    $this->assertEquals($this->API_KEY, $response->api_key);
  }

  function test_if_miss_parametrs_in_get_html() {
    $this->expectException(SerpApiSearchException::class);
    $this->expectExceptionMessage('parameters must be an array and has a value');
    $search = new SerpApiSearch($this->API_KEY);
    $search->get_html();
  }

  function test_get_html() {
    $search = new SerpApiSearch($this->API_KEY);
    $response = $search->get_html($this->QUERY);
    $this->assertGreaterThan(10000, strlen($response));
  }

  function test_if_miss_parametrs_in_get_json() {
    $this->expectException(SerpApiSearchException::class);
    $this->expectExceptionMessage('parameters must be an array and has a value');
    $search = new SerpApiSearch($this->API_KEY);
    $search->get_json();
  }

  function test_get_json() {
    $search = new SerpApiSearch($this->API_KEY);
    $response = $search->get_json($this->QUERY);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
    $this->assertGreaterThan(5, strlen($response->organic_results[0]->title));
  }

  function test_google_get_location_method() {
    $client = new SerpApiSearch($this->API_KEY);
    $location_list = $client->get_location('Austin', 3);
    $this->assertEquals(200635, $location_list[0]->google_id);
  }

  function test_get_search_archive_if_miss_id() {
    $this->expectException(SerpApiSearchException::class);
    $this->expectExceptionMessage('search_id must be present');
    $client = new SerpApiSearch($this->API_KEY);
    $client->get_search_archive();
  }

  function test_get_search_archive_method() {
    $client = new SerpApiSearch($this->API_KEY);
    $result = $client->get_json($this->QUERY);
    $archived_result = $client->get_search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
