<?php

namespace SerpApi;

class Client {
  const VERSION = '1.0.0';
  const BASE_URL = 'https://serpapi.com';
  const DEFAULT_TIMEOUT = 120;

  /** @var string */
  private $api_key;

  /** @var string */
  private $engine;

  /** @var int */
  private $timeout;

  /**
   * @param string $api_key
   * @param string $engine
   * @param int $timeout  Request timeout in seconds
   * @throws SerpApiException
   */
  public function __construct(string $api_key = '', string $engine = 'google', int $timeout = self::DEFAULT_TIMEOUT) {
    if (empty($engine)) {
      throw new SerpApiException('engine must be present');
    }

    $this->api_key = $api_key;
    $this->engine = $engine;
    $this->timeout = $timeout;
  }

  /**
   * Set the SerpApi API key.
   *
   * @param string $api_key
   * @throws SerpApiException
   */
  public function set_api_key(string $api_key): void {
    if (empty($api_key)) {
      throw new SerpApiException('api_key must have a value');
    }

    $this->api_key = $api_key;
  }

  /**
   * Get the current API key.
   */
  public function get_api_key(): string {
    return $this->api_key;
  }

  /**
   * Get the current engine.
   */
  public function get_engine(): string {
    return $this->engine;
  }

  /**
   * Run a search and return decoded JSON.
   *
   * @param array<string, mixed> $params
   * @throws SerpApiException
   */
  public function search(array $params = []): object {
    return $this->get('/search', 'json', $params);
  }

  /**
   * Run a search and return raw HTML.
   *
   * @param array<string, mixed> $params
   * @throws SerpApiException
   */
  public function html(array $params = []): string {
    return $this->get('/search', 'html', $params);
  }

  /**
   * Get account information using Account API.
   *
   * @throws SerpApiException
   */
  public function account(?string $api_key = null): object {
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
  public function location(array $params = []): array {
    return $this->get('/locations.json', 'json', $params);
  }

  /**
   * Retrieve search result from the Search Archive API.
   *
   * @return object|string
   * @throws SerpApiException
   */
  public function search_archive(string $search_id, string $format = 'json') {
    if (empty($search_id)) {
      throw new SerpApiException('search_id must be present');
    }

    if (!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException('format must be json or html');
    }

    $safe_search_id = rawurlencode($search_id);
    return $this->get("/searches/{$safe_search_id}.{$format}", $format, []);
  }

  /**
   * @param array<string, mixed> $params
   * @return object|array<int|string, mixed>|string
   * @throws SerpApiException
   */
  private function get(string $endpoint, string $format = 'json', array $params = []) {
    if (!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException("Unsupported format '$format'. Expected 'html' or 'json'.");
    }

    $api_key = $params['api_key'] ?? $this->api_key;

    $requires_key = strpos($endpoint, '/locations') !== 0;
    if ($requires_key && empty($api_key)) {
      throw new SerpApiException('api_key must be present');
    }

    $default_query = [
      'engine'  => $this->engine,
      'source'  => 'php',
    ];

    if (!empty($api_key)) {
      $default_query['api_key'] = $api_key;
    }

    $query = array_merge($default_query, $params);
    $query['output'] = $format;

    $url = self::BASE_URL . $endpoint . '?' . http_build_query($query);

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_USERAGENT      => 'serpapi-php/' . self::VERSION,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT        => $this->timeout,
    ]);

    $response = curl_exec($ch);
    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);

    if ($response === false) {
      throw new SerpApiException('cURL error: ' . $curl_error);
    }

    if ($http_code === 200) {
      if ($format === 'html') {
        return $response;
      }

      $decoded = json_decode($response);
      if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new SerpApiException('JSON decode error: ' . json_last_error_msg());
      }

      return $decoded;
    }

    if ($format === 'json') {
      $error = json_decode($response);
      $message = (is_object($error) && isset($error->error))
        ? $error->error
        : 'Unexpected exception: ' . $response;
      throw new SerpApiException($message);
    }

    throw new SerpApiException('Unexpected exception: ' . $response);
  }
}
