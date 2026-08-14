<?php

namespace SerpApi;

class SerpApiException extends \Exception
{
  /** @var string|null */
  private $serpApiError;

  /** @var array<string, mixed>|null */
  private $searchParams;

  /** @var int|null */
  private $responseStatus;

  /** @var string|null */
  private $searchId;

  /** @var string|null */
  private $decoder;

  /**
   * @param array<string, mixed>|null $searchParams
   */
  public function __construct(
    string $message = '',
    ?string $serpApiError = null,
    ?array $searchParams = null,
    ?int $responseStatus = null,
    ?string $searchId = null,
    ?string $decoder = null,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, 0, $previous);

    $this->serpApiError = $serpApiError;
    $this->searchParams = $searchParams;
    $this->responseStatus = $responseStatus;
    $this->searchId = $searchId;
    $this->decoder = $decoder;
  }

  public function getSerpApiError(): ?string
  {
    return $this->serpApiError;
  }

  /**
   * @return array<string, mixed>|null
   */
  public function getSearchParams(): ?array
  {
    return $this->searchParams;
  }

  public function getResponseStatus(): ?int
  {
    return $this->responseStatus;
  }

  public function getSearchId(): ?string
  {
    return $this->searchId;
  }

  public function getDecoder(): ?string
  {
    return $this->decoder;
  }

  /**
   * @return array<string, mixed>
   */
  public function toArray(): array
  {
    return array_filter([
      'message' => $this->getMessage(),
      'serpApiError' => $this->serpApiError,
      'searchParams' => $this->searchParams,
      'responseStatus' => $this->responseStatus,
      'searchId' => $this->searchId,
      'decoder' => $this->decoder,
    ], static function ($value) {
      return $value !== null;
    });
  }
}
