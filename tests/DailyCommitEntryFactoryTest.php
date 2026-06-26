<?php

declare(strict_types=1);

use App\Application\Services\DailyCommitEntryFactory;

require __DIR__ . '/../vendor/autoload.php';

function assertSameValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . PHP_EOL . 'Expected: ' . var_export($expected, true) . PHP_EOL . 'Actual: ' . var_export($actual, true));
    }
}

function assertNotSameValue(mixed $unexpected, mixed $actual, string $message): void
{
    if ($unexpected === $actual) {
        throw new RuntimeException($message . PHP_EOL . 'Both values: ' . var_export($actual, true));
    }
}

function assertContainsText(string $needle, string $haystack, string $message): void
{
    if (!str_contains($haystack, $needle)) {
        throw new RuntimeException($message . PHP_EOL . 'Missing: ' . $needle . PHP_EOL . 'Content: ' . $haystack);
    }
}

function assertDoesNotContainText(string $needle, string $haystack, string $message): void
{
    if (str_contains($haystack, $needle)) {
        throw new RuntimeException($message . PHP_EOL . 'Unexpected: ' . $needle . PHP_EOL . 'Content: ' . $haystack);
    }
}

$factory = new DailyCommitEntryFactory();
$date = new DateTimeImmutable('2026-06-26 09:15:00', new DateTimeZone('Europe/Amsterdam'));
$entry = $factory->create($date);
$sameDayEntry = $factory->create($date);
$nextDayEntry = $factory->create($date->modify('+1 day'));

assertSameValue('contents/26.06.26.txt', $entry->getPath(), 'Entry path should use the current day.');
assertContainsText('# CommitManiac greenhouse log', $entry->getContent(), 'Entry should use the new original format.');
assertContainsText('Date: 2026-06-26', $entry->getContent(), 'Entry should include the ISO date.');
assertContainsText('Path: contents/26.06.26.txt', $entry->getContent(), 'Entry should include the saved path.');
assertDoesNotContainText('Big changes are coming', $entry->getContent(), 'Entry should not fall back to the old generic text.');
assertSameValue($entry->getContent(), $sameDayEntry->getContent(), 'Entry content should be deterministic for the same day.');
assertSameValue($entry->getCommitMessage(), $sameDayEntry->getCommitMessage(), 'Commit message should be deterministic for the same day.');
assertNotSameValue($entry->getContent(), $nextDayEntry->getContent(), 'Entry content should change on the next day.');
assertNotSameValue($entry->getCommitMessage(), $nextDayEntry->getCommitMessage(), 'Commit message should change on the next day.');
assertSameValue(1, preg_match('/^daily\(2026-06-26\): [a-z0-9 -]+$/', $entry->getCommitMessage()), 'Commit message should be concise and machine-friendly.');

echo 'DailyCommitEntryFactoryTest passed' . PHP_EOL;
