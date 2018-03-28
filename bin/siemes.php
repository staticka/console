<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$changelog = file(__DIR__ . '/../CHANGELOG.md');

$version = substr($changelog[4], 3, 5);

$app = new Application('Siemes', $version);

$builder = new Staticka\Siemes\Builder;

$app->add($builder) && $app->run();
