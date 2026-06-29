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
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
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
    $this->assertCount(3, $location_list);
    $this->assertStringContainsString('Austin', $location_list[0]->name);
    $this->assertGreaterThan(0, $location_list[0]->google_id);
  }

  public function test_google_get_search_archive_method() {
    $client = new GoogleSearch($this->api_key);
    $result = $client->get_json($this->QUERY);
    $archived_result = $client->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }

  public function test_serpapiclient_get_search_method() {
    $query = [
      'q' => "Coffee"
    ];
    $client = new SerpApiSearch($this->api_key, 'google');
    $response = $client->get_json($query);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_google_get_search_method() {
    $client = new GoogleSearch($this->api_key);
    $response = $client->search("json", $this->QUERY);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }
}
