<?php

abstract class SerpApiTestCase extends \PHPUnit\Framework\TestCase {
  protected $api_key;

  protected function setUp(): void {
    parent::setUp();
    $this->api_key = $this->apiKey();
  }

  protected function apiKey(): string {
    $env = $_ENV['API_KEY'] ?? null;
    if(!empty($env)) {
      return $env;
    }

    $value = getenv('API_KEY');
    if(!empty($value)) {
      return $value;
    }

    return 'demo';
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
