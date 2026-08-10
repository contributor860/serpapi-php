<?php

namespace SerpApi\Tests;

use SerpApi\Client;

/**
 * Connection reuse. The handle lifecycle is exercised directly so these
 * tests need neither an API key nor network access.
 */
class ClientPersistentTest extends SerpApiTestCase {
  protected function requiresApiKey(): bool {
    return false;
  }

  /**
   * @return resource|\CurlHandle
   */
  private function acquire(Client $client) {
    $method = new \ReflectionMethod(Client::class, 'acquire_handle');

    if (PHP_VERSION_ID < 80100) {
      $method->setAccessible(true);
    }

    return $method->invoke($client);
  }

  /**
   * @return resource|\CurlHandle|null
   */
  private function handle(Client $client) {
    $property = new \ReflectionProperty(Client::class, 'handle');

    if (PHP_VERSION_ID < 80100) {
      $property->setAccessible(true);
    }

    return $property->getValue($client);
  }

  public function test_persistent_is_enabled_by_default() {
    $this->assertTrue((new Client('secret'))->is_persistent());
  }

  public function test_persistent_can_be_disabled() {
    $client = new Client(['api_key' => 'secret', 'persistent' => false]);
    $this->assertFalse($client->is_persistent());
  }

  public function test_persistent_is_not_sent_as_a_search_parameter() {
    $client = new Client(['api_key' => 'secret', 'persistent' => false]);
    $this->assertArrayNotHasKey('persistent', $client->get_params());
  }

  public function test_no_connection_is_opened_before_the_first_request() {
    $this->assertNull($this->handle(new Client('secret')));
  }

  public function test_persistent_client_reuses_the_same_handle() {
    $client = new Client('secret');

    $first = $this->acquire($client);
    $second = $this->acquire($client);

    $this->assertSame($first, $second);
    $this->assertSame($first, $this->handle($client));
  }

  public function test_non_persistent_client_uses_a_fresh_handle_each_time() {
    $client = new Client(['api_key' => 'secret', 'persistent' => false]);

    $first = $this->acquire($client);
    $second = $this->acquire($client);

    $this->assertNotSame($first, $second);
    $this->assertNull($this->handle($client), 'non persistent handles must not be retained');
  }

  public function test_close_releases_the_shared_handle() {
    $client = new Client('secret');
    $this->acquire($client);

    $client->close();

    $this->assertNull($this->handle($client));
  }

  public function test_close_is_idempotent() {
    $client = new Client('secret');
    $this->acquire($client);

    $client->close();
    $client->close();

    $this->assertNull($this->handle($client));
  }

  public function test_client_reconnects_after_close() {
    $client = new Client('secret');
    $first = $this->acquire($client);
    $client->close();

    $second = $this->acquire($client);

    $this->assertNotSame($first, $second);
    $this->assertSame($second, $this->handle($client));
  }

  public function test_inspect_reports_persistent_mode() {
    $this->assertStringContainsString('@persistent=true', (new Client('secret'))->inspect());

    $client = new Client(['api_key' => 'secret', 'persistent' => false]);
    $this->assertStringContainsString('@persistent=false', $client->inspect());
  }
}
