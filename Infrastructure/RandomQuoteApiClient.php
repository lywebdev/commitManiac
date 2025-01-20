<?php

declare(strict_types=1);

namespace App\Infrastructure;


use App\Domain\Entities\RandomQuoteEntity;
use GuzzleHttp\Client;

/**
 * Quotes powered by the QuoteSlate API: https://github.com/Musheer360/QuoteSlate
 */
final class RandomQuoteApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchRandomQuote(): RandomQuoteEntity
    {
        $response = $this->client->get('https://quoteslate.vercel.app/api/quotes/random');
        $data = json_decode($response->getBody()->getContents());

        return new RandomQuoteEntity($data->quote, $data->author);
    }
}