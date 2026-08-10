<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleNewsLightTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_news_light',
      'q' => 'pizza',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'news_results', 'Error on `google_news_light` engine: no `news_results`');
  }
}
