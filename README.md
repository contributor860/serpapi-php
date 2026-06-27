# SerpApi PHP library

[![serpapi-php](https://github.com/serpapi/serpapi-php/actions/workflows/serpapi-php.yml/badge.svg)](https://github.com/serpapi/serpapi-php/actions/workflows/serpapi-php.yml)

Integrate search data into your PHP application. This library is the official wrapper for SerpApi (https://serpapi.com).

SerpApi supports Google, Google Maps, Google Shopping, Baidu, Yandex, Yahoo, eBay, App Stores, and more.

This PHP API is meant to scrape and parse Google, Bing, Apple, Baidu, Naver and more results using [SerpApi](https://serpapi.com).

This is the new library provided by SerpApi as a replacement for our old library that can be found [here](https://github.com/serpapi/google-search-results-php) feel free to contact us in case you needed any help: contact@serpapi.com


[The full documentation is available here.](https://serpapi.com/search-api)

The following services are provided:
 * [Search API](https://serpapi.com/search-api)
 * [Location API](https://serpapi.com/locations-api)
 * [Search Archive API](https://serpapi.com/search-archive-api)
 * [Account API](https://serpapi.com/account-api)

SerpApi provides a [script builder](https://serpapi.com/demo) to get you started quickly.

## Installation

PHP 7.2+ must be already installed and [composer](https://getcomposer.org/) dependency management tool.

Tested PHP versions:
* 7.2
* 7.3
* 7.4
* 8.0
* 8.1
* 8.2
* 8.3
* 8.4
* 8.5

Package available from packagist.

## Quick start

if you're using composer, you can add this package ([link to packagist](https://packagist.org/packages/serpapi/serpapi-php)).
```bash
$ composer require serpapi/serpapi-php
```

Then you need to load the dependency in your script.
```php
<?php
require __DIR__ . '/vendor/autoload.php';
 ?>
```

if not, you must clone this repository and link the class.
```php
require 'path/to/serpapi-php';

```

Get "your secret key" from https://serpapi.com/dashboard

Then you can start coding something like:
```php
require 'vendor/autoload.php';
$query = [
  "engine" => "naver",
  "query" => "paris",
];

$search = new SerpApiSearch('YOUR API KEY HERE');
$result = $search->get_json($query);
print_r($result);
```

This example runs a search about "paris" on Naver using your secret api key.

The SerpApi service (backend)
 - searches on Naver using the query: paris
 - parses the messy HTML responses
 - returns a standardized JSON response
The PHP class SerpApiSearch
 - formats the request to SerpApi server
 - executes GET http request
 - parses JSON into PHP objects using the ext-json extension
Et voila..

### How to set SERP API key
The SerpApi api_key can be set globally using a singleton pattern.

```php
$client = new SerpApiSearch();
$client->set_serp_api_key("Your Private Key");

```
Or

```php
$client = new SerpApiSearch("Your Private Key");

```

## Examples in php
Here is how to calls the APIs

### Search Archive API

Let's run a search to get a search_id.
```php
$client = new SerpApiSearch(getenv("API_KEY"));
$result = $client->get_json([
  'q' => 'Coffee',
  'location' => 'Austin, Texas'
]);
$search_id = $result->search_metadata->id;
```


Now let's retrieve the previous search from the archive.

```php
$archived_result = $client->search_archive($search_id);
print_r($archived_result);

```
it prints the search from the archive.

### Account API
```php
$client = new SerpApiSearch(getenv("API_KEY"));
$info = $client->get_account();
print_r($info);

```
it prints your account information.

### Search Google Images

```php
$client = new SerpApiSearch(getenv("API_KEY"));
$data = $client->get_json([
  'q' => "Coffee",
  'tbm' => 'isch'
]);

foreach($data->images_results as $image_result) {
  print_r($image_result->original);
  // to download the image:
  // `wget #{image_result[:original]}`
}
```



### Search bing
```php
class ExampleSearchBingTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'bing',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{bing}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchBingTest.php
see: [https://serpapi.com/bing-search-api](https://serpapi.com/bing-search-api)

### Search baidu
```php
class ExampleSearchBaiduTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'baidu',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{baidu}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchBaiduTest.php
see: [https://serpapi.com/baidu-search-api](https://serpapi.com/baidu-search-api)

### Search yahoo
```php
class ExampleSearchYahooTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'yahoo',
      'p' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{yahoo}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchYahooTest.php
see: [https://serpapi.com/yahoo-search-api](https://serpapi.com/yahoo-search-api)

### Search youtube
```php
class ExampleSearchYoutubeTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'youtube',
      'search_query' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('video_results', $response, "Error on `{youtube}` engine not has `{video_results}`");
  }
}
```
test: tests/ExampleSearchYoutubeTest.php
see: [https://serpapi.com/youtube-search-api](https://serpapi.com/youtube-search-api)

### Search walmart
```php
class ExampleSearchWalmartTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'walmart',
      'query' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{walmart}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchWalmartTest.php
see: [https://serpapi.com/walmart-search-api](https://serpapi.com/walmart-search-api)

### Search ebay
```php
class ExampleSearchEbayTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'ebay',
      '_nkw' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{ebay}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchEbayTest.php
see: [https://serpapi.com/ebay-search-api](https://serpapi.com/ebay-search-api)

### Search naver
```php
class ExampleSearchNaverTest extends \PHPUnit\Framework\TestCase {

  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'naver',
      'query' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('ads_results', $response, "Error on `{naver}` engine not has `{ads_results}`");
  }
}
```
test: tests/ExampleSearchNaverTest.php
see: [https://serpapi.com/naver-search-api](https://serpapi.com/naver-search-api)

### Search home depot
```php
class ExampleSearchHomeDepotTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'home_depot',
      'q' => 'table'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('products', $response, "Error on `{home_depot}` engine not has `{products}`");
  }
}
```
test: tests/ExampleSearchHomeDepotTest.php
see: [https://serpapi.com/home-depot-search-api](https://serpapi.com/home-depot-search-api)

### Search apple app store
```php
class ExampleSearchAppleAppStoreTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'apple_app_store',
      'term' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{apple_app_store}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchAppleAppStoreTest.php
see: [https://serpapi.com/apple-app-store](https://serpapi.com/apple-app-store)

### Search duckduckgo
```php
class ExampleSearchDuckduckgoTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'duckduckgo',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{duckduckgo}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchDuckduckgoTest.php
see: [https://serpapi.com/duckduckgo-search-api](https://serpapi.com/duckduckgo-search-api)

### Search google
```php
class ExampleSearchGoogleTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google',
      'tbm' => 'isch',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('images_results', $response, "Error on `{google}` engine not has `{images_results}`");
  }
}
```
test: tests/ExampleSearchGoogleTest.php
see: [https://serpapi.com/search-api](https://serpapi.com/search-api)

### Search google scholar
```php
class ExampleSearchGoogleScholarTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_scholar',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{google_scholar}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchGoogleScholarTest.php
see: [https://serpapi.com/google-scholar-api](https://serpapi.com/google-scholar-api)

### Search google autocomplete
```php
class ExampleSearchGoogleAutocompleteTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_autocomplete',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('suggestions', $response, "Error on `{google_autocomplete}` engine not has `{suggestions}`");
  }
}
```
test: tests/ExampleSearchGoogleAutocompleteTest.php
see: [https://serpapi.com/google-autocomplete-api](https://serpapi.com/google-autocomplete-api)

### Search google product
```php
class ExampleSearchGoogleProductTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_product',
      'q' => 'coffee',
      'product_id' => '4172129135583325756'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('product_results', $response, "Error on `{google_product}` engine not has `{product_results}`");
  }
}
```
test: tests/ExampleSearchGoogleProductTest.php
see: [https://serpapi.com/google-product-api](https://serpapi.com/google-product-api)

### Search google reverse image
```php
class ExampleSearchGoogleReverseImageTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_reverse_image',
      'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('image_sizes', $response, "Error on `{google_reverse_image}` engine not has `{image_sizes}`");
  }
}
```
test: tests/ExampleSearchGoogleReverseImageTest.php
see: [https://serpapi.com/google-reverse-image](https://serpapi.com/google-reverse-image)

### Search google events
```php
class ExampleSearchGoogleEventsTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_events',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('events_results', $response, "Error on `{google_events}` engine not has `{events_results}`");
  }
}
```
test: tests/ExampleSearchGoogleEventsTest.php
see: [https://serpapi.com/google-events-api](https://serpapi.com/google-events-api)

### Search google local services
```php
class ExampleSearchGoogleLocalServicesTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_local_services',
      'q' => 'electrician',
      'data_cid' => '6745062158417646970'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('local_ads', $response, "Error on `{google_local_services}` engine not has `{local_ads}`");
  }
}
```
test: tests/ExampleSearchGoogleLocalServicesTest.php
see: [https://serpapi.com/google-local-services-api](https://serpapi.com/google-local-services-api)

### Search google maps
```php
class ExampleSearchGoogleMapsTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_maps',
      'q' => 'pizza',
      'll' => '@40.7455096,-74.0083012,15.1z',
      'type' => 'search'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('local_results', $response, "Error on `{google_maps}` engine not has `{local_results}`");
  }
}
```
test: tests/ExampleSearchGoogleMapsTest.php
see: [https://serpapi.com/google-maps-api](https://serpapi.com/google-maps-api)

### Search google jobs
```php
class ExampleSearchGoogleJobsTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_jobs',
      'q' => 'coffee'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('jobs_results', $response, "Error on `{google_jobs}` engine not has `{jobs_results}`");
  }
}
```
test: tests/ExampleSearchGoogleJobsTest.php
see: [https://serpapi.com/google-jobs-api](https://serpapi.com/google-jobs-api)

### Search google play
```php
class ExampleSearchGooglePlayTest extends \PHPUnit\Framework\TestCase {
  
  private $search_params;
  private $api_key;

  protected function setUp(): void {
    $this->search_params = [
      'engine' => 'google_play',
      'q' => 'kite',
      'store' => 'apps'
    ];

    if(isset($_ENV["API_KEY"])) {
      $this->api_key = $_ENV["API_KEY"];
    } elseif(getenv('API_KEY')) {
      $this->api_key = getenv('API_KEY');
    } else {
      $this->api_key = "demo";
    }
 }

  function test_if_result_exist() {
    $search = new SerpApi($this->api_key);
    $response = $search->search($this->search_params);
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{google_play}` engine not has `{organic_results}`");
  }
}
```
test: tests/ExampleSearchGooglePlayTest.php
see: [https://serpapi.com/google-play-api](https://serpapi.com/google-play-api)


## Composer example

To run the code.
 - git clone https://github.com/serpapi/serpapi-php
 - cd serpapi-php
 - composer install


## Change log

 * 1.0
   * First stable version

## Conclusion

SerpApi supports all the major search engines. Google has the more advanced support with all the major services available: Images, News, Shopping and more...

[The full documentation is available here.](https://serpapi.com/search-api)

Authors: Victor Benarbia victor@serpapi.com, Alaa Abdulridha alaa@serpapi.com
For more information: https://serpapi.com

Thanks Rest API for Php
 - Travis Dent  - https://github.com/tcdent/php-restclient
 - Test framework - PhpUnit - https://phpunit.de/getting-started/phpunit-8.html

## Continuous integration

We love "true open source", "continuous integration", and Test Driven Development (TDD).
We use PHPUnit to test our infrastructure around the clock using [GitHub Actions](https://github.com/serpapi/serpapi-php/actions/workflows/serpapi-php.yml) to achieve the best QoS (Quality Of Service).

The `tests/` directory includes specifications which serve the dual purposes of examples and functional tests.

PHP versions validated by GitHub Actions:
* 7.2
* 7.3
* 7.4
* 8.0
* 8.1
* 8.2
* 8.3
* 8.4
* 8.5

Set your secret API key in your shell before running a test.

```bash
export API_KEY="your_secret_key"
```

Install testing dependencies and run the test suite:

```bash
make test
```

Contributions are welcome. Feel free to submit a pull request!

## License

MIT License.

