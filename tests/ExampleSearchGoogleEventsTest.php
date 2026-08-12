<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleEventsTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $searchParams;

  protected function setUp(): void {
    parent::setUp();
    $this->searchParams = [
      'engine' => 'google_events',
      'q' => 'Events in Austin',
      'location' => 'Austin, Texas, United States',
    ];
  }

  public function test_result_exists() {
    $client = $this->serpApiClient();
    $response = $client->search($this->searchParams);
    $this->assertResponseHasProperty($response, 'events_results', 'Error on `google_events` engine: no `events_results`');
  }
}
