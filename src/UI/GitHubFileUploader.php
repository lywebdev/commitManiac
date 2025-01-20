<?php

declare(strict_types=1);

namespace App\UI;

use App\Application\Services\FileUploadService;
use Exception;

class GitHubFileUploader
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function upload(
        string $githubProfileNick,
        string $repoName,
        string $filePath,
        string $content,
        string $commitMessage
    ): void {
        try {
            $url = $this->fileUploadService->uploadFile($githubProfileNick, $repoName, $filePath, $content, $commitMessage);
            echo "File successfully uploaded: " . $url . PHP_EOL;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }
}
