<?php

class ExampleSearchGoogleAutocompleteTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_autocomplete',
      'q' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('suggestions', $response, "Error on `{google_autocomplete}` engine not has `{suggestions}`");
  }
}
