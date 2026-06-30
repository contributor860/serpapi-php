<?php

namespace SerpApi\Tests;

class ExampleSearchHomeDepotTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'home_depot',
      'q' => 'table',
    ];
  }

  public function test_if_result_exist() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'products', 'Error on `home_depot` engine: no `products`');
  }
}
