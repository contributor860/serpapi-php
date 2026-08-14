<?php

namespace SerpApi\Tests;

use SerpApi\Client;
use PHPUnit\Framework\TestCase;

abstract class SerpApiTestCase extends TestCase {
  /** @var string|null */
  protected $api_key;

  protected function setUp(): void {
    parent::setUp();
    $resolved = $this->resolveApiKey();

    if ($resolved === null && $this->requiresApiKey()) {
      $this->markTestSkipped('SERPAPI_KEY is not set');
      return;
    }

    $this->api_key = $resolved;
  }

  protected function requiresApiKey(): bool {
    return true;
  }

  /**
   * Look up the secret key, preferring SERPAPI_KEY. API_KEY is the previous
   * name, still accepted so existing setups keep working.
   *
   * @var array<int, string>
   */
  protected static $api_key_env_names = ['SERPAPI_KEY', 'API_KEY'];

  protected function resolveApiKey(): ?string {
    foreach (self::$api_key_env_names as $name) {
      $env = $_ENV[$name] ?? null;
      if (!empty($env)) {
        return $env;
      }

      $value = getenv($name);
      if (!empty($value)) {
        return $value;
      }
    }

    return null;
  }

  protected function serpApiClient(string $engine = 'google'): Client {
    return new Client($this->api_key ?? '', $engine);
  }

  protected function assertResponseHasProperty(object $response, string $property, string $message = ''): void {
    if (method_exists($this, 'assertObjectHasProperty')) {
      $this->assertObjectHasProperty($property, $response, $message);
      return;
    }

    $this->assertTrue(
      property_exists($response, $property),
      $message ?: "Failed asserting that object has property '{$property}'"
    );
  }
}
