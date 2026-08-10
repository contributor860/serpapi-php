<?php

namespace SerpApi\Tests;

use SerpApi\Client;

class ClientInspectTest extends SerpApiTestCase {
  const LONG_KEY = 'abcd1234secret5678wxyz';

  protected function requiresApiKey(): bool {
    return false;
  }

  public function test_inspect_does_not_expose_the_api_key() {
    $client = new Client(self::LONG_KEY);
    $this->assertStringNotContainsString(self::LONG_KEY, $client->inspect());
  }

  public function test_inspect_shows_first_and_last_four_characters() {
    $client = new Client(self::LONG_KEY);
    $this->assertStringContainsString('abcd****wxyz', $client->inspect());
  }

  public function test_inspect_reports_engine_and_timeout() {
    $client = new Client(self::LONG_KEY, 'bing', 30);
    $inspect = $client->inspect();

    $this->assertStringContainsString('@engine=bing', $inspect);
    $this->assertStringContainsString('@timeout=30', $inspect);
  }

  public function test_short_api_key_is_fully_masked() {
    $client = new Client('abcdef');
    $inspect = $client->inspect();

    $this->assertStringNotContainsString('abcdef', $inspect);
    $this->assertStringContainsString('****', $inspect);
  }

  public function test_eight_character_api_key_is_fully_masked() {
    $client = new Client('abcdefgh');
    $this->assertStringNotContainsString('abcdefgh', $client->inspect());
  }

  public function test_missing_api_key_renders_as_empty() {
    $client = new Client();
    $this->assertStringContainsString('@api_key=>', $client->inspect());
  }

  public function test_var_dump_does_not_expose_the_api_key() {
    $client = new Client(self::LONG_KEY);

    ob_start();
    var_dump($client);
    $dump = (string) ob_get_clean();

    $this->assertStringNotContainsString(self::LONG_KEY, $dump);
    $this->assertStringContainsString('abcd****wxyz', $dump);
  }

  public function test_debug_info_does_not_expose_the_api_key_in_default_params() {
    $client = new Client(['api_key' => self::LONG_KEY, 'hl' => 'en']);
    $info = $client->__debugInfo();

    $this->assertEquals('abcd****wxyz', $info['api_key']);
    $this->assertEquals(['hl' => 'en'], $info['params']);
  }
}
