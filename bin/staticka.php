<?php

use Staticka\Console\Builder;
use Staticka\Console\Composer;
use Staticka\Console\Creator;
use Staticka\Console\Watcher;
use Symfony\Component\Console\Application;

$global = __DIR__ . '/../../../autoload.php';

$autoload = __DIR__ . '/../vendor/autoload.php';

file_exists($global) && $autoload = $global;

require $path = realpath($autoload);

$root = dirname(dirname($path));

$composer = new Composer($root);

$data = (array) $composer->data();

list($paths, $styles) = [ $data['paths'], $data['styles'] ];

$changelog = file(__DIR__ . '/../CHANGELOG.md');

$version = substr($changelog[4], 4, 5);

$app = new Application('Staticka', $version);

$builder = new Builder($paths, $styles);

$app->add($builder->data((array) $data));

$app->add(new Creator($paths, $styles));

$app->add(new Watcher($paths, $styles));

$app->run();
