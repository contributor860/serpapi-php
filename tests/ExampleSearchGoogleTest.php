<?php

class ExampleSearchGoogleTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google',
      'tbm' => 'isch',
      'q' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('images_results', $response, "Error on `{google}` engine not has `{images_results}`");
  }
}
