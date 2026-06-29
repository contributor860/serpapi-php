<?php

class ExampleSearchGoogleLensTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_lens',
      'url' => 'https://i.imgur.com/5bGzZi7.jpg',
      'gl' => 'us',
      'hl' => 'en',
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'visual_matches', "Error on `{google_lens}` engine not has `{visual_matches}`");
  }
}
