<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day10
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $grid = array_map(static fn($line) => array_map(fn (string $in) => is_numeric($in) ? (int) $in : null , array_values(str_split($line))), $lines);
        $maxX = count($grid);
        $maxY = count($grid[0]);

        $scores = 0;

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                $number = $grid[$x][$y];
                if ($number !== 0) {
                    continue;
                }

                $solutions = $this->findTrailHeads($x, $y, $grid);
                $scores += count(array_unique($solutions));
            }
        }
        return (string) $scores;
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $grid = array_map(static fn($line) => array_map(fn (string $in) => is_numeric($in) ? (int) $in : null , array_values(str_split($line))), $lines);
        $maxX = count($grid);
        $maxY = count($grid[0]);

        $scores = 0;

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                $number = $grid[$x][$y];
                if ($number !== 0) {
                    continue;
                }

                $solutions = $this->findTrailHeads($x, $y, $grid, unique: false);
                $scores += count($solutions);
            }
        }
        return (string) $scores;
    }

    private function findTrailHeads(int $x, int $y, array $grid, bool $unique = true): array
    {
        $number = $grid[$x][$y];

        if ($number === 9) {
            return [$x . ',' . $y];
        }

        $result = [];
        if (($grid[$x - 1][$y] ?? '-1') === $number + 1) {
            $result[] = $this->findTrailHeads($x - 1, $y, $grid, $unique);
        }
        if (($grid[$x][$y + 1] ?? '-1') === $number + 1) {
            $result[] = $this->findTrailHeads($x, $y + 1, $grid, $unique);
        }
        if (($grid[$x + 1][$y] ?? '-1') === $number + 1) {
            $result[] = $this->findTrailHeads($x + 1, $y, $grid, $unique);
        }
        if (($grid[$x][$y - 1] ?? '-1') === $number + 1) {
            $result[] = $this->findTrailHeads($x, $y - 1, $grid, $unique);
        }

        $merged = array_merge(...$result);
        if ($unique === false) {
            return $merged;
        }

        return array_unique($merged);
    }
}