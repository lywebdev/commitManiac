<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Interfaces\GitHubRepositoryInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class GitHubRepository implements GitHubRepositoryInterface
{
    private Client $client;

    public function __construct(string $token)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/vnd.github.v3+json',
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function uploadFile(string $githubProfileNick, string $repoName, string $filePath, string $content, string $commitMessage, ?string $sha = null): string
    {
        try {
            if ($sha === null) {
                try {
                    $response = $this->client->get("repos/$githubProfileNick/$repoName/contents/$filePath");
                    $data = json_decode($response->getBody()->getContents(), true);
                    $sha = $data['sha'] ?? null;
                } catch (ClientException $e) {
                    if ($e->getResponse()->getStatusCode() !== 404) {
                        throw $e;
                    }

                    $sha = null;
                }
            }

            $response = $this->client->put("repos/$githubProfileNick/$repoName/contents/$filePath", [
                'json' => [
                    'message' => $commitMessage,
                    'content' => base64_encode($content),
                    'sha'     => $sha,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result['content']['html_url'];
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new Exception('File not found, unable to update');
            }

            throw $e;
        }
    }
}
