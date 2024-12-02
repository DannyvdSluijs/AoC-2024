<?php

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSets([SetList::PHP_84, SetList::CODING_STYLE])
    ->withPreparedSets(deadCode: true, codeQuality: true, earlyReturn: true)
    ->withRules([InlineConstructorDefaultToPropertyRector::class]);
