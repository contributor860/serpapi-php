
# SerpApi PHP  library

[![serpapi-php](https://github.com/serpapi/serpapi-php/actions/workflows/test.yml/badge.svg)](https://github.com/serpapi/serpapi-php/actions/workflows/test.yml)

Integrate search data into your Ruby application. This library is the official wrapper for SerpApi (https://serpapi.com).

SerpApi supports Google, Google Maps, Google Shopping, Baidu, Yandex, Yahoo, eBay, App Stores, and more.

This is the new library provided by SerpApi as a replacement for our old library that can be found [here](https://github.com/serpapi/google-search-results-php) feel free to contact us in case you needed any help: contact@serpapi.com


[The full documentation is available here.](https://serpapi.com/search-api)

The following services are provided:
 * [Search API](https://serpapi.com/search-api)
 * [Location API](https://serpapi.com/locations-api)
 * [Search Archive API](https://serpapi.com/search-archive-api)
 * [Account API](https://serpapi.com/account-api)

SerpApi provides a [script builder](https://serpapi.com/demo) to get you started quickly.

## Installation

PHP 7.1+ must be already installed and [composer](https://getcomposer.org/) dependency management tool.

Tested PHP versions:
* 7.1.33
* 7.2.34
* 7.3.33
* 7.4.33
* 8.0.30
* 8.1.27
* 8.2.17
* 8.3.4

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
print_r($result)

 ```

This example runs a search about "coffee" using your secret api key.

The SerpApi service (backend)
 - searches on Google using the query: q = "coffee"
 - parses the messy HTML responses
 - return a standardizes JSON response
The PHP class SerpApiSearch
 - Format the request to SerpApi server
 - Execute GET http request
 - Parse JSON into Ruby Hash using JSON standard library provided by Ruby
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
$client = SerpApiSearch(getenv("API_KEY"));
$result = $client->get_json($this->QUERY);
$search_id = $result->search_metadata->id

```


Now let's retrieve the previous search from the archive.

```php
$archived_result = $client->get_search_archive($search_id);
print_r($archived_result);

```
it prints the search from the archive.

### Account API
```php
$client = new SerpApiSearch($this->API_KEY);
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
  //to download the image:
  // `wget #{image_result[:original]}`
}
```



### Search bing
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'bing',
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{bing}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_bing_test.php
see: [https://serpapi.com/bing-search-api](https://serpapi.com/bing-search-api)

### Search baidu
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'baidu',
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{baidu}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_baidu_test.php
see: [https://serpapi.com/baidu-search-api](https://serpapi.com/baidu-search-api)

### Search yahoo
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'yahoo',
      'p' => 'coffee'
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{yahoo}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_yahoo_test.php
see: [https://serpapi.com/yahoo-search-api](https://serpapi.com/yahoo-search-api)

### Search youtube
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'youtube',
      'search_query' => 'coffee'
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
    $this->assertObjectHasAttribute('video_results', $response, "Error on `{youtube}` engine not has `{video_results}`");
  }
}
```
test: tests/example_search_youtube_test.php
see: [https://serpapi.com/youtube-search-api](https://serpapi.com/youtube-search-api)

### Search walmart
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'walmart',
      'query' => 'coffee'
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{walmart}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_walmart_test.php
see: [https://serpapi.com/walmart-search-api](https://serpapi.com/walmart-search-api)

### Search ebay
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'ebay',
      '_nkw' => 'coffee'
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{ebay}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_ebay_test.php
see: [https://serpapi.com/ebay-search-api](https://serpapi.com/ebay-search-api)

### Search naver
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'naver',
      'query' => 'coffee'
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
    $this->assertObjectHasAttribute('ads_results', $response, "Error on `{naver}` engine not has `{ads_results}`");
  }
}
```
test: tests/example_search_naver_test.php
see: [https://serpapi.com/naver-search-api](https://serpapi.com/naver-search-api)

### Search home depot
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'home_depot',
      'q' => 'table'
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
    $this->assertObjectHasAttribute('products', $response, "Error on `{home_depot}` engine not has `{products}`");
  }
}
```
test: tests/example_search_home_depot_test.php
see: [https://serpapi.com/home-depot-search-api](https://serpapi.com/home-depot-search-api)

### Search apple app store
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'apple_app_store',
      'term' => 'coffee'
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{apple_app_store}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_apple_app_store_test.php
see: [https://serpapi.com/apple-app-store](https://serpapi.com/apple-app-store)

### Search duckduckgo
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'duckduckgo',
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{duckduckgo}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_duckduckgo_test.php
see: [https://serpapi.com/duckduckgo-search-api](https://serpapi.com/duckduckgo-search-api)

### Search google
```php
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
```
test: tests/example_search_google_test.php
see: [https://serpapi.com/search-api](https://serpapi.com/search-api)

### Search google scholar
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_scholar',
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{google_scholar}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_google_scholar_test.php
see: [https://serpapi.com/google-scholar-api](https://serpapi.com/google-scholar-api)

### Search google autocomplete
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_autocomplete',
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
    $this->assertObjectHasAttribute('suggestions', $response, "Error on `{google_autocomplete}` engine not has `{suggestions}`");
  }
}
```
test: tests/example_search_google_autocomplete_test.php
see: [https://serpapi.com/google-autocomplete-api](https://serpapi.com/google-autocomplete-api)

### Search google product
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_product',
      'q' => 'coffee',
      'product_id' => '4172129135583325756'
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
    $this->assertObjectHasAttribute('product_results', $response, "Error on `{google_product}` engine not has `{product_results}`");
  }
}
```
test: tests/example_search_google_product_test.php
see: [https://serpapi.com/google-product-api](https://serpapi.com/google-product-api)

### Search google reverse image
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_reverse_image',
      'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'
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
    $this->assertObjectHasAttribute('image_sizes', $response, "Error on `{google_reverse_image}` engine not has `{image_sizes}`");
  }
}
```
test: tests/example_search_google_reverse_image_test.php
see: [https://serpapi.com/google-reverse-image](https://serpapi.com/google-reverse-image)

### Search google events
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_events',
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
    $this->assertObjectHasAttribute('events_results', $response, "Error on `{google_events}` engine not has `{events_results}`");
  }
}
```
test: tests/example_search_google_events_test.php
see: [https://serpapi.com/google-events-api](https://serpapi.com/google-events-api)

### Search google local services
```php
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
```
test: tests/example_search_google_local_services_test.php
see: [https://serpapi.com/google-local-services-api](https://serpapi.com/google-local-services-api)

### Search google maps
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_maps',
      'q' => 'pizza',
      'll' => '@40.7455096,-74.0083012,15.1z',
      'type' => 'search'
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
    $this->assertObjectHasAttribute('local_results', $response, "Error on `{google_maps}` engine not has `{local_results}`");
  }
}
```
test: tests/example_search_google_maps_test.php
see: [https://serpapi.com/google-maps-api](https://serpapi.com/google-maps-api)

### Search google jobs
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_jobs',
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
    $this->assertObjectHasAttribute('jobs_results', $response, "Error on `{google_jobs}` engine not has `{jobs_results}`");
  }
}
```
test: tests/example_search_google_jobs_test.php
see: [https://serpapi.com/google-jobs-api](https://serpapi.com/google-jobs-api)

### Search google play
```php
class serpapiTest extends \PHPUnit\Framework\TestCase {
  protected function setUp(): void {
    $this->QUERY = [
      'engine' => 'google_play',
      'q' => 'kite',
      'store' => 'apps'
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
    $this->assertObjectHasAttribute('organic_results', $response, "Error on `{google_play}` engine not has `{organic_results}`");
  }
}
```
test: tests/example_search_google_play_test.php
see: [https://serpapi.com/google-play-api](https://serpapi.com/google-play-api)

### Search google
```php
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
```
test: tests/example_search_google_test.php
see: [https://serpapi.com/search-api](https://serpapi.com/search-api)


## Composer example

To run the code.
 - git clone https://github.com/serpapi/serpapi-php
 - cd serpapi-php
 - composer install
 - composer update


## Change log

 * 1.0
   * First stable version

## Conclusion

SerpApi supports all the major search engines. Google has the more advance support with all the major services available: Images, News, Shopping and more...

[The full documentation is available here.](https://serpapi.com/search-api)

Authors: Victor Benarbia victor@serpapi.com, Alaa Abdulridha alaa@serpapi.com
For more information: https://serpapi.com

Thanks Rest API for Php
 - Travis Dent  - https://github.com/tcdent/php-restclient
 - Test framework - PhpUnit - https://phpunit.de/getting-started/phpunit-7.html

