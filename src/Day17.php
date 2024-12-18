<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2024;

use Dannyvdsluijs\AdventOfCode2024\Concerns\ContentReader;

class Day17
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsLines();
        $program = array_map(intval(...), explode(',', substr($content[4], 9)));
        $register = [
            'A' => $this->parseRegister($content[0]),
            'B' =>  $this->parseRegister($content[1]),
            'C' =>  $this->parseRegister($content[2]),
        ];

        $output = $this->run($program, $register);

        return implode(',', $output);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLines();
        $program = array_map(intval(...), explode(',', substr($content[4], 9)));
        $basicRegister = [
            'A' => 0,
            'B' =>  $this->parseRegister($content[1]),
            'C' =>  $this->parseRegister($content[2]),
        ];
        $reverseProgram = array_reverse($program);
        foreach ($reverseProgram as $index => $value) {
            $aCandidate = $basicRegister['A'] * 8;
            while (true) {
                $register = $basicRegister;
                $register['A'] = $aCandidate;
                $output = $this->run($program, $register);

                if ($output === array_reverse(array_slice($reverseProgram, 0, $index + 1))) {
                    $basicRegister['A'] = $aCandidate;
                    break;
                }
                $aCandidate++;
            }
        }

        return (string) $basicRegister['A'];
    }

    private function parseRegister(string $in): int
    {
        [, $register] = explode(': ', $in);

        return (int) $register;
    }

    private function run(array $program, array &$register): array
    {
        $instructionPointer = 0;
        $output = [];
        $outputKey = 0;

        while (true) {
            if (is_null($program[$instructionPointer] ?? null)) {
                break;
            }

            $jumped = false;
            $opcode = $program[$instructionPointer];
            $operand = match ($program[$instructionPointer + 1]) {
                0, 1, 2, 3 => $program[$instructionPointer + 1],
                4 => $register['A'],
                5 => $register['B'],
                6 => $register['C'],
            };

            switch ($opcode) {
                case 0:
                    $register['A'] = intdiv($register['A'], 2 ** $operand);
                    break;
                case 1:
                    $register['B'] ^= $operand;
                    break;
                case 2:
                    $register['B'] = $operand % 8;
                    break;
                case 3:
                    if ($register['A'] !== 0) {
                        $instructionPointer = $operand;
                        $jumped = true;
                    }
                    break;
                case 4:
                    $register['B'] ^= $register['C'];
                    break;
                case 5:
                    $output[$outputKey] = $operand % 8;
                    $outputKey++;
                    break;
                case 6:
                    $register['B'] = intdiv($register['A'], 2 ** $operand);
                    break;
                case 7:
                    $register['C'] = intdiv($register['A'], 2 ** $operand);
                    break;
            }

            if (!$jumped) {
                $instructionPointer += 2;
            }
        }

        return $output;
    }
}