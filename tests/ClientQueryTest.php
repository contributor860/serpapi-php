<?php

namespace SerpApi\Tests;

use SerpApi\Client;

/**
 * Unit tests for the query string assembled before each HTTP request.
 * These run without an API key and without touching the network.
 */
class ClientQueryTest extends SerpApiTestCase {
  protected function requiresApiKey(): bool {
    return false;
  }

  /**
   * @param array<string, mixed> $params
   * @return array<string, mixed>
   */
  protected function query(Client $client, array $params = [], string $api_key = 'secret', string $format = 'json'): array {
    $method = new \ReflectionMethod(Client::class, 'query');

    // Required before PHP 8.1, and a no-op deprecated in PHP 8.5.
    if (PHP_VERSION_ID < 80100) {
      $method->setAccessible(true);
    }

    return $method->invoke($client, $params, $api_key, $format);
  }

  public function test_source_reports_library_name_and_version() {
    $query = $this->query(new Client('secret'));
    $this->assertEquals('serpapi-php:' . Client::VERSION, $query['source']);
  }

  public function test_source_constant_matches_version() {
    $this->assertStringStartsWith('serpapi-php:', Client::SOURCE);
    $this->assertStringEndsWith(Client::VERSION, Client::SOURCE);
  }

  public function test_query_includes_engine_api_key_and_output() {
    $query = $this->query(new Client('secret', 'bing'), ['q' => 'coffee']);
    $this->assertEquals('bing', $query['engine']);
    $this->assertEquals('secret', $query['api_key']);
    $this->assertEquals('coffee', $query['q']);
    $this->assertEquals('json', $query['output']);
  }

  public function test_query_omits_api_key_when_empty() {
    $query = $this->query(new Client(), [], '');
    $this->assertArrayNotHasKey('api_key', $query);
  }

  public function test_output_reflects_requested_format() {
    $query = $this->query(new Client('secret'), [], 'secret', 'html');
    $this->assertEquals('html', $query['output']);
  }
}
