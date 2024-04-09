
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
require __DIR__ . '/vendor/autoload.php';
```

if not, you must clone this repository and link the class.
```php
require 'path/to/serpapi-php';
```

Get "secret_api_key" from https://serpapi.com/dashboard

Then you can start coding something like:
```php
require 'vendor/autoload.php';
$params = [
  "engine"  => "naver",
  "query"   => "paris",
  "api_key" => "secret_api_key"
];

$search = new SerpApi($params);
$result = $search->get_json();
print_r($result);
```

This example runs a search about "coffee" using your secret api key.

The SerpApi service (backend)
 - searches on Google using the query: q = "coffee"
 - parses the messy HTML responses
 - return a standardizes JSON response
The PHP class SerpApi
 - Format the request to SerpApi server
 - Execute GET http request
 - Parse JSON into Ruby Hash using JSON standard library provided by Ruby
Et voila..

### How to set SERP API key
The SerpApi api_key can be set globally using a singleton pattern.

```php
$search = new SerpApi();
$search->_api_key = "secret_api_key";
```
Or with same query like this:

```php
$params = [
  "api_key" => "secret_api_key"
];

$search = new SerpApi($params);
```

## Examples in php
Here is how to calls the APIs

### Search Archive API

Let's run a search to get a search_id.
```php
$params = [
  "q" => "Coffee", 
  "location" => "Portland"
];

$search = new SerpApi($params);
$result = $search->get_json();
$search_id = $result->search_metadata->id;
echo $search_id;
```

Now let's retrieve the previous search from the archive.

```php
$archived_result = $search->get_search_archive($search_id);
print_r($archived_result);

```
it prints the search from the archive.

### Account API
```php
$search = new SerpApi();
$search->_api_key = "secret_api_key";
$info = $search->get_account();
print_r($info);
```
it prints your account information.

### Search API capability for Google

```php
$search_params = [
  "q" => "search",
  "google_domain" => "Google Domain",
  "location" => "Location Requested",
  "device" => "desktop|mobile|tablet",
  "hl" => "Google UI Language",
  "gl" => "Google Country",
  "safe" => "Safe Search Flag",
  "num" => "Number of Results",
  "start" => "Pagination Offset",
  "api_key" => "private key", # copy paste from https://serpapi.com/dashboard
  "tbm" => "nws|isch|shop",
  "tbs" => "custom to be search criteria",
  "async" => true|false # allow async
];

# define the search search
$search = new SerpApi($search_params);

# override an existing parameter
$search->_params["location"] = "Portland,Oregon,United States";

# search format return as raw html
$html_results = $search->get_html();

# search as raw JSON format
$json_results = $search->get_json();
```

(the full documentation)[https://serpapi.com/search-api].

More search API are documented on [SerpApi.com](http://serpapi.com).

You will find more hands on examples below.

### Search Google Images

```php
$params = [
  'q' => "Coffee",
  'tbm' => 'isch'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();

foreach($data->images_results as $image_result) {
  print_r($image_result->original);
}
```

### Google Search By Location

With SerpApi.com, we can build Google search from anywhere in the world. This code is looking for the best coffee shop per city.

```php
foreach(["new york", "paris", "berlin"] as $location) {
  $search = new SerpApi(["q" => $location, "limit" => 1]);
  $search->_api_key = "secret_api_key";
  $location_name = $search->get_location()[0]->canonical_name;
  
  $search->_params = [
    "q" => 'best coffee shop',
    "location" => $location_name,
    "start" => 0 # offset
  ];

  $top_result = $search->get_json()->organic_results[0];
  echo "top coffee result for {$location_name} is: {$top_result->title}".PHP_EOL;
}
```

### Search bing
```php
$params = [
  'engine' => 'bing',
  'q' => 'Coffee',
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_bing_test.php
see: [https://serpapi.com/bing-search-api](https://serpapi.com/bing-search-api)

### Search baidu
```php
$params = [
  'engine' => 'baidu',
  'q' => 'Coffee',
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_baidu_test.php
see: [https://serpapi.com/baidu-search-api](https://serpapi.com/baidu-search-api)

### Search yahoo
```php
$params = [
  'engine' => 'yahoo',
  'p' => 'Coffee',
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_yahoo_test.php
see: [https://serpapi.com/yahoo-search-api](https://serpapi.com/yahoo-search-api)

### Search youtube
```php
$params = [
  'engine' => 'youtube',
  'search_query' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_youtube_test.php
see: [https://serpapi.com/youtube-search-api](https://serpapi.com/youtube-search-api)

### Search walmart
```php
$params = [
  'engine' => 'walmart',
  'query' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_walmart_test.php
see: [https://serpapi.com/walmart-search-api](https://serpapi.com/walmart-search-api)

### Search ebay
```php
$params = [
  'engine' => 'ebay',
  '_nkw' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_ebay_test.php
see: [https://serpapi.com/ebay-search-api](https://serpapi.com/ebay-search-api)

### Search naver
```php
$params = [
  'engine' => 'naver',
  'query' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_naver_test.php
see: [https://serpapi.com/naver-search-api](https://serpapi.com/naver-search-api)

### Search home depot
```php
$params = [
  'engine' => 'home_depot',
  'q' => 'table'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_home_depot_test.php
see: [https://serpapi.com/home-depot-search-api](https://serpapi.com/home-depot-search-api)

### Search apple app store
```php
$params = [
  'engine' => 'apple_app_store',
  'term' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_apple_app_store_test.php
see: [https://serpapi.com/apple-app-store](https://serpapi.com/apple-app-store)

### Search duckduckgo
```php
$params = [
  'engine' => 'duckduckgo',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_duckduckgo_test.php
see: [https://serpapi.com/duckduckgo-search-api](https://serpapi.com/duckduckgo-search-api)

### Search google
```php
$params = [
  'engine' => 'google',
  'tbm' => 'isch',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_test.php
see: [https://serpapi.com/search-api](https://serpapi.com/search-api)

### Search google scholar
```php
$params = [
  'engine' => 'google_scholar',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_scholar_test.php
see: [https://serpapi.com/google-scholar-api](https://serpapi.com/google-scholar-api)

### Search google autocomplete
```php
$params = [
  'engine' => 'google_autocomplete',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_autocomplete_test.php
see: [https://serpapi.com/google-autocomplete-api](https://serpapi.com/google-autocomplete-api)

### Search google product
```php
$params = [
  'engine' => 'google_product',
  'q' => 'coffee',
  'product_id' => '4172129135583325756'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_product_test.php
see: [https://serpapi.com/google-product-api](https://serpapi.com/google-product-api)

### Search google reverse image
```php
$params = [
  'engine' => 'google_reverse_image',
  'image_url' => 'https://i.imgur.com/5bGzZi7.jpg'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_reverse_image_test.php
see: [https://serpapi.com/google-reverse-image](https://serpapi.com/google-reverse-image)

### Search google events
```php
$params = [
  'engine' => 'google_events',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_events_test.php
see: [https://serpapi.com/google-events-api](https://serpapi.com/google-events-api)

### Search google local services
```php
$params = [
  'engine' => 'google_local_services',
  'q' => 'Electrician',
  'place_id' => 'ChIJOwg_06VPwokRYv534QaPC8g'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_local_services_test.php
see: [https://serpapi.com/google-local-services-api](https://serpapi.com/google-local-services-api)

### Search google maps
```php
$params = [
  'engine' => 'google_maps',
  'q' => 'pizza',
  'll' => '@40.7455096,-74.0083012,15.1z',
  'type' => 'search'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_maps_test.php
see: [https://serpapi.com/google-maps-api](https://serpapi.com/google-maps-api)

### Search google jobs
```php
$params = [
  'engine' => 'google_jobs',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_jobs_test.php
see: [https://serpapi.com/google-jobs-api](https://serpapi.com/google-jobs-api)

### Search google play
```php
$params = [
  'engine' => 'google_play',
  'q' => 'kite',
  'store' => 'apps'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data);
```
test: tests/example_search_google_play_test.php
see: [https://serpapi.com/google-play-api](https://serpapi.com/google-play-api)

### Generic SerpApi search
```php
$params = [
  'engine' => 'google',
  'tbm' => 'isch',
  'q' => 'coffee'
];

$search = new SerpApi($params);
$search->_api_key = "secret_api_key";
$data = $search->get_json();
print_r($data->organic_results);
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
 - Test framework - PhpUnit - https://phpunit.de/getting-started/phpunit-8.html

To run the tests:

```bash
export API_KEY="your api key"
vendor\bin\phpunit
```
