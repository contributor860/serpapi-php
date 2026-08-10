<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleImagesLightTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_images_light',
      'q' => 'Coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'images_results', 'Error on `google_images_light` engine: no `images_results`');
  }
}
