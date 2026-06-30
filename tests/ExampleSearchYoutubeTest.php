<?php

namespace SerpApi\Tests;

class ExampleSearchYoutubeTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'youtube',
      'search_query' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'video_results', 'Error on `youtube` engine: no `video_results`');
  }
}
