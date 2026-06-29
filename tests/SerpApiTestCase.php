<?php

abstract class SerpApiTestCase extends \PHPUnit\Framework\TestCase {
  protected $api_key;

  protected function setUp(): void {
    $this->api_key = $this->apiKey();
  }

  protected function apiKey(): string {
    if(isset($_ENV['API_KEY'])) {
      return $_ENV['API_KEY'];
    }

    if(getenv('API_KEY')) {
      return getenv('API_KEY');
    }

    return 'demo';
  }

  protected function serpApiClient(): SerpApi {
    return new SerpApi($this->api_key);
  }
}
