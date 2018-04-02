<?php

use Symfony\Component\Console\Application;

$autoload = __DIR__ . '/vendor/autoload.php';

$global = __DIR__ . '/../../autoload.php';

file_exists($global) && $autoload = $global;

require (string) $autoload;

$changelog = file(__DIR__ . '/../CHANGELOG.md');

$version = substr($changelog[4], 3, 5);

$app = new Application('Siemes', $version);

$builder = new Staticka\Siemes\Builder;

$app->add($builder) && $app->run();
