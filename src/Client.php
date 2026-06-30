<?php

namespace SerpApi;

use RestClient;

class Client {
  const VERSION = '1.0.0';
  const BASE_URL = 'https://serpapi.com';

  /** @var string */
  private $api_key;

  /** @var string */
  private $engine;

  /** @var RestClient|null */
  private $rest_client;

  /**
   * @param string $api_key
   * @param string $engine
   * @throws SerpApiException
   */
  public function __construct($api_key = '', $engine = 'google') {
    if (empty($engine)) {
      throw new SerpApiException('engine must be present');
    }

    $this->api_key = $api_key;
    $this->engine = $engine;
  }

  /**
   * Set the SerpApi API key.
   *
   * @param string $api_key
   * @return void
   * @throws SerpApiException
   */
  public function set_api_key($api_key) {
    if (empty($api_key)) {
      throw new SerpApiException('api_key must have a value');
    }

    $this->api_key = $api_key;
  }

  /**
   * Get the current API key.
   *
   * @return string
   */
  public function get_api_key() {
    return $this->api_key;
  }

  /**
   * Get the current engine.
   *
   * @return string
   */
  public function get_engine() {
    return $this->engine;
  }

  /**
   * Run a search and return decoded JSON.
   *
   * @param array<string, mixed> $params
   * @return object
   * @throws SerpApiException
   */
  public function search($params = []) {
    return $this->get('/search', 'json', $params);
  }

  /**
   * Run a search and return raw HTML.
   *
   * @param array<string, mixed> $params
   * @return string
   * @throws SerpApiException
   */
  public function html($params = []) {
    return $this->get('/search', 'html', $params);
  }

  /**
   * Get account information using Account API.
   *
   * @param string|null $api_key
   * @return object
   * @throws SerpApiException
   */
  public function account($api_key = null) {
    $params = empty($api_key) ? [] : ['api_key' => $api_key];
    return $this->get('/account', 'json', $params);
  }

  /**
   * Get locations using Location API.
   *
   * @param array<string, mixed> $params
   * @return array<int, object>
   * @throws SerpApiException
   */
  public function location($params = []) {
    return $this->get('/locations.json', 'json', $params);
  }

  /**
   * Retrieve search result from the Search Archive API.
   *
   * @param string $search_id
   * @param string $format
   * @return object|string
   * @throws SerpApiException
   */
  public function search_archive($search_id, $format = 'json') {
    if (empty($search_id)) {
      throw new SerpApiException('search_id must be present');
    }

    if (!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException('format must be json or html');
    }

    $safe_search_id = rawurlencode((string)$search_id);
    return $this->get("/searches/{$safe_search_id}.{$format}", $format, []);
  }

  /**
   * @return RestClient
   */
  private function rest_client() {
    if ($this->rest_client === null) {
      $this->rest_client = new RestClient([
        'base_url'   => self::BASE_URL,
        'user_agent' => 'serpapi-php/' . self::VERSION,
      ]);
    }

    return $this->rest_client;
  }

  /**
   * @param string $endpoint
   * @param string $format
   * @param array<string, mixed> $params
   * @return object|string
   * @throws SerpApiException
   */
  private function get($endpoint, $format = 'json', $params = []) {
    if (!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException("Unsupported format '$format'. Expected 'html' or 'json'.");
    }

    $api_key = $params['api_key'] ?? $this->api_key;
    if (empty($api_key)) {
      throw new SerpApiException('api_key must be present');
    }

    $default_query = [
      'engine'  => $this->engine,
      'source'  => 'php',
      'api_key' => $api_key,
    ];

    $query = array_merge($default_query, $params);
    $query['output'] = $format;

    $result = $this->rest_client()->get($endpoint, $query);

    if ($result->info->http_code === 200) {
      if ($format === 'html') {
        return $result->response;
      }

      return $result->decode_response();
    }

    if ($format === 'json') {
      $error = $result->decode_response();
      $message = (is_object($error) && isset($error->error))
        ? $error->error
        : 'Unexpected exception: ' . $result->response;
      throw new SerpApiException($message);
    }

    throw new SerpApiException('Unexpected exception: ' . $result->response);
  }
}
