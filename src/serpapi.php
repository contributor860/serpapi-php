<?php

class SerpApi {
  public string $_api_key;
  private string $_output = 'json';

  public array $_params = [];

  function __construct($params = []) {
    $this->_params = $params;
  }

  private function get_results($path = null) {
    var_dump($this->_params);
    if(!empty($this->_params['api_key']) && empty($this->_api_key)) {
      $this->_api_key = $this->_params['api_key'];
    }

    if(empty($this->_api_key)) {
      throw new SerpApiException("API_KEY must be present");
    }

    $path_skip_to_check_params = [
      '^\/account$', 
      '^\/searches\/.*\.json$'
    ];
    $skip_check_params = preg_match("/".implode("|", $path_skip_to_check_params)."/", $path);

    if(!$skip_check_params && (!is_array($this->_params) || count($this->_params) == 0)) {
      throw new SerpApiException("parameters must be an array and has a value");
    }

    $api = new RestClient([
      'base_url'      => "https://serpapi.com",
      'user_agent'    => 'serpapi-php/1.0.0',
      'curl_options'  => [
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
      ]
    ]);

    $default_query = [
      'output'  => $this->_output,
      'source'  => 'php',
      'api_key' => $this->_api_key,
    ];

    $query = array_merge($this->_params, $default_query);
    $result = $api->get($path, $query);

    if($result->info->http_code == 200) {
      if($this->_output == 'html') {
       return $result->response;
      }

      return $result->decode_response();
    }

    if($this->_output == 'json') {
      $error = $result->decode_response();
      throw new SerpApiException($error->error);
    }

    throw new SerpApiException("Unexpected exception: $result->response");
  }

  /***
   * get_json
   * @return [Hash] search result "json like"
   */
  public function get_json() {
    $this->_output = 'json';

    return $this->get_results('/search');
  }

  /***
   * get_html
   * @return [String] raw html search result
   */
  public function get_html() {
    $this->_output = 'html';

    return $this->get_results('/search');
  }

 /***
  * Get account information using Account API
  */
  public function get_account() {
    $this->_output = 'json';

    $this->_params = array_filter($this->_params, function($k) {
      return $k == 'api_key';
    }, ARRAY_FILTER_USE_KEY);

    return $this->get_results('/account');
  }

  /***
   * Get location using Location API
   */
  public function get_location() {
    $this->_output = 'json';

    return $this->get_results("/locations.json");
  }

  /***
   * Retrieve search result from the Search Archive API
   */
  public function get_search_archive($search_id = null) {
    if($search_id == null) {
      throw new SerpApiException("search_id must be present");
    }

    $this->_output = 'json';
    $this->_params = array_filter($this->_params, function($k) {
      return $k == 'api_key';
    }, ARRAY_FILTER_USE_KEY);

    return $this->get_results("/searches/$search_id.json");
  }
}

class SerpApiException extends Exception {}

class SerpApiSearch extends SerpApi {
  function __construct($api_key = null, $engine = null){
    if($api_key == NULL) {
      throw new SerpApiException("serp_api_key must have a value");
    }

    if($engine) {
      $this->_params['engine'] = $engine;
    } else {
      throw new SerpApiException("engine must be defined");
    }

    $this->_api_key = $api_key;
    parent::__construct();
  }

  function set_serp_api_key($api_key) {
    if($api_key == NULL) {
      throw new SerpApiException("serp_api_key must have a value");
    }
    
    $this->_api_key = $api_key;
  }

  function get_json($params = []) {
    $this->_params = $params;
    return parent::get_json();
  }

  function get_html($params = []) {
    $this->_params = $params;
    return parent::get_html();
  }

  function search($output = null, $params = []) {
    $this->_params = $params;

    if($output == 'json') {
      return parent::get_json();
    } elseif($output == 'html') {
      return parent::get_html();
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

    $this->_params = [
      'q' => $location, 
      'limit' => $limit
    ];

    return parent::get_location();
  }
}

class GoogleSearch extends SerpApiSearch {
  public function __construct($api_key) {
    parent::__construct($api_key, 'google');
  }
}
