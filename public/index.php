<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use App\Application\Services\FileCreationService;
use App\Application\Services\FileUploadService;
use App\Domain\Entities\RandomQuoteEntity;
use App\Infrastructure\FileSaver;
use App\Infrastructure\GitHubRepository;
use App\UI\GitHubFileUploader;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use function DI\autowire;

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../', null, false);
    $dotenv->safeLoad();
} catch (Exception $exception) {}

$owner = getenv('OWNER') ?: $_ENV['OWNER'] ?? null;
$repo = getenv('REPO') ?: $_ENV['REPO'] ?? null;
$apikey = getenv('APIKEY') ?: $_ENV['APIKEY'] ?? null;

if (!$owner || !$repo || !$apikey) {
    exit('No environment variables were found');
}

// Configure dependency injection container
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    GitHubRepository::class => fn() => new GitHubRepository($apikey),
    FileUploadService::class => fn($c) => new FileUploadService($c->get(GitHubRepository::class)),
    GitHubFileUploader::class => fn($c) => new GitHubFileUploader($c->get(FileUploadService::class)),
    RandomQuoteEntity::class => autowire(),
    FileSaver::class => autowire(),
    FileCreationService::class => autowire(),
]);

$container = $containerBuilder->build();

// Fetch dependencies from container
$gitHubFileUploader = $container->get(GitHubFileUploader::class);
$fileCreationService = $container->get(FileCreationService::class);
$fileSaver = $container->get(FileSaver::class);


try {
    $randomQuote = $fileCreationService->getRandomQuote();
    $content = $randomQuote->getQuote();
    $commitMessage = $randomQuote->getAuthor();
} catch (Exception $exception) {
    $content = 'Big changes are coming...';
    $commitMessage = 'There will definitely be changes starting tomorrow';
}

$currentDate = date('d.m.y');
$path = 'contents/' . date('d.m.y') . '.txt';
$pathForSaving = __DIR__ . "/$path";

if (!$fileSaver->save($pathForSaving, $content)) {
    exit('Something went wrong');
}

$gitHubFileUploader->upload($owner, $repo, $path, $content, $commitMessage);