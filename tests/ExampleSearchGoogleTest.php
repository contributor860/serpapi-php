<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google',
      'tbm' => 'isch',
      'q' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'images_results', 'Error on `google` engine: no `images_results`');
  }
}
