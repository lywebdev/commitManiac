<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final readonly class DailyCommitEntry
{
    public function __construct(
        private string $path,
        private string $content,
        private string $commitMessage
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }
}
