<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day04
{
    use ContentReader;

    public function partOne(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        $count = 0;

        $maxX = count($grid);
        $maxY = count($grid[0]);

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                $count += $this->checkPositionXmas($x, $y, $grid);
            }
        }

        return (string) $count;

    }

    public function partTwo(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        $count = 0;

        $maxX = count($grid);
        $maxY = count($grid[0]);

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                $count += $this->checkPositionForMas($x, $y, (object) [
                    'grid' => $grid,
                    'maxX' => $maxX,
                    'maxY' => $maxY,
                ]);
            }
        }

        return (string) $count;
    }

    private function checkPositionXmas(int $x, int $y, array $grid): int
    {
        if ($grid[$x][$y] !== 'X') {
            return 0;
        }

        $count = 0;
        $offsets = [
            'east' => ['xoffset' => 0, 'yoffset' => 1],
            'south-east' => ['xoffset' => 1, 'yoffset' => 1],
            'south' => ['xoffset' => 1, 'yoffset' => 0],
            'south-west' => ['xoffset' => 1, 'yoffset' => -1],
            'west' => ['xoffset' => 0, 'yoffset' => -1],
            'north-west' => ['xoffset' => -1, 'yoffset' => -1],
            'north' => ['xoffset' => -1, 'yoffset' => 0],
            'north-east' => ['xoffset' => -1, 'yoffset' => 1],
        ];
        $multipleMap = [1 => 'M', 2 => 'A', 3 => 'S'];

        foreach ($offsets as $offset) {
            foreach ($multipleMap as $multiple => $match) {
                $nextX = $x + ($offset['xoffset'] * $multiple);
                $nextY = $y + ($offset['yoffset'] * $multiple);

                $char = $grid[$nextX][$nextY] ?? '';
                if ($char !== $match) {
                    continue 2;
                }
            }

            $count++;
        }

        return $count;
    }

    private function checkPositionForMas(int $x, int $y, object $context): int
    {
        $grid = $context->grid;

        if ($grid[$x][$y] !== 'A') {
            return 0;
        }

        $nw = $grid[$x-1][$y-1] ?? '.';
        $ne = $grid[$x-1][$y+1] ?? '.';
        $sw = $grid[$x+1][$y-1] ?? '.';
        $se = $grid[$x+1][$y+1] ?? '.';

        if ($nw === $se || $ne === $sw) {
            return 0;
        }

        $chars = [$nw, $ne, $sw, $se];
        sort($chars);

        if ($chars !== ['M', 'M', 'S', 'S']) {
            return 0;
        }

        return 1;
    }
}