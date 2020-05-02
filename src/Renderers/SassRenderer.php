<?php

namespace Staticka\Console\Renderers;

use ScssPhp\ScssPhp\Compiler;

/**
 * SASS Renderer
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SassRenderer
{
    /**
     * @var string[]
     */
    protected $files;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string   $path
     * @param string[] $files
     */
    public function __construct($path, $files = array())
    {
        $scss = new Compiler;

        $scss->addImportPath($path);

        $formatter = 'ScssPhp\ScssPhp\Formatter\Crunched';

        $scss->setFormatter($formatter);

        $this->scss = $scss;

        $this->files = $files;
    }

    /**
     * Compiles the SASS files into CSS files.
     *
     * @param  string[] $styles
     * @return void
     */
    public function build($styles = array())
    {
        $files = array_merge($this->files, $styles);

        foreach ($files as $old => $new)
        {
            $css = $this->render((string) $old);

            if (! file_exists(dirname($new)))
            {
                mkdir(dirname($new), 0755, true);
            }

            file_put_contents($new, $css);
        }
    }

    /**
     * Render a specified SASS file.
     *
     * @param  string $sass
     * @return string
     */
    public function render($sass)
    {
        if (file_exists($sass))
        {
            $sass = file_get_contents($sass);
        }

        return $this->scss->compile($sass);
    }
}
