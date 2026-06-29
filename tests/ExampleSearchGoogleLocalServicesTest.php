<?php

class ExampleSearchGoogleLocalServicesTest extends SerpApiTestCase {
  
  private $search_params;
  protected function setUp(): void {
    parent::setUp();
    $this->search_params = [
      'engine' => 'google_local_services',
      'q' => 'electrician',
      'data_cid' => '6745062158417646970'
    ];
 }

  function test_if_result_exist() {
    $search = $this->serpApiClient();
    $response = $search->search($this->search_params);
    $this->assertResponseHasProperty($response, 'local_ads', "Error on `{google_local_services}` engine not has `{local_ads}`");
  }
}
