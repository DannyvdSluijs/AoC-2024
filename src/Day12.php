<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day12
{
    use ContentReader;

    public function partOne(): string
    {
        $grid = $this->readInputAsGridOfCharacters();

        $maxX = count($grid);
        $maxY = count($grid[0]);

        $prices = [];
        $processed = [];

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                if ($processed[$x][$y] ?? false) {
                    continue;
                }

                $prices[] = $this->process($x, $y, $grid, $processed);
            }
        }

        return (string) array_sum($prices);
    }

    public function partTwo(): string
    {
        $grid = $this->readInputAsGridOfCharacters();

        $maxX = count($grid);
        $maxY = count($grid[0]);

        $prices = [];
        $processed = [];

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                if ($processed[$x][$y] ?? false) {
                    continue;
                }

                $prices[] = $this->process($x, $y, $grid, $processed, bulk: true);
            }
        }

        return (string) array_sum($prices);
    }

    private function process(int $x, int $y, array $grid, array &$processed, bool $bulk = false): int
    {
        $queue = [['x' => $x, 'y' => $y]];
        $size = 1;
        $match = $grid[$x][$y];
        $offsets = [
            'north' => ['x' => -1, 'y' => 0],
            'east' => ['x' => 0, 'y' => 1],
            'south' => ['x' => 1, 'y' => 0],
            'west' => ['x' => 0, 'y' => -1],
        ];
        $fences = 0;
        $corners = 0;

//        printf('Processing %s' . PHP_EOL, $match);

        while ($queue !== []) {
            $item = array_shift($queue);
            $itemFences = ['north' => false, 'east' => false, 'south' => false, 'west' => false];
            if ($processed[$item['x']][$item['y']] ?? false) {
//                printf('Avoiding duplicate %s: %d,%d' . PHP_EOL, $match, $item['x'], $item['y']);
                continue;
            }
            $processed[$item['x']][$item['y']] = true;

//            printf('Processing %s: %d,%d' . PHP_EOL, $match, $item['x'], $item['y']);
            foreach ($offsets as $direction => $offset) {
                $neighbourX = $item['x'] + $offset['x'];
                $neighbourY = $item['y'] + $offset['y'];
                $neighbour = $grid[$neighbourX][$neighbourY] ?? 1;

                if ($neighbour === $match) {
                    $queueItem = ['x' => $neighbourX, 'y' => $neighbourY];
                    if ($processed[$neighbourX][$neighbourY] ?? false) {
//                        printf('Avoiding earlier processed node %s: %d,%d' . PHP_EOL, $match, $neighbourX, $neighbourY);
                        continue;
                    }
                    if (in_array($queueItem, $queue, true)) {
//                        printf('Avoiding already queued node %s: %d,%d' . PHP_EOL, $match, $neighbourX, $neighbourY);
                        continue;
                    }

//                    printf(' - Found match: %d,%d' . PHP_EOL, $neighbourX, $neighbourY);
                    $queue[] = $queueItem;
                    $size++;
                    continue;
                }

                $itemFences[$direction] = true;
//                printf(' - Found fence: %s of %d,%d' . PHP_EOL, $direction, $item['x'], $item['y']);
            }

            $fences += count(array_filter($itemFences));
            if (($itemFences['north'] && $itemFences['east'])) {
                $corners++;
            }
            if ($itemFences['north'] && $itemFences['west']) {
                $corners++;
            }
            if ($itemFences['south'] && $itemFences['west']) {
                $corners++;
            }
            if ($itemFences['south'] && $itemFences['east']) {
                $corners++;
            }
            // Check for inside corner in all directions
            $insideCorderOffsets = [
                'southeast' => [
                    'south' => ['x' => 1, 'y' => 0],
                    'southeast' => ['x' => 1, 'y' => 1],
                    'east' => ['x' => 0, 'y' => 1],
                ],
                'northeast' => [
                    'north' => ['x' => -1, 'y' => 0],
                    'northeast' => ['x' => -1, 'y' => 1],
                    'east' => ['x' => 0, 'y' => 1],
                ],
                'northwest' => [
                    'north' => ['x' => -1, 'y' => 0],
                    'northwest' => ['x' => -1, 'y' => -1],
                    'west' => ['x' => 0, 'y' => -1],
                ],
                'southwest' => [
                    'south' => ['x' => 1, 'y' => 0],
                    'southwest' => ['x' => 1, 'y' => -1],
                    'west' => ['x' => 0, 'y' => -1],
                ],
            ];

            foreach ($insideCorderOffsets as $direction => $insideCorderOffset) {
                $insideCorderOffset = array_values($insideCorderOffset);
                $chars = [
                    $grid[$item['x'] + $insideCorderOffset[0]['x']][$item['y'] + $insideCorderOffset[0]['y']] ?? 1,
                    $grid[$item['x'] + $insideCorderOffset[1]['x']][$item['y'] + $insideCorderOffset[1]['y']] ?? 1,
                    $grid[$item['x'] + $insideCorderOffset[2]['x']][$item['y'] + $insideCorderOffset[2]['y']] ?? 1,
                ];

                if ($chars[0] === $match && $chars[1] !== $match && $chars[2] === $match) {
                    $corners++;
                }
            }
        }

//        printf('Price of %s: %d' . PHP_EOL . PHP_EOL, $match, $size * $fence);
        if ($bulk) {
            return $size * $corners;
        }
        return $size * $fences;
    }
}