<?php

namespace Staticka\Console;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Creator Test
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Staticka\Console\Creator
     */
    protected $command;

    /**
     * @var string[]
     */
    protected $paths =
    [
        'pages' => __DIR__ . '/Fixtures/Pages',
    ];

    /**
     * Prepates the command instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->command = new Creator($this->paths);
    }

    /**
     * Tests Creator::execute.
     *
     * @return void
     */
    public function testExecuteMethod()
    {
        $command = new CommandTester($this->command);

        $options['name'] = 'Hello World';

        $options['--prefix'] = '001';

        $command->execute((array) $options);

        $expected = $this->paths['pages'] . '/001_hello_world.md';

        $this->assertFileExists($expected);
    }
}
