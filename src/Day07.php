<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day07
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $lines = array_map($this->parseLine(...), $lines);
        $solvable = array_filter($lines, $this->solvableForPartOne(...));

        return (string) array_reduce($solvable, static fn($carry, $line) => $carry + $line['target'], 0);
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $lines = array_map($this->parseLine(...), $lines);
        $solvable = array_filter($lines, $this->solvableForPartTwo(...));

        return (string) array_reduce($solvable, static fn($carry, $line) => $carry + $line['target'], 0);
    }

    private function parseLine(string $in): array
    {
        [$target, $stack] = explode(':', $in, 2);
        $stack = array_map(intval(...), explode(' ', trim($stack)));

        return [
            'target' => (int) $target,
            'stack' => $stack,
        ];
    }

    private function solvableForPartOne(array $line): bool
    {
        if (count($line['stack']) === 1) {
            return $line['stack'][0] === $line['target'];
        }

        $l = array_shift($line['stack']);
        $r = array_shift($line['stack']);

        $multiplication = $line;
        $addition = $line;

        array_unshift($multiplication['stack'], $l * $r);
        array_unshift($addition['stack'], $l + $r);

        return $this->solvableForPartOne($multiplication) || $this->solvableForPartOne($addition);
    }

    private function solvableForPartTwo(array $line): bool
    {
        if (count($line['stack']) === 1) {
            return $line['stack'][0] === $line['target'];
        }

        $l = array_shift($line['stack']);
        $r = array_shift($line['stack']);

        if (max($l, $r) > $line['target']) {
            return false;
        }

        $multiplication = $line;
        $addition = $line;
        $concatenation = $line;

        array_unshift($multiplication['stack'], $l * $r);
        array_unshift($addition['stack'], $l + $r);
        array_unshift($concatenation['stack'], (int) ($l . $r));

        return $this->solvableForPartTwo($multiplication) || $this->solvableForPartTwo($addition) || $this->solvableForPartTwo($concatenation);
    }
}