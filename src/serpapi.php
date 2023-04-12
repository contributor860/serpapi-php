<?php

  /***
   * In case converted from old library.
   */
class GoogleSearch extends SerpApiSearch {
  public function __construct($api_key) {
    parent::__construct($api_key);
  }
}

class SerpApiSearch {
  private $_api_key;
  private $_output = 'json';

  function __construct($api_key = null) {
    $this->set_api_key($api_key);
  }

  /***
   * Validate API_KEY.
   */
  function set_api_key($api_key = null) {
    if($api_key == null) {
      throw new SerpApiSearchException("API_KEY must be present");
    }

    $this->_api_key = $api_key;
  }

  function query($path = null, $query = null) {

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

    $query = array_merge($default_query, $query);
    $result = $api->get($path, $query);

    if($result->info->http_code == 200) {
      if($this->_output == 'html') {
       return $result->response;
      }

      return $result->decode_response();
    }

    if($this->_output == 'json') {
      $error = $result->decode_response();
      throw new SerpApiSearchException($error->error);
    }

    throw new SerpApiSearchException("Unexpected exception: $result->response");
  }
  /**
   * Run a search
   */
  function search($parameters = []) {
    if(!is_array($parameters) || count($parameters) == 0) {
      throw new SerpApiSearchException("parameters must be an array and has a value");
    }

    return $this->query('/search', $parameters);
  }

  /***
   * get_json
   * @return [Hash] search result "json like"
   */
  function get_json($parameters = []) {
    $this->_output = 'json';

    return $this->search($parameters);
  }

  /***
   * get_html
   * @return [String] raw html search result
   */
  function get_html($parameters = []) {
    $this->_output = 'html';

    return $this->search($parameters);
  }

 /***
  * Get account information using Account API
  */
  function get_account() {
    $this->_output = 'json';

    return $this->query('/account', []);
  }

  /***
   * Get location using Location API
   */
  function get_location($q = 'Austin', $limit = 3) {
    $this->_output = 'json';

    $query = [
      'q' => $q,
      'limit' => $limit
    ];
    return $this->query("/locations.json", $query);
  }

  /***
   * Retrieve search result from the Search Archive API
   */
  function get_search_archive($search_id = null) {
    if($search_id == null) {
      throw new SerpApiSearchException("search_id must be present");
    }

    $this->_output = 'json';

    return $this->query("/searches/$search_id.json", []);
  }
}

class SerpApiSearchException extends Exception {}
