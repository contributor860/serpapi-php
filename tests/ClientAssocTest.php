<?php

namespace SerpApi\Tests;

use SerpApi\Client;

/**
 * Decoding JSON responses to associative arrays instead of stdClass,
 * the PHP counterpart of the Ruby client's symbolize_names option.
 */
class ClientAssocTest extends ClientQueryTest {
  public function test_objects_are_returned_by_default() {
    $this->assertFalse((new Client('secret'))->is_assoc());
  }

  public function test_assoc_can_be_enabled() {
    $client = new Client(['api_key' => 'secret', 'assoc' => true]);
    $this->assertTrue($client->is_assoc());
  }

  public function test_assoc_is_not_sent_as_a_search_parameter() {
    $client = new Client(['api_key' => 'secret', 'assoc' => true]);

    $this->assertArrayNotHasKey('assoc', $client->get_params());
    $this->assertArrayNotHasKey('assoc', $this->query($client));
  }

  public function test_per_call_assoc_is_not_sent_as_a_search_parameter() {
    $query = $this->query(new Client('secret'), ['q' => 'coffee', 'assoc' => true]);

    $this->assertArrayNotHasKey('assoc', $query);
    $this->assertEquals('coffee', $query['q']);
  }

  public function test_client_only_keys_never_reach_the_query() {
    $query = $this->query(new Client('secret'), [
      'q'          => 'coffee',
      'timeout'    => 5,
      'persistent' => false,
      'assoc'      => true,
    ]);

    $this->assertEquals(['engine', 'source', 'api_key', 'q', 'output'], array_keys($query));
  }

  public function test_dig_reads_both_object_and_array_shapes() {
    $client = new Client('secret');

    foreach ([json_decode('{"error":"boom"}'), json_decode('{"error":"boom"}', true)] as $data) {
      $this->assertEquals('boom', $this->dig($client, $data, 'error'));
      $this->assertNull($this->dig($client, $data, 'missing'));
    }
  }

  public function test_dig_tolerates_scalar_and_null_responses() {
    $client = new Client('secret');

    $this->assertNull($this->dig($client, null, 'error'));
    $this->assertNull($this->dig($client, 'a string', 'error'));
  }

  /**
   * @param mixed $data
   * @return mixed
   */
  private function dig(Client $client, $data, string $key) {
    $method = new \ReflectionMethod(Client::class, 'dig');

    if (PHP_VERSION_ID < 80100) {
      $method->setAccessible(true);
    }

    return $method->invoke($client, $data, $key);
  }
}
