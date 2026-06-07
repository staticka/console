<?php

namespace Staticka\Console;

/**
 * @runTestsInSeparateProcesses
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AppTest extends Testcase
{
    /**
     * @return void
     */
    public function test_passed_if_config_file()
    {
        $app = new Console(__DIR__ . '/Fixture');

        $expect = 'Rougin\Blueprint\Wrapper';

        $actual = $app->make()->find('create');

        $this->assertInstanceOf($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_no_config_file()
    {
        $app = new Console(__DIR__ . '/../');

        $expect = 'Rougin\Blueprint\Wrapper';

        $actual = $app->make()->find('initialize');

        $this->assertInstanceOf($expect, $actual);
    }
}
