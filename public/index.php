<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application\Services\FileCreationService;
use App\Application\Services\FileUploadService;
use App\Application\Services\DailyCommitEntryFactory;
use App\Infrastructure\FileSaver;
use App\Infrastructure\GitHubRepository;
use App\UI\GitHubFileUploader;
use Dotenv\Dotenv;

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../', null, false);
    $dotenv->safeLoad();
} catch (Exception $exception) {}

function envValue(string $name): ?string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        $value = $_ENV[$name] ?? null;
    }

    return $value !== '' ? $value : null;
}

function publishGithubOutput(string $key, string $value): void
{
    $outputFile = getenv('GITHUB_OUTPUT');

    if ($outputFile === false || $outputFile === '') {
        return;
    }

    file_put_contents($outputFile, $key . '=' . $value . PHP_EOL, FILE_APPEND);
}

$timezone = new DateTimeZone(envValue('COMMITMANIAC_TIMEZONE') ?? 'Europe/Amsterdam');
$fileCreationService = new FileCreationService(new DailyCommitEntryFactory());
$fileSaver = new FileSaver();
$entry = $fileCreationService->createDailyEntry(
    new DateTimeImmutable('now', $timezone),
    envValue('COMMITMANIAC_RUN_MARKER')
);
$pathForSaving = dirname(__DIR__) . '/' . $entry->getPath();

if (!$fileSaver->save($pathForSaving, $entry->getContent())) {
    exit('Something went wrong');
}

$owner = envValue('OWNER');
$repo = envValue('REPO');
$apikey = envValue('APIKEY');

publishGithubOutput('content_path', $entry->getPath());
publishGithubOutput('commit_message', $entry->getCommitMessage());

echo 'Daily entry saved: ' . $entry->getPath() . PHP_EOL;
echo 'Commit message: ' . $entry->getCommitMessage() . PHP_EOL;

if ($owner && $repo && $apikey) {
    $gitHubFileUploader = new GitHubFileUploader(
        new FileUploadService(new GitHubRepository($apikey))
    );

    $gitHubFileUploader->upload($owner, $repo, $entry->getPath(), $entry->getContent(), $entry->getCommitMessage());
}
