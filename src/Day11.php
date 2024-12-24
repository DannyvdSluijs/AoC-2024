<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day11
{
    use ContentReader;

    public function partOne(): string
    {
        $stones = array_count_values(array_map(intval(...), explode(' ', $this->readInput())));
        $stones = $this->blink($stones, blinkAmount: 25);

        return (string) array_sum($stones);
    }

    public function partTwo(): string
    {
        $stones = array_count_values(array_map(intval(...), explode(' ', $this->readInput())));
        $stones = $this->blink($stones, blinkAmount: 75);

        return (string) array_sum($stones);
    }

    private function blink(array $stones, int $blinkAmount): array
    {
        for ($blink = 0; $blink < $blinkAmount; $blink++) {
            $clone = $stones;
            foreach ($clone as $value => $amount) {
                $length = strlen((string)$value);

                if ($value === 0) {
                    $stones[1] ??= 0;
                    $stones[1] += $amount;
                    $stones[0] -= $amount;
                    continue;
                }

                if ($length % 2 === 0) {
                    $left = (int)substr((string)$value, 0, intdiv($length, 2));
                    $stones[$left] ??= 0;
                    $stones[$left] += $amount;
                    $right = (int)substr((string)$value, intdiv($length, 2));
                    $stones[$right] ??= 0;
                    $stones[$right] += $amount;
                    $stones[$value] -= $amount;
                    continue;
                }

                $new = $value * 2024;
                $stones[$new] ??= 0;
                $stones[$new] += $amount;
                $stones[$value] -= $amount;
            }
        }

        return $stones;
    }
}