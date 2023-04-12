<?php

class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_local_services',
      'q' => 'Electrician',
      'place_id' => 'ChIJOwg_06VPwokRYv534QaPC8g'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->API_KEY = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->API_KEY = getenv('API_KEY');
    } else {
      $this->API_KEY = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApiSearch($this->API_KEY);
    $response = $search->get_json($this->QUERY);
    $this->assertObjectHasAttribute('local_ads', $response, "Error on `{google_local_services}` engine not has `{local_ads}`");
  }
}
