<?php

class ExampleSearchGoogleReverseImageTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_reverse_image',
      'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'image_sizes', "Error on `{google_reverse_image}` engine not has `{image_sizes}`");
  }
}
