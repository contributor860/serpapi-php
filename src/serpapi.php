<?php

class SerpApi {
  public $_api_key;
  private $_output = 'json';

  private $_params;

  function __construct($params = null) {
    if(!is_array($params) || count($params) == 0) {
      throw new SerpApiException("parameters must be an array and has a value");
    }

    if(!empty($params['api_key'])) {
      $this->_api_key = $params['api_key'];
    }

    $this->_params = $params;
  }

  private function get_results($path = null) {
    if(empty($this->_api_key)) {
      throw new SerpApiException("API_KEY must be present");
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

    $query = array_merge($default_query, $this->_params);
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

    return $this->get_results('/account');
  }

  /***
   * Get location using Location API
   */
  public function get_location($q = 'Austin', $limit = 3) {
    $this->_output = 'json';

    $this->_params = [
      'q' => $q,
      'limit' => $limit,
      'api_key' => $this->_api_key,
    ];
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
    $this->_params = [
      'api_key' => $this->_api_key,
    ];

    return $this->get_results("/searches/$search_id.json");
  }
}

class SerpApiException extends Exception {}
