<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\DailyCommitEntry;
use DateTimeImmutable;

final class FileCreationService
{
    public function __construct(
        private DailyCommitEntryFactory $entryFactory
    ) {
    }

    public function createDailyEntry(DateTimeImmutable $date, ?string $runMarker = null): DailyCommitEntry
    {
        return $this->entryFactory->create($date, $runMarker);
    }
}
