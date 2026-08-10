<?php

namespace SerpApi\Tests;

class ExampleSearchYelpTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'yelp',
      'find_desc' => 'Coffee',
      'find_loc' => 'New York, NY, USA',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `yelp` engine: no `organic_results`');
  }
}
