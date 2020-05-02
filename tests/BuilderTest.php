<?php

namespace Staticka\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Builder Test
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class BuilderTest extends TestCase
{
    /**
     * @var \Staticka\Console\Builder
     */
    protected $command;

    /**
     * @var \Staticka\Console\Creator
     */
    protected $creator;

    /**
     * @var string[]
     */
    protected $paths = [];

    /**
     * @var string[]
     */
    protected $styles = [];

    /**
     * Prepates the command instance.
     *
     * @return void
     */
    public function setUp()
    {
        $composer = new Composer(__DIR__ . '/Fixtures');

        $data = $composer->data();

        $this->styles = $data['styles'];

        $this->paths = $data['paths'];

        $this->command = new Builder($this->paths, $this->styles);

        $this->command->data((array) $data);

        $this->creator = new Creator($this->paths);
    }

    /**
     * Tests Creator::execute.
     *
     * @return void
     */
    public function testExecuteMethod()
    {
        $name = (string) 'Hello World';

        $creator = new CommandTester($this->creator);

        $creator->execute(compact('name'));

        $command = new CommandTester($this->command);

        $command->execute(array());

        $expected = $this->paths['public'] . '/hello-world/index.html';

        $this->assertFileExists($expected);
    }
}
