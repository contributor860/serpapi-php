<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleImagesTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_images',
      'tbm' => 'isch',
      'q' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'images_results', 'Error on `google_images` engine: no `images_results`');
  }
}
