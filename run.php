#!php
<?php

use Dannyvdsluijs\AdventOfCode2016;

require_once 'vendor/autoload.php';

ini_set('memory_limit','2048M');

if ($argc < 2 || $argc > 3 || !is_numeric($argv[1])) {
    print("Usage ./run.php <day> [part]\r\nPossible values for part are: 'input', 1 or 2");
    exit(255);
}

if (($argv[2] ?? '') === 'input') {
    // Take session from .session file and get the inputs
    $session = file_get_contents(sprintf('%s/.session', __DIR__));
    if ($session === false) {
        throw new RuntimeException('Unable to read from .session file');
    }
    $ch = curl_init(sprintf("https://adventofcode.com/2024/day/%d/input", $argv[1]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [sprintf('Cookie: session=%s', $session)]);
    $output = curl_exec($ch);
    file_put_contents(sprintf(__DIR__ . '/inputs/day%02d.txt', $argv[1]), $output);

    // Copy the DayXX.template and replace day number
    $content = file_get_contents(sprintf('%s/src/DayXX.template', __DIR__));
    $content = str_replace('DayXX', sprintf("Day%02d", $argv[1]), $content);
    file_put_contents(sprintf('%s/src/Day%02d.php', __DIR__, $argv[1]), $content);

    exit(0);
}

$className = sprintf("\Dannyvdsluijs\AdventOfCode2016\Day%02d", $argv[1]);
$object = new $className();
$part = (int) ($argv[2] ?? 1);

$time_start = microtime(true);
$answer = match($part) {
    1 => $object->partOne(),
    2 => $object->partTwo(),
};
$time_end = microtime(true);

printf("The correct answer for day %d part %d is: %s (%f sec)\r\n", $argv[1], $part, $answer, $time_end - $time_start);