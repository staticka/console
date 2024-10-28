<?php

namespace Staticka\Console\Scripts;

use Rougin\Blueprint\Commands\InitializeCommand;

/**
 * @package Combustor
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Init extends InitializeCommand
{
    /**
     * @var string
     */
    protected $file = 'staticka.yml';

    /**
     * Returns the source directory for the specified file.
     *
     * @return string
     */
    protected function getPlatePath()
    {
        /** @var string */
        return realpath(__DIR__ . '/../Plates');
    }

    /**
     * Returns the root directory from the package.
     *
     * @return string
     */
    protected function getRootPath()
    {
        // $root = __DIR__ . '/../../../../../';
        $root = __DIR__ . '/../../../../../../';

        $exists = file_exists($root . '/vendor/autoload.php');

        // return $exists ? $root : __DIR__ . '/../../';
        return $exists ? $root : __DIR__ . '/../../../';
    }
}
