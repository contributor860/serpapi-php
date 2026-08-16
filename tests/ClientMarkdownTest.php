<?php

namespace SerpApi\Tests;

use SerpApi\Client;
use SerpApi\SerpApiException;

/**
 * Markdown output. The request building is covered without an API key;
 * the live round trip is covered in GoogleSearchTest.
 */
class ClientMarkdownTest extends ClientQueryTest {
  public function test_md_is_an_accepted_format() {
    $this->assertContains('md', Client::FORMATS);
  }

  public function test_md_is_returned_raw_like_html() {
    $this->assertContains('md', Client::RAW_FORMATS);
    $this->assertNotContains('json', Client::RAW_FORMATS);
  }

  public function test_markdown_requests_output_md() {
    $query = $this->query(new Client('secret'), ['q' => 'coffee'], 'secret', 'md');
    $this->assertEquals('md', $query['output']);
  }

  public function test_unsupported_format_is_rejected() {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage("Unsupported format 'xml'. Expected json, html, md.");

    $method = new \ReflectionMethod(Client::class, 'get');
    if (PHP_VERSION_ID < 80100) {
      $method->setAccessible(true);
    }

    $method->invoke(new Client('secret'), '/search', 'xml', []);
  }

  public function test_search_archive_accepts_md() {
    // Reaches the network only after validation, so an empty id is enough
    // to prove `md` passes the format check while `xml` does not.
    $client = new Client('secret');

    try {
      $client->search_archive('', 'md');
      $this->fail('expected an exception');
    } catch (SerpApiException $e) {
      $this->assertEquals('search_id must be present', $e->getMessage());
    }
  }

}
