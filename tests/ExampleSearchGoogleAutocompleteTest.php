<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleAutocompleteTest extends SerpApiTestCase {
  /** @var array<string, string> */
  private $search_params;

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_autocomplete',
      'q' => 'coffee',
    ];
  }

  public function test_if_result_exist() {
    $client = $this->serpApiClient();
    $response = $client->search($this->search_params);
    $this->assertResponseHasProperty($response, 'suggestions', 'Error on `google_autocomplete` engine: no `suggestions`');
  }
}
