<?php

namespace Staticka\Console\Factories;

/**
 * File Factory
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FileFactory
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $plate = __DIR__ . '/../Templates/Page.md';

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $path
     * @param string $type
     */
    public function __construct($path, $type = 'md')
    {
        $this->path = $path;

        $this->type = $type;
    }

    /**
     * Creates a new file based on the given name.
     *
     * @param  string      $name
     * @param  string|null $prefix
     * @return string
     */
    public function make($name, $prefix = null)
    {
        $prefix = $prefix ? $prefix : date('YmdHis');

        $plate = (string) file_get_contents($this->plate);

        list($file, $plate) = $this->parse($name, $plate);

        $file = (string) $prefix . '_' . $file;

        $file = "{$this->path}/$file.{$this->type}";

        if (! file_exists(dirname($file)))
        {
            mkdir(dirname($file), 0755, true);
        }

        file_put_contents($file, $plate);

        return basename(realpath($file));
    }

    /**
     * Parses the contents of the template page.
     *
     * @param  string $name
     * @param  string $plate
     * @return string
     */
    protected function parse($name, $plate)
    {
        $plate = str_replace('$NAME', $name, $plate);

        $link = str_replace(' ', '-', strtolower($name));

        $plate = str_replace('$LINK', "/$link", $plate);

        $file = str_replace(' ', '_', strtolower($name));

        return array($file, $plate);
    }
}
