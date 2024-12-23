<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day06
{
    use ContentReader;

    public function partOne(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        [$pos, $heading, $visited, $blockers] = $this->parseGrid($grid);

        $visited = $this->findPositions($grid, $pos, $heading, $visited, $blockers);

        $visited = array_map(static fn (array $in): array => ['x' => $in['x'], 'y' => $in['y']], $visited);

        return (string) count(array_unique($visited, SORT_REGULAR));
    }

    public function partTwo(): string
    {
        $grid = $this->readInputAsGridOfCharacters();
        [$pos, $heading, $visited, $blockers] = $this->parseGrid($grid);

        $visited = $this->findPositions($grid, $pos, $heading, $visited, $blockers);
        $visited = array_values(array_map(static fn (array $in): array => ['x' => $in['x'], 'y' => $in['y']], $visited));

        $loopPositions = [];
        foreach ($visited as $index => $position) {
            $clone = $grid;
            $clone[$position['x']][$position['y']] = '#';

            [$pos, $heading, $newlyVisited, $blockers] = $this->parseGrid($clone);

            try {
                $this->findPositions($grid, $pos, $heading, $newlyVisited, $blockers);
            } catch (\Exception) {
                $loopPositions[] = $position;
            }
        }

        return (string) count(array_unique($loopPositions, SORT_REGULAR));
    }

    private function parseGrid(array $grid): array
    {
        $maxX = count($grid);
        $maxY = count($grid[0]);
        $pos = ['x' => 0, 'y' => 0];
        $heading = 'N';
        $blockers = [
            'x' => array_fill_keys(range(0, $maxX), []),
            'y' => array_fill_keys(range(0, $maxY), []),
        ];
        $visited = [];

        for ($x = 0; $x < $maxX; $x++) {
            for ($y = 0; $y < $maxY; $y++) {
                $char = $grid[$x][$y];

                switch ($char) {
                    case '^':
                        $pos = ['x' => $x, 'y' => $y];
                        $visited[] = ['x' => $pos['x'], 'y' => $pos['y'], 'heading' => $heading];
                        break;
                    case '#':
                        $blockers['x'][$y][] = $x;
                        $blockers['y'][$x][] = $y;
                }
            }
        }

        return [$pos, $heading, $visited, $blockers];
    }

    private function findPositions(array $grid, array $pos, string $heading, array $visited, array $blockers): array
    {
        while (true) {
            if (is_null($grid[$pos['x']][$pos['y']] ?? null)) {
                break;
            }
            switch ($heading) {
                case 'N':
                    if (in_array($pos['x'] - 1, $blockers['x'][$pos['y']], true)) {
                        $heading = 'E';
                        break;
                    }
                    $pos['x']--;
                    break;
                case 'E':
                    if (in_array($pos['y'] + 1, $blockers['y'][$pos['x']], true)) {
                        $heading = 'S';
                        break;
                    }
                    $pos['y']++;
                    break;
                case 'S':
                    if (in_array($pos['x'] + 1, $blockers['x'][$pos['y']], true)) {
                        $heading = 'W';
                        break;
                    }
                    $pos['x']++;
                    break;
                case 'W':
                    if (in_array($pos['y'] - 1, $blockers['y'][$pos['x']], true)) {
                        $heading = 'N';
                        break;
                    }
                    $pos['y']--;
                    break;
            }

            $visitedItem = ['x' => $pos['x'], 'y' => $pos['y'], 'heading' => $heading];
            if (in_array($visitedItem, $visited, true)) {
                throw new \Exception('Loop detected');
            }
            $visited[] = $visitedItem;
        }

        array_pop($visited);

        return $visited;
    }
}