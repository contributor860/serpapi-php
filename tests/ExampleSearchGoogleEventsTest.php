<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleEventsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_events',
      'q' => 'Events in Austin',
      'location' => 'Austin, Texas, United States',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'events_results', 'Error on `google_events` engine: no `events_results`');
  }
}
