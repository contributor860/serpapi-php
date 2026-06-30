<?php

class SerpApiIntegrationTest extends SerpApiTestCase {

  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];
  }

  function test_account() {
    $search = $this->serpApiClient();
    $response = $search->account();
    $this->assertEquals($search->api_key, $response->api_key);
  }

  function test_html() {
    $search = $this->serpApiClient();
    $response = $search->html($this->search_params);
    $this->assertGreaterThan(10000, strlen($response));
  }

  function test_search() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertEquals("Success", $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  function test_location_method() {
    $search = $this->serpApiClient();
    $location_list = $search->location(["q" => "Austin", "limit" => 3]);
    $this->assertCount(3, $location_list);
    $this->assertStringContainsString('Austin', $location_list[0]->name);
    $this->assertGreaterThan(0, $location_list[0]->google_id);
  }

  function test_search_archive_method() {
    $search = $this->serpApiClient();
    $result = $search->search($this->search_params);
    $archived_result = $search->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
