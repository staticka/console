<?php

namespace Staticka\Siemes;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Builder Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Staticka\Siemes\Builder
     */
    protected $command;

    /**
     * Sets up the builder command instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->command = new Builder;
    }

    /**
     * Tests Builder::execute.
     *
     * @return void
     */
    public function testExecuteMethod()
    {
        $command = new CommandTester($this->command);

        $options = array('--output' => __DIR__ . '/Build');

        $options['--website'] = __DIR__ . '/Fixture/Website.php';

        $command->execute((array) $options);

        $expected = __DIR__ . '/Build/content/index.html';

        $this->assertFileExists($expected);
    }
}
