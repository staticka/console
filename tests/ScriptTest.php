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
     * @param string $name
     *
     * @return string
     */
    protected function getActualPage($name)
    {
        $path = $this->app->getPagesPath();

        $files = glob($path . '/*.md');

        $files = is_array($files) ? $files : array();

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
        $actual = file_get_contents($selected);

        return str_replace("\r\n", "\n", $actual);
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

    /**
     * @return void
     */
    public function test_passed_if_page_compiled()
    {
        $create = $this->findCommand('create');

        $data = array('name' => 'Hello world!');

        $create->execute($data);

        $test = $this->findCommand('build');

        $test->execute(array());

        $expect = $this->getTemplate('HelloWorld.html');

        $actual = $this->getActualHtml('hello-world');

        $this->assertEquals($expect, $actual);

        $this->deletePath('build');

        $this->deletePath('pages');
    }

    /**
     * @return void
     */
    public function test_passed_if_page_created()
    {
        $test = $this->findCommand('create');

        $data = array('name' => 'Hello world!');

        $test->execute($data);

        $expect = $this->getTemplate('HelloWorld.md');

        $actual = $this->getActualPage('hello-world');

        $this->assertEquals($expect, $actual);
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
            $text = '"' . $path . '" must be a directory';

            throw new \InvalidArgumentException($text);
        }

        if (substr($path, -1) != '/')
        {
            $path .= '/';
        }

        $files = glob($path . '*', GLOB_MARK);

        $files = is_array($files) ? $files : array();

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
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/Fixture';

        $this->app = new Console($path);
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function findCommand($name)
    {
        $command = $this->app->make()->find($name);

        return new CommandTester($command);
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

        $files = glob($path . '/**/*.html');

        $files = is_array($files) ? $files : array();

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
        $actual = file_get_contents($selected);

        return str_replace("\r\n", "\n", $actual);
    }
}
