<?php

class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google',
      'engine' => 'google',
      'tbm' => 'isch',
      'q' => 'coffee'
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
    $this->assertObjectHasAttribute('images_results', $response, "Error on `{google}` engine not has `{images_results}`");
  }
}
