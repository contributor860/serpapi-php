<?php

class ExampleSearchDuckduckgoTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'duckduckgo',
      'q' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{duckduckgo}` engine not has `{organic_results}`");
  }
}
