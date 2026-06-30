<?php

namespace SerpApi;

class SerpApiException extends \Exception {
  /** @var string|null */
  private $serpapi_error;

  /** @var array<string, mixed>|null */
  private $search_params;

  /** @var int|null */
  private $response_status;

  /** @var string|null */
  private $search_id;

  /** @var string|null */
  private $decoder;

  /**
   * @param array<string, mixed>|null $search_params
   */
  public function __construct(
    string $message = '',
    ?string $serpapi_error = null,
    ?array $search_params = null,
    ?int $response_status = null,
    ?string $search_id = null,
    ?string $decoder = null,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, 0, $previous);

    $this->serpapi_error = $serpapi_error;
    $this->search_params = $search_params;
    $this->response_status = $response_status;
    $this->search_id = $search_id;
    $this->decoder = $decoder;
  }

  public function get_serpapi_error(): ?string {
    return $this->serpapi_error;
  }

  /**
   * @return array<string, mixed>|null
   */
  public function get_search_params(): ?array {
    return $this->search_params;
  }

  public function get_response_status(): ?int {
    return $this->response_status;
  }

  public function get_search_id(): ?string {
    return $this->search_id;
  }

  public function get_decoder(): ?string {
    return $this->decoder;
  }

  /**
   * @return array<string, mixed>
   */
  public function to_array(): array {
    return array_filter([
      'message'          => $this->getMessage(),
      'serpapi_error'    => $this->serpapi_error,
      'search_params'    => $this->search_params,
      'response_status'  => $this->response_status,
      'search_id'        => $this->search_id,
      'decoder'          => $this->decoder,
    ], static function ($value) {
      return $value !== null;
    });
  }
}
