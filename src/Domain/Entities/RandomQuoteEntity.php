<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final readonly class RandomQuoteEntity
{
    public function __construct(
        private string $quote,
        private string $author
    ) {}

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
}