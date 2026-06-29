<?php

class ExampleSearchYoutubeTest extends SerpApiTestCase {

  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'youtube',
      'search_query' => 'coffee'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('video_results', $response, "Error on `{youtube}` engine not has `{video_results}`");
  }
}
