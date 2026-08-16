<?php

namespace SerpApi\Tests;

use SerpApi\Client;
use SerpApi\SerpApiException;

class ClientTest extends SerpApiTestCase {
  protected function requiresApiKey(): bool {
    return false;
  }

  public function test_throws_when_api_key_missing() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('api_key must be present');
    $client = new Client();
    $client->search(['q' => 'Coffee']);
  }

  public function test_throws_when_engine_empty() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('engine must be present');
    new Client('test_key', '');
  }

  public function test_throws_when_api_key_invalid() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessageMatches('/Invalid API key/i');
    $client = new Client('not_valid_key');
    $client->search(['q' => 'Coffee']);
  }

  public function test_search_archive_throws_when_id_empty() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('search_id must be present');
    $client = new Client('test_key');
    $client->search_archive('');
  }

  public function test_search_archive_throws_when_format_invalid() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('format must be json, html or md');
    $client = new Client('test_key');
    $client->search_archive('abc', 'xml');
  }

  public function test_set_api_key_throws_when_empty() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('api_key must have a value');
    $client = new Client('test_key');
    $client->set_api_key('');
  }

  public function test_set_api_key_updates_value() {
    $client = new Client('initial_key');
    $client->set_api_key('updated_key');
    $this->assertEquals('updated_key', $client->get_api_key());
  }

  public function test_get_engine_returns_engine() {
    $client = new Client('test_key', 'bing');
    $this->assertEquals('bing', $client->get_engine());
  }
}
