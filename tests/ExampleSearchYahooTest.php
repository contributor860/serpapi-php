<?php

namespace SerpApi\Tests;

class ExampleSearchYahooTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $searchParams;

  protected function setUp(): void {
    parent::setUp();
    $this->searchParams = [
      'engine' => 'yahoo',
      'p' => 'coffee',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->searchParams);
    $this->assertResponseHasProperty($response, 'organic_results', 'Error on `yahoo` engine: no `organic_results`');
  }
}
