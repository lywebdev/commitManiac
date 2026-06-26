<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\DailyCommitEntry;
use DateTimeImmutable;

final class DailyCommitEntryFactory
{
    private const FACTS = [
        'Git stores snapshots, not file-by-file diffs, and then compresses similar objects efficiently.',
        'A commit hash changes when any tracked byte in its tree or metadata changes.',
        'The default branch matters for contribution graphs because GitHub counts eligible commits there.',
        'Small commits are easier to review, revert, and explain than large mixed changes.',
        'A repository can be quiet in code and still alive in history when automation leaves a useful trace.',
        'Cron schedules in GitHub Actions run on UTC time, so local dates need an explicit timezone.',
        'Generated files are safest when the generator is deterministic for the same input date.',
        'A boring daily task becomes reliable when it has no external API in its critical path.',
        'Commit messages age better when they say what changed instead of how hard it was.',
        'A green graph is built one eligible commit at a time, not by making today carry every idea.',
    ];

    private const SIGNALS = [
        'fresh leaf on the graph',
        'tiny proof of work',
        'scheduled pulse',
        'quiet repository heartbeat',
        'daily green spark',
        'one-file ritual',
        'calendar checkpoint',
        'automation footprint',
        'steady commit trace',
        'low-noise activity mark',
    ];

    private const RITUALS = [
        'Write one honest line, save it, and move on.',
        'Keep the change small enough that tomorrow can understand it.',
        'Let the automation do the watering while the codebase stays calm.',
        'Prefer a deterministic note over a flaky dependency.',
        'Leave a readable artifact instead of a random blob.',
        'Make the commit useful to humans and eligible for the graph.',
        'Use the date as the seed and the repository as the canvas.',
        'Keep the habit free, local, and easy to restart.',
        'Commit the signal, not the noise.',
        'Turn a scheduled run into a tiny piece of project folklore.',
    ];

    public function create(DateTimeImmutable $date, ?string $runMarker = null): DailyCommitEntry
    {
        $path = sprintf(
            'contents/%s/%s/%s.txt',
            $date->format('Y'),
            strtolower($date->format('F')),
            $date->format('d')
        );
        $isoDate = $date->format('Y-m-d');
        $seed = (int) sprintf('%u', crc32($isoDate . '|commit-maniac'));

        $fact = $this->pick(self::FACTS, $seed, 0);
        $signal = $this->pick(self::SIGNALS, $seed, 7);
        $ritual = $this->pick(self::RITUALS, $seed, 17);
        $checksum = substr(hash('sha256', $isoDate . '|' . $fact . '|' . $ritual), 0, 12);

        $content = implode(PHP_EOL, [
            '# CommitManiac greenhouse log',
            '',
            'Date: ' . $isoDate,
            'Path: ' . $path,
            'Signal: ' . $signal,
            '',
            'Daily fact:',
            $fact,
            '',
            'Ritual:',
            $ritual,
            '',
            'Seed: cm-' . $date->format('Ymd') . '-' . $checksum,
            '',
        ]);

        if ($runMarker !== null && $runMarker !== '') {
            $content .= 'Run: ' . $runMarker . PHP_EOL;
        }

        return new DailyCommitEntry(
            $path,
            $content,
            sprintf('daily(%s): %s', $isoDate, $signal)
        );
    }

    /**
     * @param list<string> $items
     */
    private function pick(array $items, int $seed, int $salt): string
    {
        return $items[($seed + $salt) % count($items)];
    }
}
