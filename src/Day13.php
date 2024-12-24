<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day13
{
    use ContentReader;

    public function partOne(): string
    {
        $machines = explode("\n\n", $this->readInput());
        $prizes = [];

        foreach ($machines as $machine) {
            $prizes[] = $this->solveMachine($machine);
        }

        $prizes = array_filter($prizes);

        return (string) array_sum($prizes);
    }

    public function partTwo(): string
    {
        $machines = explode("\n\n", $this->readInput());
        $prizes = [];

        foreach ($machines as $machine) {
            $prizes[] = $this->solveMachine($machine, partTwo: true);
        }

        $prizes = array_filter($prizes);

        return (string) array_sum($prizes);
    }

    private function solveMachine(string $machine, bool $partTwo = false): ?int
    {
        [$a, $b, $prize] = explode("\n", $machine);

        [$aX, $aY] = $this->parseButtonOrPrize($a);
        [$bX, $bY] = $this->parseButtonOrPrize($b);
        [$prizeX, $prizeY] = $this->parseButtonOrPrize($prize);

        if ($partTwo) {
            $prizeX += 10_000_000_000_000;
            $prizeY += 10_000_000_000_000;
        }

        $leftSideEquation = $prizeY * $aX - $aY * $prizeX;
        $rightSideEquation = -1 * $aY * $bX + $bY * $aX;
        $b = $leftSideEquation / $rightSideEquation;

        $leftSideEquation = $prizeX - $bX * $b;
        $rightSideEquation = $aX;
        $a = $leftSideEquation / $rightSideEquation;

        if (is_float($a) || is_float($b)) {
            return null;
        }

        return $a * 3 + $b;
    }

    private function parseButtonOrPrize(string $input): array
    {
        $input = str_replace(['Button A: X+', 'Button B: X+', 'Prize: X='], '', $input);
        [$x, $y] = explode(",", $input);
        $x = (int) $x;
        $y = (int) substr(trim($y), 2);

        return [$x, $y];
    }
}