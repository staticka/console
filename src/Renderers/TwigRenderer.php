<?php

namespace Staticka\Console\Renderers;

use Staticka\Console\Locator;
use Staticka\Contracts\RendererContract;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Twig Renderer
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class TwigRenderer implements RendererContract
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @param array  $data
     */
    public function __construct($path, $data = array())
    {
        $this->data = $data;

        $this->path = $path;
    }

    /**
     * Renders a file from a specified template.
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function render($template, array $data = array())
    {
        $data = array_merge($this->data, $data);

        $pattern = $this->path . '/**.twig';

        $plates = array();

        foreach (Locator::rglob($pattern) as $file)
        {
            $index = str_replace($this->path . '/', '', $file);

            $plates[$index] = file_get_contents((string) $file);
        }

        $twig = new Environment(new ArrayLoader($plates));

        return $twig->render($template, (array) $data);
    }
}
