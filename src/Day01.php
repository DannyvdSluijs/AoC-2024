<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day01
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $fields = array_map(function (string $in) {
            $fields = explode(' ', $in);

            return [(int) $fields[0], (int) $fields[3]];
        }, $lines);

        $left = array_column($fields, 0);
        $right = array_column($fields, 1);

        sort($left);
        sort($right);
        $diff = [];


        for($x = 0, $max = count($left); $x < $max; $x++) {
            $l = $left[$x];
            $r = $right[$x];
            $diff[] = max($l, $r) - min($l, $r);
        }

        $sum = array_sum($diff);

        return (string) $sum;
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $fields = array_map(function (string $in) {
            $fields = explode(' ', $in);

            return [(int) $fields[0], (int) $fields[3]];
        }, $lines);

        $left = array_column($fields, 0);
        $right = array_column($fields, 1);
        $score = 0;

        $right = array_count_values($right);

        foreach ($left as $value) {
            $amount = $right[$value] ?? 0;
            $score += $value * $amount;
        }

        return (string) $score;
    }
}