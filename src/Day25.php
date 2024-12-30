<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day25
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput();
        $keysAndLocks = explode("\n\n", $content);

        $keys = array_filter($keysAndLocks, fn (string $in): bool => str_starts_with($in, '#'));
        $locks = array_filter($keysAndLocks, fn (string $in): bool => str_starts_with($in, '.'));

        $keys = array_map(static function (string $key): array {
            $grid = array_map(static fn ($line) => str_split($line), explode("\n", $key));

            $width = count($grid[0]);
            $pinHeights = [];
            for ($x = 0; $x < $width; $x++) {
                $column = array_column($grid, $x);
                $counts = array_count_values($column);

                $pinHeights[] = $counts['#'] - 1;
            }

            return $pinHeights;
        }, $keys);
        $locks = array_map(static function (string $lock): array {
            $grid = array_map(static fn ($line) => str_split($line), explode("\n", $lock));

            $width = count($grid[0]);
            $pinHeights = [];
            for ($x = 0; $x < $width; $x++) {
                $column = array_column($grid, $x);
                $counts = array_count_values($column);

                $pinHeights[] = $counts['#'] - 1;
            }

            return $pinHeights;
        }, $locks);

        $match = 0;
        foreach ($keys as $key) {
            foreach ($locks as $lock) {
                for ($x = 0; $x < 5; $x++) {
                    if ($key[$x] + $lock[$x] > 5) {
                        continue 2;
                    };
                }
                $match++;
            }
        }

        return (string) $match;
    }

    public function partTwo(): string
    {
        return '';
    }
}