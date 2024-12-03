<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day02
{
    use ContentReader;

    public function partOne(): string
    {
        $reports = $this->readInputAsGridOfNumbers();

        $safe = array_filter($reports, $this->isSafe(...));

        return (string) count($safe);
    }

    public function partTwo(): string
    {
        $reports = $this->readInputAsGridOfNumbers();

        $safe = array_filter($reports, $this->isSafe(...));
        $unsafe = array_filter($reports, fn ($r) => !$this->isSafe($r));

        foreach ($unsafe as $report) {
            foreach ($report as $key => $value) {
                $clone = $report;
                unset($clone[$key]);
                if ($this->isSafe($clone)) {
                    $safe[] = $report;
                    continue 2;
                }
            }
        }

        return (string) count($safe);
    }

    private function isSafe(array $report): bool
    {
        $report = array_values($report);
        $sortedReport = $report;
        sort($sortedReport);
        if ($report !== $sortedReport && $report !== array_reverse($sortedReport)) {
            return false;
        }

        for ($x = 0, $max = count($report); $x < $max - 1; $x++) {
            $diff = abs($report[$x] -$report[$x+1]);
            if ($diff > 3 || $diff === 0) {
                return false;
            }
        }

        return true;
    }
}