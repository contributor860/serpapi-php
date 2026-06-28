<?php

class SerpApi {
  public $api_key;
  public $engine;

  function __construct($api_key = '', $engine = 'google') {
    if(empty($engine)) {
      throw new SerpApiException("engine must be present");
    }

    $this->api_key = $api_key;
    $this->engine = $engine;
  }

  /***
   * search
   * @return [Hash] search result "json like"
   */
  public function search($params = []) {
    return $this->get('/search', 'json', $params);
  }

  /***
   * html
   * @return [String] raw html search result
   */
  public function html($params = []) {
    return $this->get('/search', 'html', $params);
  }

 /***
  * Get account information using Account API
  */
  public function account($api_key = null) {
    $params = empty($api_key) ? [] : ['api_key' => $api_key];
    return $this->get('/account', 'json', $params);
  }

  /***
   * Get location using Location API
   */
  public function location($params = []) {
    return $this->get("/locations.json", 'json', $params);
  }

  /***
   * Retrieve search result from the Search Archive API
   */
  public function search_archive($search_id = null, $format = 'json') {
    if($search_id == null) {
      throw new SerpApiException("search_id must be present");
    }

    if(!in_array($format, ['json', 'html'])) {
      throw new SerpApiException("format must be json or html");
    }

    $safe_search_id = rawurlencode((string)$search_id);
    return $this->get("/searches/{$safe_search_id}.{$format}", $format, []);
  }

  private function get($endpoint = null, $format = 'json', $params = []) {
    if(!in_array($format, ['json', 'html'])) {
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
      throw new SerpApiException($error->error);
    }

    throw new SerpApiException('Unexpected exception: ' . $result->response);
  }
}

class SerpApiException extends Exception {}

class SerpApiSearch extends SerpApi {
  function __construct($api_key = '', $engine = 'google'){
    parent::__construct($api_key, $engine);
  }

  function set_serp_api_key($api_key) {
    if(empty($api_key)) {
      throw new SerpApiException("serp_api_key must have a value");
    }

    $this->api_key = $api_key;
  }

  function get_json($params = []) {
    return parent::search($params);
  }

  function get_html($params = []) {
    return parent::html($params);
  }

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

  function get_account() {
    return parent::account($this->api_key);
  }
}

class GoogleSearch extends SerpApiSearch {
  public function __construct($api_key) {
    parent::__construct($api_key, 'google');
  }
}
