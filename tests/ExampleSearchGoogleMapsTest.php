<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleMapsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_maps',
      'q' => 'pizza',
      'll' => '@40.7455096,-74.0083012,15.1z',
      'type' => 'search',
    ];
  }

  public function test_if_result_exist() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'local_results', 'Error on `google_maps` engine: no `local_results`');
  }
}
