<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Interfaces\GitHubRepositoryInterface;

class FileUploadService
{
    private GitHubRepositoryInterface $gitHubRepository;

    public function __construct(GitHubRepositoryInterface $gitHubRepository)
    {
        $this->gitHubRepository = $gitHubRepository;
    }

    public function uploadFile(
        string $githubProfileNick,
        string $repoName,
        string $filePath,
        string $content,
        string $commitMessage
    ): string {
        return $this->gitHubRepository->uploadFile($githubProfileNick, $repoName, $filePath, $content, $commitMessage);
    }
}
