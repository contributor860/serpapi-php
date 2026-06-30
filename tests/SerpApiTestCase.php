<?php

abstract class SerpApiTestCase extends \PHPUnit\Framework\TestCase {
  protected $api_key;

  protected function setUp(): void {
    parent::setUp();
    $resolved = $this->resolveApiKey();

    if($resolved === null && $this->requiresApiKey()) {
      $this->markTestSkipped('API_KEY is not set');
      return;
    }

    $this->api_key = $resolved;
  }

  protected function requiresApiKey(): bool {
    return true;
  }

  protected function resolveApiKey(): ?string {
    $env = $_ENV['API_KEY'] ?? null;

    if(!empty($env)) {
      return $env;
    }

    $value = getenv('API_KEY');
    if(!empty($value)) {
      return $value;
    }

    return null;
  }

  protected function serpApiClient(): SerpApi {
    return new SerpApi($this->api_key);
  }

  protected function assertResponseHasProperty(object $response, string $property, string $message = ''): void {
    if(method_exists($this, 'assertObjectHasProperty')) {
      $this->assertObjectHasProperty($property, $response, $message);
      return;
    }

    $this->assertTrue(
      property_exists($response, $property),
      $message ?: "Failed asserting that object has property '{$property}'"
    );
  }
}
