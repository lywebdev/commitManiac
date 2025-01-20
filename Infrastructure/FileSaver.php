<?php

declare(strict_types=1);

namespace App\Infrastructure;

use RuntimeException;

final class FileSaver
{
    public function save(string $filename, string $content): bool
    {
        $directory = dirname($filename);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new RuntimeException(sprintf('Failed to create directory: %s', $directory));
            }
        }

        return file_put_contents($filename, $content) !== false;
    }
}