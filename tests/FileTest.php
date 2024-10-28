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
    public function doSetUp()
    {
        $this->app = new Console(__DIR__ . '/Fixture/Sample');
    }

    /**
     * @depends test_creating_new_page
     *
     * @return void
     */
    public function test_config_path()
    {
        $expected = __DIR__ . '/Fixture/Sample/config';

        $ds = DIRECTORY_SEPARATOR;

        $expected = str_replace(array('/', '\\'), $ds, $expected);

        $actual = $this->app->getConfigPath();

        $this->assertEquals($expected, $actual);
    }
}
