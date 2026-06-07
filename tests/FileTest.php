<?php

namespace Staticka\Console;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileTest extends ScriptTest
{
    /**
     * @return void
     */
    public function test_passed_if_config_path()
    {
        $path = __DIR__ . '/Fixture/Sample/config';

        $ds = DIRECTORY_SEPARATOR;

        $expect = str_replace(array('/', '\\'), $ds, $path);

        $actual = $this->app->getConfigPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/Fixture/Sample';

        $this->app = new Console($path);
    }
}
