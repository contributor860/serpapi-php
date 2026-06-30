<?php

namespace SerpApi\Tests;

class ClientIntegrationTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'q' => 'Coffee',
      'location' => 'Austin,Texas',
    ];
  }

  public function test_account() {
    $client = $this->serpApiClient();
    $response = $client->account();
    $this->assertEquals($client->get_api_key(), $response->api_key);
  }

  public function test_html() {
    $client = $this->serpApiClient();
    $response = $client->html($this->search_params);
    $this->assertGreaterThan(10000, strlen($response));
  }

  public function test_search() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_location() {
    $client = $this->serpApiClient();
    $location_list = $client->location(['q' => 'Austin', 'limit' => 3]);
    $this->assertCount(3, $location_list);
    $this->assertStringContainsString('Austin', $location_list[0]->name);
    $this->assertGreaterThan(0, $location_list[0]->google_id);
  }

  public function test_search_archive() {
    $client = $this->serpApiClient();
    $result = $client->search($this->search_params);
    $archived_result = $client->search_archive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
