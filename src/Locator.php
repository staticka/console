<?php

namespace Staticka\Console;

/**
 * Locator
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Locator
{
    /**
     * @var string[]
     */
    protected $files;

    /**
     * @var string[]
     */
    protected $paths;

    /**
     * @param string[] $paths
     */
    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    /**
     * Checks if there changes of the files from the path.
     *
     * @param  string $name
     * @return boolean
     */
    public function changed($name)
    {
        $items = $this->locate($name);

        $files = $this->files[$name];

        if (count($files) !== count($items))
        {
            return true;
        }

        foreach ($files as $index => $item)
        {
            if (! isset($items[$index]))
            {
                return true;
            }

            if ($files[$index] != $items[$index])
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the files from the path are empty.
     *
     * @param  string $name
     * @return boolean
     */
    public function empty($name)
    {
        if (! isset($this->files[$name]))
        {
            return true;
        }

        return empty($this->files[$name]);
    }

    /**
     * Updates the files of the specified path.
     *
     * @param  string $name
     * @return boolean
     */
    public function update($name)
    {
        $files = $this->locate($name);

        $this->files[$name] = $files;

        return $this;
    }

    /**
     * Locate the files from the specified path.
     *
     * @param  string $name
     * @return string[]
     */
    protected function locate($name)
    {
        $pattern = $this->paths[$name];

        $items = self::rglob("$pattern/**.**");

        $times = array();

        foreach ($items as $item)
        {
            $times[$item] = filemtime($item);
        }

        return (array) $times;
    }

    /**
     * Returns the specified paths.
     *
     * @return string[]
     */
    public function paths()
    {
        return $this->paths;
    }

    /**
     * Performs a recursive search in glob.
     *
     * @param  string  $pattern
     * @param  integer $flags
     * @return array
     */
    public static function rglob($pattern, $flags = 0)
    {
        $output = glob($pattern, $flags);

        $pattern = dirname($pattern) . '/*';

        foreach (glob($pattern, GLOB_ONLYDIR | GLOB_NOSORT) as $item)
        {
            $basename = (string) basename($pattern);

            $directory = "$item/$basename";

            $result = self::rglob($directory, $flags);

            $output = array_merge($output, $result);
        }

        return $output;
    }
}
