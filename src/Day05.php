<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day05
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput();
        [$rules, $updates] = $this->parseRulesAndUpdates($content);

        $updatesInRightOrder = array_filter($updates, fn(array $update): bool => $this->isInRightOrder($update, $rules));

        return (string) array_sum(array_map($this->findMiddlePageNumber(...), $updatesInRightOrder));

    }

    public function partTwo(): string
    {
        $content = $this->readInput();
        [$rules, $updates] = $this->parseRulesAndUpdates($content);

        $updatesNotInRightOrder = array_filter($updates, fn(array $update): bool => !$this->isInRightOrder($update, $rules));
        $correctedOrders = array_map(fn(array $update): array => $this->sortOrder($update, $rules), $updatesNotInRightOrder);

        return (string) array_sum(array_map($this->findMiddlePageNumber(...), $correctedOrders));
    }

    private function parseRulesAndUpdates(string $content): array
    {
        [$rawRules, $rawUpdates] = explode("\n\n", $content);
        $rules = array_map(static function (string $rule) {
            [$page, $requires] = explode('|', $rule);
            return [
                'before' => (int)$page,
                'after' => (int)$requires,
            ];
        }, explode("\n", $rawRules));
        $updates = array_map(
            static fn($rawUpdate) => array_map(intval(...), explode(',', $rawUpdate)),
            explode("\n", $rawUpdates)
        );
        return array($rules, $updates);
    }

    private function isInRightOrder(array $update, array $rules): bool
    {
        $updateSpecificRules = array_filter(
            $rules,
            static fn($rule): bool => in_array($rule['before'], $update, true) && in_array($rule['after'], $update, true)
        );

        $printed = [];
        foreach ($update as $page) {
            $pageSpecificRules = array_filter(
                $updateSpecificRules,
                static fn($rule): bool => $page === $rule['after']
            );
            $requires = array_column($pageSpecificRules, 'before');
            if (array_diff($requires, $printed) !== []) {
                return false;
            }

            $printed[] = $page;
        }

        return true;
    }

    private function sortOrder(array $update, array $rules): array
    {
        $updateSpecificRules = array_filter(
            $rules,
            static fn($rule): bool => in_array($rule['before'], $update, true) && in_array($rule['after'], $update, true)
        );

        $clone = $update;
        $printed = [];
        while (true) {
            foreach ($clone as $index => $page) {
                $pageSpecificRules = array_filter($updateSpecificRules, static fn($rule): bool => $page === $rule['after']);
                $requires = array_column($pageSpecificRules, 'before');
                if (array_diff($requires, $printed) !== []) {
                    continue;
                }

                $printed[] = $page;
                unset($clone[$index]);
            }

            if ($clone === []) {
                return $printed;
            }

        }
    }

    private function findMiddlePageNumber(array $update): int
    {
        $count = count($update);
        $key = intdiv($count, 2);

        return $update[$key];
    }
}