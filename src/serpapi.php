<?php

class SerpApi {
  /** @var string */
  public $api_key;

  /** @var string */
  public $engine;

  /**
   * @param string $api_key
   * @param string $engine
   * @throws SerpApiException
   */
  function __construct($api_key = '', $engine = 'google') {
    if(empty($engine)) {
      throw new SerpApiException("engine must be present");
    }

    $this->api_key = $api_key;
    $this->engine = $engine;
  }

  /**
   * Run a search and return decoded JSON.
   *
   * @param array<string, mixed> $params
   * @return object
   */
  public function search($params = []) {
    return $this->get('/search', 'json', $params);
  }

  /**
   * Run a search and return raw HTML.
   *
   * @param array<string, mixed> $params
   * @return string
   */
  public function html($params = []) {
    return $this->get('/search', 'html', $params);
  }

  /**
   * Get account information using Account API.
   *
   * @param string|null $api_key
   * @return object
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
   */
  public function location($params = []) {
    return $this->get("/locations.json", 'json', $params);
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
    if(empty($search_id)) {
      throw new SerpApiException("search_id must be present");
    }

    if(!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException("format must be json or html");
    }

    $safe_search_id = rawurlencode((string)$search_id);
    return $this->get("/searches/{$safe_search_id}.{$format}", $format, []);
  }

  /**
   * @param string|null $endpoint
   * @param string $format
   * @param array<string, mixed> $params
   * @return object|string
   * @throws SerpApiException
   */
  private function get($endpoint = null, $format = 'json', $params = []) {
    if(!in_array($format, ['json', 'html'], true)) {
      throw new SerpApiException("Unsupported format '$format'. Expected 'html' or 'json'.");
    }

    $api_key = $params['api_key'] ?? $this->api_key;
    if(empty($api_key)) {
      throw new SerpApiException("API_KEY must be present");
    }

    $api = new RestClient([
      'base_url'      => "https://serpapi.com",
      'user_agent'    => 'serpapi-php/1.0.0',
    ]);

    $default_query = [
      'engine'  => $this->engine,
      'output'  => $format,
      'source'  => 'php',
      'api_key' => $api_key,
    ];

    $query = array_merge($default_query, $params);
    $query['output'] = $format;

    $result = $api->get($endpoint, $query);

    if($result->info->http_code == 200) {
      if($format == 'html') {
       return $result->response;
      }

      return $result->decode_response();
    }

    if($format == 'json') {
      $error = $result->decode_response();
      $message = (is_object($error) && isset($error->error)) ? $error->error : ('Unexpected exception: ' . $result->response);
      throw new SerpApiException($message);
    }

    throw new SerpApiException('Unexpected exception: ' . $result->response);
  }
}

class SerpApiException extends Exception {}

class SerpApiSearch extends SerpApi {
  function __construct($api_key = '', $engine = 'google'){
    parent::__construct($api_key, $engine);
  }

  /**
   * @param string $api_key
   * @return void
   * @throws SerpApiException
   */
  function set_serp_api_key($api_key) {
    if(empty($api_key)) {
      throw new SerpApiException("serp_api_key must have a value");
    }

    $this->api_key = $api_key;
  }

  /**
   * @param array<string, mixed> $params
   * @return object
   */
  function get_json($params = []) {
    return parent::search($params);
  }

  /**
   * @param array<string, mixed> $params
   * @return string
   */
  function get_html($params = []) {
    return parent::html($params);
  }

  /**
   * @param string|array<string, mixed>|null $output
   * @param array<string, mixed> $params
   * @return object|string
   * @throws SerpApiException
   */
  function search($output = null, $params = []) {
    if($output === null || is_array($output)) {
      return parent::search(is_array($output) ? $output : $params);
    }

    if($output == 'json') {
      return parent::search($params);
    } elseif($output == 'html') {
      return parent::html($params);
    } else {
      throw new SerpApiException("output must be json or html");
    }
  }

  /**
   * @param string|null $location
   * @param int $limit
   * @return array<int, object>
   * @throws SerpApiException
   */
  function get_location($location = null, $limit = 3) {
    if(empty($location)) {
      throw new SerpApiException("location must be present");
    }

    if(empty($limit)) {
      throw new SerpApiException("limit must be present");
    }

    $params = [
      'q' => $location, 
      'limit' => $limit
    ];

    return parent::location($params);
  }

  /**
   * @return object
   */
  function get_account() {
    return parent::account($this->api_key);
  }
}

class GoogleSearch extends SerpApiSearch {
  /**
   * @param string $api_key
   */
  public function __construct($api_key) {
    parent::__construct($api_key, 'google');
  }
}
