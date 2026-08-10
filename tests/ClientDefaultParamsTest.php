<?php

namespace SerpApi\Tests;

use SerpApi\Client;
use SerpApi\SerpApiException;

/**
 * Default search parameters supplied to the constructor, and the
 * array-style constructor that mirrors the Ruby client.
 */
class ClientDefaultParamsTest extends ClientQueryTest {
  public function test_default_params_are_merged_into_every_request() {
    $client = new Client('secret', 'google', 120, ['hl' => 'en', 'gl' => 'us']);
    $query = $this->query($client, ['q' => 'coffee']);

    $this->assertEquals('en', $query['hl']);
    $this->assertEquals('us', $query['gl']);
    $this->assertEquals('coffee', $query['q']);
  }

  public function test_per_search_params_override_defaults() {
    $client = new Client('secret', 'google', 120, ['hl' => 'en']);
    $query = $this->query($client, ['hl' => 'fr']);

    $this->assertEquals('fr', $query['hl']);
  }

  public function test_null_params_are_dropped_from_the_query() {
    $client = new Client('secret', 'google', 120, ['hl' => 'en']);
    $query = $this->query($client, ['hl' => null]);

    $this->assertArrayNotHasKey('hl', $query);
  }

  public function test_array_constructor_sets_configuration() {
    $client = new Client([
      'api_key' => 'secret',
      'engine'  => 'bing',
      'timeout' => 30,
    ]);

    $this->assertEquals('secret', $client->get_api_key());
    $this->assertEquals('bing', $client->get_engine());
    $this->assertEquals(30, $client->get_timeout());
  }

  public function test_array_constructor_treats_unknown_keys_as_default_params() {
    $client = new Client([
      'api_key'  => 'secret',
      'engine'   => 'google',
      'location' => 'Austin, TX',
      'no_cache' => true,
    ]);

    $query = $this->query($client, ['q' => 'coffee']);
    $this->assertEquals('Austin, TX', $query['location']);
    $this->assertTrue($query['no_cache']);
  }

  public function test_array_constructor_defaults_engine_to_google() {
    $client = new Client(['api_key' => 'secret']);
    $this->assertEquals('google', $client->get_engine());
  }

  public function test_array_constructor_rejects_empty_engine() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('engine must be present');
    new Client(['api_key' => 'secret', 'engine' => '']);
  }

  public function test_positional_constructor_remains_supported() {
    $client = new Client('secret', 'bing', 30);

    $this->assertEquals('secret', $client->get_api_key());
    $this->assertEquals('bing', $client->get_engine());
    $this->assertEquals(30, $client->get_timeout());
  }

  public function test_get_params_exposes_engine_api_key_and_defaults() {
    $client = new Client(['api_key' => 'secret', 'engine' => 'bing', 'hl' => 'en']);
    $params = $client->get_params();

    $this->assertEquals('bing', $params['engine']);
    $this->assertEquals('secret', $params['api_key']);
    $this->assertEquals('en', $params['hl']);
  }

  public function test_get_params_omits_client_only_options() {
    $client = new Client(['api_key' => 'secret', 'timeout' => 30]);

    $this->assertArrayNotHasKey('timeout', $client->get_params());
    $this->assertArrayNotHasKey('timeout', $this->query($client));
  }
}
