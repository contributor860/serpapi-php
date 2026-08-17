<?php

namespace SerpApi\Tests;

use SerpApi\Client;
use SerpApi\SerpApiException;

class ClientTest extends SerpApiTestCase
{
  protected function requiresApiKey(): bool
  {
    return false;
  }

  public function testThrowsWhenApiKeyMissing()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('api_key must be present');
    $client = new Client();
    $client->search(['q' => 'Coffee']);
  }

  public function testThrowsWhenEngineEmpty()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('engine must be present');
    new Client('test_key', '');
  }

  public function testThrowsWhenApiKeyInvalid()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessageMatches('/Invalid API key/i');
    $client = new Client('not_valid_key');
    $client->search(['q' => 'Coffee']);
  }

  public function testSearchArchiveThrowsWhenIdEmpty()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('search_id must be present');
    $client = new Client('test_key');
    $client->searchArchive('');
  }

  public function testSearchArchiveThrowsWhenFormatInvalid()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('format must be json, html, or md');
    $client = new Client('test_key');
    $client->searchArchive('abc', 'xml');
  }

  public function testMdReturnsRawMarkdown()
  {
    $markdown = "---\nengine: google\n---\n\n## Organic Results\n";
    $client = new StubClient([
      'response' => $markdown,
      'http_code' => 200,
      'curl_error' => '',
    ]);

    $response = $client->md(['q' => 'Coffee']);
    $request = $client->getLastRequest();
    $query = [];
    parse_str($request['query'], $query);

    $this->assertSame($markdown, $response);
    $this->assertSame('/search.md', $request['path']);
    $this->assertSame('md', $query['output']);
    $this->assertSame('Coffee', $query['q']);
  }

  public function testSearchArchiveReturnsRawMarkdown()
  {
    $markdown = "---\nengine: google\n---\n";
    $client = new StubClient([
      'response' => $markdown,
      'http_code' => 200,
      'curl_error' => '',
    ]);

    $response = $client->searchArchive('search/id', 'md');
    $request = $client->getLastRequest();

    $this->assertSame($markdown, $response);
    $this->assertSame('/searches/search%2Fid.md', $request['path']);
  }

  public function testMdHttpErrorUsesMdDecoder()
  {
    $client = new StubClient([
      'response' => 'Invalid search',
      'http_code' => 400,
      'curl_error' => '',
    ]);

    try {
      $client->md(['q' => 'Coffee']);
      $this->fail('Expected SerpApiException was not thrown');
    } catch (SerpApiException $exception) {
      $this->assertSame(400, $exception->getResponseStatus());
      $this->assertSame('md', $exception->getDecoder());
      $this->assertSame([
        'engine' => 'google',
        'source' => 'php',
        'q' => 'Coffee',
        'output' => 'md',
      ], $exception->getSearchParams());
    }
  }

  public function testSetApiKeyThrowsWhenEmpty()
  {
    $this->expectException(SerpApiException::class);
    $this->expectExceptionMessage('api_key must have a value');
    $client = new Client('test_key');
    $client->setApiKey('');
  }

  public function testSetApiKeyUpdatesValue()
  {
    $client = new Client('initial_key');
    $client->setApiKey('updated_key');
    $this->assertEquals('updated_key', $client->getApiKey());
  }

  public function testGetEngineReturnsEngine()
  {
    $client = new Client('test_key', 'bing');
    $this->assertEquals('bing', $client->getEngine());
  }
}
