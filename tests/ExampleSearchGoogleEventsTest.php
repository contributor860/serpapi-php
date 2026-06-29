<?php

class ExampleSearchGoogleEventsTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_events',
      'q' => 'Events in Austin'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'events_results', "Error on `{google_events}` engine not has `{events_results}`");
  }
}
