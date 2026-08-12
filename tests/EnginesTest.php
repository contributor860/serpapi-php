<?php

namespace SerpApi\Tests;

class EnginesTest extends SerpApiTestCase {
  /** @var array<string, mixed> */
  private $searchParams;

  protected function setUp(): void {
    parent::setUp();
    $this->searchParams = [
      'q' => 'Coffee',
      'location' => 'Austin,Texas',
    ];
  }

  public function test_bing_search() {
    $client = $this->serpApiClient('bing');
    $response = $client->search($this->searchParams);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_baidu_search() {
    $client = $this->serpApiClient('baidu');
    $response = $client->search($this->searchParams);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_yahoo_search() {
    $client = $this->serpApiClient('yahoo');
    $response = $client->search(['p' => 'Coffee']);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_yandex_search() {
    $client = $this->serpApiClient('yandex');
    $response = $client->search(['text' => 'Coffee']);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_ebay_search() {
    $client = $this->serpApiClient('ebay');
    $response = $client->search([
      '_nkw' => 'Coffee',
      'no_cache' => true,
    ]);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'organic_results');
    $this->assertNotEmpty($response->organic_results);
  }

  public function test_youtube_search() {
    $client = $this->serpApiClient('youtube');
    $response = $client->search(['search_query' => 'Coffee']);
    $this->assertEquals('Success', $response->search_metadata->status);
    $this->assertResponseHasProperty($response, 'video_results');
    $this->assertNotEmpty($response->video_results);
  }
}
