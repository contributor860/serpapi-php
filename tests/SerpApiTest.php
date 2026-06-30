<?php

class SerpApiTest extends SerpApiTestCase {

  private $search_params;

  protected function requiresApiKey(): bool {
    return false;
  }

  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'q' => "Coffee",
      'location' => "Austin,Texas"
    ];
  }

  function test_if_API_key_not_exist() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('API_KEY must be present');
    $search = new SerpApi();
    $search->search($this->search_params);
  }

  function test_if_empty_engine() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('engine must be present');
    $search = new SerpApi($this->api_key, '');
    $search->search($this->search_params);
  }

  function test_if_API_key_error() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessageMatches('/Invalid API key/i');
    $search = new SerpApi('not_valid_Key');
    $search->search();
  }

  function test_search_archive_if_miss_id() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('search_id must be present');
    $search = $this->serpApiClient();
    $search->search_archive();
  }

  function test_search_archive_if_invalid_format() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('format must be json or html');
    $search = new SerpApi('test_key');
    $search->search_archive('abc', 'xml');
  }

  function test_if_invalid_output() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('output must be json or html');
    $search = new SerpApiSearch('test_key');
    $search->search('xml');
  }
}
