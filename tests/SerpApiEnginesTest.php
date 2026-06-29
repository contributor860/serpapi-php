<?php

class SerpApiEnginesTest extends SerpApiTestCase {

  private $QUERY;

  protected function setUp(): void {
    parent::setUp();
    $this->QUERY = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];
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
    $client = new SerpApiSearch($this->api_key, 'yahoo');
    $response = $client->get_json(['p' => "Coffee"]);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_yandex_get_search_method() {
    $client = new SerpApiSearch($this->api_key, 'yandex');
    $response = $client->get_json(['text' => "Coffee"]);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_ebay_get_search_method() {
    $client = new SerpApiSearch($this->api_key, 'ebay');
    $response = $client->get_json([
      '_nkw' => "Coffee",
      'no_cache' => true
    ]);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->organic_results));
  }

  public function test_youtube_get_search_method() {
    $client = new SerpApiSearch($this->api_key, 'youtube');
    $response = $client->get_json(['search_query' => "Coffee"]);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertGreaterThan(5, count($response->video_results));
  }
}
