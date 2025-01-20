<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

interface GitHubRepositoryInterface
{
    public function uploadFile(
        string  $githubProfileNick,
        string  $repoName,
        string  $filePath,
        string  $content,
        string  $commitMessage,
        ?string $sha = null
    ): string;
}
