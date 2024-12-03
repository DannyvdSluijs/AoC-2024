<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day03
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput();

        $pointer = 0;
        $sum = 0;
        while (true) {
            $position = strpos($content, 'mul(', $pointer);
            if ($position === false) {
                break;
            }

            $position += 4;
            $pointer = $position;

            $sum += $this->calculateMulFromPosition($content, $position);
        }

        return (string) $sum;
    }

    public function partTwo(): string
    {
        $content = $this->readInput();
        $content = $this->cleanupDontsAndDos($content);

        $pointer = 0;
        $sum = 0;

        while (true) {
            $position = strpos($content, 'mul(', $pointer);
            if ($position === false) {
                break;
            }

            $position += 4;
            $pointer = $position;

            $sum += $this->calculateMulFromPosition($content, $position);
        }

        return (string) $sum;
    }

    private function calculateMulFromPosition(string $content, int $position): int
    {
        $stack = '';
        $left = null;
        while (true) {
            $next = $content[$position];
            switch (true) {
                case is_numeric($next):
                    $stack .= $next;
                    break;
                case $next === ',':
                    $left = (int) $stack;
                    $stack = '';
                    break;
                case $next === ')':
                    $right = (int) $stack;
                    break 2;
                default:
                    return 0;
            }

            $position++;
        }

        return $left * $right;
    }

    private function cleanupDontsAndDos(string $content): string
    {
        $pointer = 0;
        $exclude = [];
        while (true) {
            $dont = strpos($content, "don't()", $pointer);
            if ($dont === false) {
                break;
            }

            $pointer = $dont + 7;

            $do = strpos($content, "do()", $pointer);
            if ($do === false) {
                $do = strlen($content);
            }

            $pointer = $do + 4;

            $exclude[] = [$dont, $do];
        }

        $exclude = array_unique($exclude, SORT_REGULAR );
        $exclude = array_reverse($exclude);

        foreach ($exclude as $e) {
            $content = substr($content, 0, $e[0]) . substr($content, $e[1]);
        }

        return $content;
    }
}