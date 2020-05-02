<?php

namespace Staticka\Console\Factories;

use Staticka\Console\Renderers\SassRenderer;

/**
 * SASS Factory
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SassFactory
{
    /**
     * @var string[]
     */
    protected $styles;

    /**
     * @param array $styles
     */
    public function __construct($styles = array())
    {
        $this->styles = $styles;
    }

    /**
     * Creates a new SassRenderer instance.
     *
     * @param  string[] $styles
     * @param  string   $public
     * @return \Staticka\Console\Renderers\SassRenderer
     */
    public function make($styles, $public)
    {
        $files = array();

        foreach ($styles as $sass => $css)
        {
            $sass = "{$this->styles}/$sass";

            $files[$sass] = "$public/$css";
        }

        return new SassRenderer($this->styles, $files);
    }
}
