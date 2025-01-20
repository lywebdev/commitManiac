<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\RandomQuoteEntity;
use App\Infrastructure\RandomQuoteApiClient;

final class FileCreationService
{
    private RandomQuoteApiClient $apiClient;

    public function __construct(RandomQuoteApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getRandomQuote(): RandomQuoteEntity
    {
        return $this->apiClient->fetchRandomQuote();
    }
}