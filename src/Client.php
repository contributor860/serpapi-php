<?php

namespace SerpApi;

class Client {
  const VERSION = '1.0.0';
  const BASE_URL = 'https://serpapi.com';
  const DEFAULT_TIMEOUT = 120;

  /** Client identifier reported to SerpApi for usage statistics. */
  const SOURCE = 'serpapi-php:' . self::VERSION;

  /** @var string */
  private $api_key;

  /** @var string */
  private $engine;

  /** @var int */
  private $timeout;

  /** @var array<string, mixed> Search parameters applied to every request */
  private $params = [];

  /**
   * Client-only configuration keys, never forwarded to the API as search parameters.
   *
   * @var array<int, string>
   */
  private static $option_keys = ['api_key', 'engine', 'timeout'];

  /**
   * Accepts either positional arguments or, like the Ruby client, a single
   * associative array holding both configuration and default search parameters:
   *
   *   new Client(['api_key' => '...', 'engine' => 'google', 'hl' => 'en'])
   *
   * Any key that is not `api_key`, `engine` or `timeout` becomes a default
   * search parameter merged into every request, and can still be overridden
   * per call.
   *
   * @param string|array<string, mixed> $api_key  API key, or a full configuration array
   * @param string $engine
   * @param int $timeout  Request timeout in seconds
   * @param array<string, mixed> $params  Default search parameters
   * @throws SerpApiException
   */
  public function __construct($api_key = '', string $engine = 'google', int $timeout = self::DEFAULT_TIMEOUT, array $params = []) {
    if (is_array($api_key)) {
      $config = $api_key;
      $api_key = (string) $this->take($config, 'api_key', '');
      $engine = (string) $this->take($config, 'engine', $engine);
      $timeout = (int) $this->take($config, 'timeout', $timeout);
      $params = array_merge($config, $params);
    }

    if (empty($engine)) {
      throw new SerpApiException('engine must be present');
    }

    $this->api_key = (string) $api_key;
    $this->engine = $engine;
    $this->timeout = $timeout;
    $this->params = $this->without_option_keys($params);
  }

  /**
   * Pull a value out of a configuration array, removing it in the process.
   *
   * @param array<string, mixed> $config
   * @param mixed $default
   * @return mixed
   */
  private function take(array &$config, string $key, $default) {
    if (!array_key_exists($key, $config) || $config[$key] === null) {
      unset($config[$key]);
      return $default;
    }

    $value = $config[$key];
    unset($config[$key]);

    return $value;
  }

  /**
   * @param array<string, mixed> $params
   * @return array<string, mixed>
   */
  private function without_option_keys(array $params): array {
    foreach (self::$option_keys as $key) {
      unset($params[$key]);
    }

    return $params;
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
   * Get the request timeout in seconds.
   */
  public function get_timeout(): int {
    return $this->timeout;
  }

  /**
   * Get the default search parameters applied to every request,
   * including `engine` and `api_key`.
   *
   * @return array<string, mixed>
   */
  public function get_params(): array {
    $params = ['engine' => $this->engine];

    if (!empty($this->api_key)) {
      $params['api_key'] = $this->api_key;
    }

    return array_merge($params, $this->params);
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

    $query = $this->query($params, $api_key, $format);

    $url = self::BASE_URL . $endpoint . '?' . http_build_query($query);

    $request_result = $this->request($url);
    $response = $request_result['response'];
    $http_code = $request_result['http_code'];
    $curl_error = $request_result['curl_error'];

    if ($response === false) {
      throw new SerpApiException('cURL error: ' . $curl_error);
    }

    if ($format === 'html') {
      if ($http_code === 200) {
        return $response;
      }

      $this->raise_http_error($http_code, $endpoint, $query, null, null, 'html');
    }

    $decoded = json_decode($response);
    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
      $this->raise_parser_error($http_code, $endpoint, $query);
    }

    $serpapi_error = (is_object($decoded) && isset($decoded->error)) ? $decoded->error : null;
    $search_id = (is_object($decoded) && isset($decoded->search_metadata->id))
      ? (string) $decoded->search_metadata->id
      : null;

    if ($http_code === 200) {
      if ($serpapi_error !== null) {
        $this->raise_http_error($http_code, $endpoint, $query, $serpapi_error, $search_id, 'json');
      }

      return $decoded;
    }

    $this->raise_http_error($http_code, $endpoint, $query, $serpapi_error, $search_id, 'json');
  }

  /**
   * Build the query string parameters for a request.
   *
   * @param array<string, mixed> $params
   * @return array<string, mixed>
   */
  private function query(array $params, string $api_key, string $format): array {
    $default_query = [
      'engine'  => $this->engine,
      'source'  => self::SOURCE,
    ];

    if (!empty($api_key)) {
      $default_query['api_key'] = $api_key;
    }

    $query = array_merge($default_query, $this->params, $params);
    $query['output'] = $format;

    return array_filter($query, static function ($value) {
      return $value !== null;
    });
  }

  /**
   * @return array{response: string|false, http_code: int, curl_error: string}
   * @throws SerpApiException
   */
  private function request(string $url): array {
    $ch = curl_init();
    if ($ch === false) {
      throw new SerpApiException('Failed to initialize cURL handle');
    }

    try {
      $is_configured = curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT      => 'serpapi-php/' . self::VERSION,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => $this->timeout,
      ]);

      if ($is_configured === false) {
        throw new SerpApiException('Failed to configure cURL options: ' . curl_error($ch));
      }

      $response = curl_exec($ch);
      $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $curl_error = curl_error($ch);

      return [
        'response' => $response,
        'http_code' => $http_code,
        'curl_error' => $curl_error,
      ];
    } finally {
      if (PHP_VERSION_ID < 80500) {
        curl_close($ch);
      }

      $ch = null;
    }
  }

  /**
   * @param array<string, mixed> $search_params
   * @return never
   * @throws SerpApiException
   */
  private function raise_http_error(
    int $response_status,
    string $endpoint,
    array $search_params,
    ?string $serpapi_error = null,
    ?string $search_id = null,
    string $decoder = 'json'
  ): void {
    $message = "HTTP request failed with status: {$response_status}";
    if ($serpapi_error !== null) {
      $message .= " error: {$serpapi_error}";
    }
    $message .= ' from url: ' . self::BASE_URL . $endpoint;
    $sanitized_search_params = $this->sanitize_search_params($search_params);

    throw new SerpApiException(
      $message,
      $serpapi_error,
      $sanitized_search_params,
      $response_status,
      $search_id,
      $decoder
    );
  }

  /**
   * @param array<string, mixed> $search_params
   * @return never
   * @throws SerpApiException
   */
  private function raise_parser_error(
    int $response_status,
    string $endpoint,
    array $search_params
  ): void {
    $sanitized_search_params = $this->sanitize_search_params($search_params);

    throw new SerpApiException(
      'JSON parse error: ' . json_last_error_msg() . ' on get url: ' . self::BASE_URL . $endpoint,
      null,
      $sanitized_search_params,
      $response_status,
      null,
      'json'
    );
  }

  /**
   * @param array<string, mixed> $search_params
   * @param array<int, string> $keys_to_remove
   * @return array<string, mixed>
   */
  private function sanitize_search_params(array $search_params, array $keys_to_remove = ['api_key']): array {
    foreach ($keys_to_remove as $key) {
      unset($search_params[$key]);
    }

    return $search_params;
  }
}
