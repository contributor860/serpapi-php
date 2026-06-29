<?php

class ExampleSearchGoogleJobsTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_jobs',
      'q' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('jobs_results', $response, "Error on `{google_jobs}` engine not has `{jobs_results}`");
  }
}
