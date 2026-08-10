<?php

namespace SerpApi\Tests;

class ExampleSearchGoogleAiOverviewTest extends SerpApiTestCase {
  public function test_result_exists() {
    // The AI overview engine needs a page_token that is only valid for a short
    // while, so it is taken from a live Google search rather than hardcoded.
    $google = $this->serpApiClient('google');
    $search = $google->search(['q' => 'what is coffee']);

    $page_token = $search->ai_overview->page_token ?? null;
    if ($page_token === null) {
      $this->markTestSkipped('Google returned no AI overview page_token for this query');
    }

    $client = $this->serpApiClient('google_ai_overview');
    $response = $client->search(['page_token' => $page_token]);
    $this->assertResponseHasProperty($response, 'ai_overview', 'Error on `google_ai_overview` engine: no `ai_overview`');
  }
}
