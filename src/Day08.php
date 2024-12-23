<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day08
{
    use ContentReader;

    public function partOne(): string
    {
        $grid = $this->readInputAsGridOfCharacters();

        $antinodes = $this->computeUniqueAntiNodes($grid);
        return (string) $antinodes;
    }

    public function partTwo(): string
    {
        $grid = $this->readInputAsGridOfCharacters();

        $antinodes = $this->computeUniqueAntiNodes($grid, withRepeat: true);
        return (string) $antinodes;
    }

    public function computeUniqueAntiNodes(array $grid, bool $withRepeat = false): int
    {
        $gridMaxX = count($grid);
        $gridMaxY = count($grid[0]);

        $antennas = [];

        foreach ($grid as $x => $row) {
            foreach ($row as $y => $char) {
                if ($char === '.' || $char === '#') {
                    continue;
                }

                $antennas[$char] ??= [];
                $antennas[$char][] = ['x' => $x, 'y' => $y];
            }
        }

        $antiNodes = [];
        foreach ($antennas as $char => $positions) {
            $antiNodes[$char] ??= [];
            if (count($positions) < 2) {
                continue;
            }
            foreach ($positions as $keyOne => $positionOne) {
                if ($withRepeat) {
                    $antiNodes[$char][] =  ['x' => $positionOne['x'], 'y' => $positionOne['y']];
                }
                foreach ($positions as $keyTwo => $positionTwo) {
                    if ($keyOne >= $keyTwo) {
                        continue;
                    }

                    $xDiff = abs($positionOne['x'] - $positionTwo['x']);
                    $yDiff = abs($positionOne['y'] - $positionTwo['y']);

                    // Position one is West of position two
                    if ($positionOne['x'] === $positionTwo['x'] && $positionOne['y'] < $positionTwo['y']) {
                        $next = ['x' => $positionOne['x'], 'y' => $positionOne['y'] - $yDiff];
                        while ($next['y'] >= 0) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'], 'y' => $next['y'] - $yDiff];
                        }

                        $next = ['x' => $positionTwo['x'], 'y' => $positionTwo['y'] + $yDiff];
                        while ($next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'], 'y' => $next['y'] + $yDiff];
                        }
                    }

                    // Position one is NorthWest of positions two:
                    if ($positionOne['x'] < $positionTwo['x'] && $positionOne['y'] < $positionTwo['y']) {
                        $next = ['x' => $positionOne['x'] - $xDiff, 'y' => $positionOne['y'] - $yDiff];
                        while ($next['x'] >= 0 && $next['y'] >= 0) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y'] - $yDiff];
                        }

                        $next = ['x' => $positionTwo['x'] + $xDiff, 'y' => $positionTwo['y'] + $yDiff];
                        while ($next['x'] < $gridMaxX && $next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y'] + $yDiff];
                        }
                    }

                    // Position one is North of position two
                    if ($positionOne['x'] < $positionTwo['x'] && $positionOne['y'] === $positionTwo['y']) {
                        $next = ['x' => $positionOne['x'] - $xDiff, 'y' => $positionOne['y']];
                        while ($next['x'] >= 0) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y']];
                        }

                        $next = ['x' => $positionTwo['x'] + $xDiff, 'y' => $positionTwo['y']];
                        while ($next['x'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;

                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y']];
                        }
                    }

                    // Position one is NorthEast of positions two:
                    if ($positionOne['x'] < $positionTwo['x'] && $positionOne['y'] > $positionTwo['y']) {
                        $next = ['x' => $positionOne['x'] - $xDiff, 'y' => $positionOne['y'] + $yDiff];
                        while ($next['x'] >= 0 && $next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y'] + $yDiff];
                        }

                        $next = ['x' => $positionTwo['x'] + $xDiff, 'y' => $positionTwo['y'] - $yDiff];
                        while ($next['x'] < $gridMaxX && $next['y'] >= 0) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y'] - $yDiff];
                        }
                    }

                    // Position one is East of position two
                    if ($positionOne['x'] === $positionTwo['x'] && $positionOne['y'] > $positionTwo['y']) {
                        $next = ['x' => $positionTwo['x'], 'y' => $positionTwo['y'] - $yDiff];
                        while ($next['y'] >= 0) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'], 'y' => $next['y'] - $yDiff];
                        }

                        $next = ['x' => $positionOne['x'], 'y' => $positionOne['y'] + $yDiff];
                        while ($next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'], 'y' => $next['y'] + $yDiff];
                        }
                    }

                    // Position one is SouthEast of positions two:
                    if ($positionOne['x'] > $positionTwo['x'] && $positionOne['y'] > $positionTwo['y']) {
                        $next = ['x' => $positionTwo['x'] - $xDiff, 'y' => $positionTwo['y'] - $yDiff];
                        while ($next['x'] >= 0 && $next['y'] >= 0) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y'] - $yDiff];
                        }

                        $next = ['x' => $positionOne['x'] + $xDiff, 'y' => $positionOne['y'] + $yDiff];
                        while ($next['x'] < $gridMaxX && $next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y'] + $yDiff];
                        }
                    }

                    // Position one is South of position two
                    if ($positionOne['x'] > $positionTwo['x'] && $positionOne['y'] === $positionTwo['y']) {
                        $next = ['x' => $positionTwo['x'] - $xDiff, 'y' => $positionTwo['y']];
                        while ($next['x'] >= 0) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y']];
                        }

                        $next = ['x' => $positionOne['x'] + $xDiff, 'y' => $positionOne['y']];
                        while ($next['x'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y'] + $yDiff];
                        }
                    }
                    // Position one is SouthWest of positions two:
                    if ($positionOne['x'] > $positionTwo['x'] && $positionOne['y'] < $positionTwo['y']) {
                        $next = ['x' => $positionTwo['x'] - $xDiff, 'y' => $positionTwo['y'] + $yDiff];
                        while ($next['x'] >= 0 && $next['y'] < $gridMaxY) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] - $xDiff, 'y' => $next['y'] + $yDiff];
                        }

                        $next = ['x' => $positionOne['x'] + $xDiff, 'y' => $positionOne['y'] - $yDiff];
                        while ($next['x'] < $gridMaxX && $next['y'] >= 0) {
                            $antiNodes[$char][] = $next;
                            if ($withRepeat === false) {
                                break;
                            }
                            $next = ['x' => $next['x'] + $xDiff, 'y' => $next['y'] - $yDiff];
                        }
                    }
                }
            }
        }
        return count(array_unique(array_merge(...array_values($antiNodes)), SORT_REGULAR));
    }
}