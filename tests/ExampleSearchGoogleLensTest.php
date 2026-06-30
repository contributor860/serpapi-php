<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleLensTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_lens',
      'url' => 'https://i.imgur.com/5bGzZi7.jpg',
      'gl' => 'us',
      'hl' => 'en',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'visual_matches', 'Error on `google_lens` engine: no `visual_matches`');
  }
}
