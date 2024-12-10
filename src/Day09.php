<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day09
{
    use ContentReader;

    public function partOne(): string
    {
        $map = $this->readInputAsListOfNumbers();
        $fileIdSizeMap = array_values(array_filter($map, static fn (int $key) => $key % 2 === 0, ARRAY_FILTER_USE_KEY));
        $emptyBlockSizes = array_values(array_filter($map, static fn (int $key) => $key % 2 === 1, ARRAY_FILTER_USE_KEY));

        $results = 0;
        $position = 0;
        $frontIndex = 0;
        $rearIndex = count($fileIdSizeMap) - 1;
        while (true) {
            if ($frontIndex > $rearIndex) {
                break;
            }
            for ($x = 0; $x < $fileIdSizeMap[$frontIndex]; $x++) {
                $results += $position * $frontIndex;
                $position++;
            }
            $frontIndex++;

            if ($frontIndex > $rearIndex) {
                break;
            }

            $nextEmptyBlockSize = array_shift($emptyBlockSizes);
            for ($x = 0; $x < $nextEmptyBlockSize; $x++) {
                $results += $position * $rearIndex;
                $fileIdSizeMap[$rearIndex]--;

                if ($fileIdSizeMap[$rearIndex] === 0) {
                    $rearIndex--;
                }
                $position++;
            }
        }

        return (string) $results;
    }

    public function partTwo(): string
    {
        $blocks = $this->readInputAsListOfNumbers();
        array_walk($blocks, static function (int $size, int $key) use (&$blocks){
            if ($key % 2 === 1) {
                // Empty blocks
                $blocks[$key] = [
                    'key' => $key,
                    'size' => $size,
                    'left' => $size,
                    'blocks' => []
                ];
                return;
            }

            $blocks[$key] = [
                'key' => $key,
                'size' => $size,
                'left' => 0,
                'blocks' => array_fill(0, $size, intdiv($key, 2))
            ];
        });
        $blockIndex = count($blocks) - 1;

        while (true) {
            if ($blockIndex < 0) {
                break;
            }

            $size = $blocks[$blockIndex]['size'];
            $fileId = $blocks[$blockIndex]['blocks'][0];
            $key = array_find_key($blocks, static fn(array $block) => $block['left'] >= $size && $block['key'] < $blockIndex);
            // No match
            if (\is_null($key)) {
                $blockIndex -= 2;
                continue;
            }

            // Match, so move
            $blocks[$key]['left'] -= $size;
            for ($x = 0; $x < $size; $x++) {
                $blocks[$key]['blocks'][] = $fileId;
            }
            $blocks[$blockIndex] = ['key' => $blockIndex, 'size' => $size, 'left' => $size, 'blocks' => []];
            $blockIndex -= 2;
        }

        $position = 0;
        $result = 0;
        foreach ($blocks as $block) {
            for ($x = 0; $x < $block['size']; $x++) {
                $blockIndex = $block['blocks'][$x] ?? 0;
                $result += $blockIndex * $position;
                $position++;
            }
        }

        return (string) $result;
    }
}