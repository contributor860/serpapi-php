<?php

class serpapiEnginesTest extends \PHPUnit\Framework\TestCase {

  private $_search_params = [
    [
      'params'    => ['engine' => 'apple_app_store', 'term' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'baidu', 'q' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'bing', 'q' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'duckduckgo', 'q' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'ebay', '_nkw' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'google_autocomplete', 'q' => 'coffee'],
      'check_has' => 'suggestions',
    ],
    [
      'params'    => ['engine' => 'google_events', 'q' => 'coffee'],
      'check_has' => 'events_results',
    ],
    [
      'params'    => ['engine' => 'google_jobs', 'q' => 'coffee'],
      'check_has' => 'jobs_results',
    ],
    [
      'params'    => ['engine' => 'google_local_services', 'q' => 'electrician', 'data_cid' => '6745062158417646970'],
      'check_has' => 'local_ads',
    ],
    [
      'params'    => ['engine' => 'google_maps', 'q' => 'pizza', 'll' => '@40.7455096,-74.0083012,15.1z', 'type' => 'search'],
      'check_has' => 'local_results',
    ],
    [
      'params'    => ['engine' => 'google_play', 'q' => 'kite', 'store' => 'apps'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'google_product', 'q' => 'coffee', 'product_id' => '4172129135583325756'],
      'check_has' => 'product_results',
    ],
    [
      'params'    => ['engine' => 'google_reverse_image', 'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'],
      'check_has' => 'image_sizes',
    ],
    [
      'params'    => ['engine' => 'google_scholar', 'q' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'home_depot', 'q' => 'table'],
      'check_has' => 'products',
    ],
    [
      'params'    => ['engine' => 'google', 'tbm' => 'isch', 'q' => 'coffee'],
      'check_has' => 'images_results',
    ],
    [
      'params'    => ['engine' => 'naver', 'query' => 'coffee'],
      'check_has' => 'ads_results',
    ],
    [
      'params'    => ['engine' => 'walmart', 'query' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'yahoo', 'p' => 'coffee'],
      'check_has' => 'organic_results',
    ],
    [
      'params'    => ['engine' => 'youtube', 'search_query' => 'coffee'],
      'check_has' => 'video_results',
    ],
    
  ];

  private $api_key;

  protected function setUp(): void {
    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    foreach($this->_search_params as $search_param)
    $search = new SerpApi($search_param['params']);
    $search->_api_key = $this->api_key;
    $response = $search->get_json();
    $this->assertObjectHasAttribute($search_param['check_has'], $response, "Error on `{$search_param['params']['engine']}` engine not has `{$search_param['check_has']}`");
  }
}
