<?php

namespace Staticka\Console;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ScriptTest extends Testcase
{
    /**
     * @var \Staticka\Console\Console
     */
    protected $app;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $this->app = new Console(__DIR__ . '/Fixture');
    }

    /**
     * @return void
     */
    public function test_creating_new_page()
    {
        $test = $this->findCommand('create');

        $test->execute(array('name' => 'Hello world!'));

        $expected = $this->getTemplate('HelloWorld.md');

        $actual = $this->getActualPage('hello-world');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_creating_new_page
     *
     * @return void
     */
    public function test_compiling_pages()
    {
        $test = $this->findCommand('build');

        $test->execute(array());

        $expected = $this->getTemplate('HelloWorld.html');

        $actual = $this->getActualHtml('hello-world');

        $this->assertEquals($expected, $actual);

        $this->deletePath('build');

        $this->deletePath('pages');
    }

    /**
     * @param string      $name
     * @param string|null $path
     *
     * @return void
     */
    protected function deletePath($name, $path = null)
    {
        if ($path === null)
        {
            $path = $this->app->getPagesPath();

            if ($name === 'build')
            {
                $path = $this->app->getBuildPath();
            }
        }

        if (! is_dir($path))
        {
            throw new \InvalidArgumentException('"' . $path . '" must be a directory');
        }

        if (substr($path, strlen($path) - 1, 1) != '/')
        {
            $path .= '/';
        }

        /** @var string[] */
        $files = glob($path . '*', GLOB_MARK);

        foreach ($files as $file)
        {
            if (is_dir($file))
            {
                $this->deletePath($name, $file);

                continue;
            }

            unlink($file);
        }

        rmdir($path);
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function findCommand($name)
    {
        return new CommandTester($this->app->make()->find($name));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getActualHtml($name)
    {
        $path = $this->app->getBuildPath();

        $ds = DIRECTORY_SEPARATOR;

        $path = str_replace(array('/', '\\'), $ds, $path);

        $selected = '';

        /** @var string[] */
        $files = glob($path . '/**/*.html');

        foreach ($files as $file)
        {
            $file = str_replace(array('/', '\\'), $ds, $file);

            $folder = dirname($file);

            $folder = str_replace($path, '', $folder);

            if ($folder === $ds . $name)
            {
                $selected = $file;

                break;
            }
        }

        /** @var string */
        $result = file_get_contents($selected);

        return str_replace("\r\n", "\n", $result);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getActualPage($name)
    {
        $path = $this->app->getPagesPath();

        /** @var string[] */
        $files = glob($path . '/*.md');

        $selected = '';

        foreach ($files as $file)
        {
            $base = basename($file);

            $parsed = substr($base, 15, strlen($base));

            if ($parsed === $name . '.md')
            {
                $selected = $file;

                break;
            }
        }

        /** @var string */
        $result = file_get_contents($selected);

        return str_replace("\r\n", "\n", $result);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getTemplate($name)
    {
        $path = __DIR__ . '/Fixture/Output/' . $name;

        /** @var string */
        $file = file_get_contents($path);

        return str_replace("\r\n", "\n", $file);
    }
}
