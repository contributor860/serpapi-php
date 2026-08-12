<?php

namespace SerpApi\Tests;

class ClientIntegrationTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $searchParams;

  protected function setUp(): void {
    parent::setUp();
    $this->searchParams = [
      'q' => 'Coffee',
      'location' => 'Austin,Texas',
    ];
  }

  public function test_account() {
    $client = $this->serpApiClient();
    $response = $client->account();
    $this->assertEquals($client->getApiKey(), $response->api_key);
  }

  public function test_html() {
    $client = $this->serpApiClient();
    $response = $client->html($this->searchParams);
    $this->assertGreaterThan(10000, strlen($response));
  }

  public function test_search() {
    $client = $this->serpApiClient();
    $response = $client->search($this->searchParams);
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
    $result = $client->search($this->searchParams);
    $archived_result = $client->searchArchive($result->search_metadata->id);
    $this->assertEquals($result->search_metadata->id, $archived_result->search_metadata->id);
  }
}
