<?php

namespace SerpApi\Tests;

class GoogleSearchTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'q' => 'Coffee',
      'location' => 'Austin,Texas',
    ];
  }

  public function test_google_search_returns_organic_results() {
    $client = $this->serpApiClient('google');
    $response = $client->search($this->search_params);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_google_html_returns_html_payload() {
    $client = $this->serpApiClient('google');
    $response = $client->html($this->search_params);
    $this->assertGreaterThan(10000, strlen($response));
  }

  public function test_google_account_returns_api_key() {
    $client = $this->serpApiClient('google');
    $info = $client->account();
    $this->assertEquals($client->get_api_key(), $info->api_key);
  }

  public function test_google_location_returns_results() {
    $client = $this->serpApiClient('google');
    $location_list = $client->location(['q' => 'Austin', 'limit' => 3]);
    $this->assertCount(3, $location_list);
    $this->assertStringContainsString('Austin', $location_list[0]->name);
    $this->assertGreaterThan(0, $location_list[0]->google_id);
  }

  public function test_google_search_archive_returns_same_id() {
    $client = $this->serpApiClient('google');
    $result = $client->search($this->search_params);
    $archived_result = $client->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
