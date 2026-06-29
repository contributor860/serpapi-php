<?php

class SerpApiGoogleSearchTest extends SerpApiTestCase {

  private $QUERY;
  protected function setUp(): void {
    parent::setUp();
     $this->QUERY = [
      'q' => "Coffee", 
      'location' => "Austin,Texas"
    ];
  }
  
  public function testGoogleSearch() {
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_json($this->QUERY);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
    $this->assertGreaterThan(5, strlen($response->organic_results[0]->title));
  }

  public function test_google_get_html_method() {
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_html($this->QUERY);
    $this->assertGreaterThan(10000, strlen($response));
  }

  public function test_google_get_account_method() {
    $client = new GoogleSearch($this->api_key);
    $info = $client->get_account();
    $this->assertEquals($this->api_key , $info->api_key);
  }

  public function test_google_get_location_method() {
    $client = new GoogleSearch($this->api_key);
    $location_list = $client->get_location('Austin', 3);
    $this->assertEquals(200635, $location_list[0]->google_id);
  }

  public function test_google_get_search_archive_method() {
    $client = new GoogleSearch($this->api_key);
    $result = $client->get_json($this->QUERY);
    $archived_result = $client->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }

  public function test_bing_get_search_method() {
    $client = new SerpApiSearch($this->api_key, 'bing');
    $response = $client->get_json($this->QUERY);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_baidu_get_search_method() {
    $client = new SerpApiSearch($this->api_key, 'baidu');
    $response = $client->get_json($this->QUERY);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_yahoo_get_search_method() {
    $query = [
      'p' => "Coffee",
      'engine'  => 'yahoo'
    ];
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_yandex_get_search_method() {
    $query = [
      "engine" => "yandex",
      'text' => "Coffee",
    ];
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_ebay_get_search_method() {
    $query = [
      "engine" => "ebay",
      '_nkw' => "Coffee",
      "no_cache" => true
    ];
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_youtube_get_search_method() {
    $query = [
      "engine" => "youtube",
      'search_query' => "Coffee"
    ];
    $client = new GoogleSearch($this->api_key);
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->video_results));
  }

  public function test_serpapiclient_get_search_method() {
    $query = [
      'q' => "Coffee"
    ];
    $client = new SerpApiSearch($this->api_key, 'google');
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_google_get_search_method() {
    $client = new GoogleSearch($this->api_key);
    $response = $client->search("json", $this->QUERY);
    $this->assertGreaterThan(5, count($response->organic_results));
  }
}
