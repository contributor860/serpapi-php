<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleNewsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_news',
      'q' => 'pizza',
      'gl' => 'us',
      'hl' => 'en',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'news_results', 'Error on `google_news` engine: no `news_results`');
  }
}
