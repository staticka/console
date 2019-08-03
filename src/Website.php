<?php

namespace Staticka\Console;

use Staticka\Content\ContentInterface;
use Zapheus\Renderer\Renderer;
use Zapheus\Renderer\RendererInterface;

/**
 * Website
 *
 * @package Console
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Website extends \Staticka\Website
{
    /**
     * Directory path of the source files.
     *
     * @var string
     */
    protected $source = '';

    /**
     * Directory path for the static HTML files.
     *
     * @var string
     */
    protected $output = '';

    /**
     * Initializes the website instance.
     *
     * @param \Zapheus\Renderer\RendererInterface|null $renderer
     * @param \Staticka\Content\ContentInterface|null  $content
     */
    public function __construct(RendererInterface $renderer = null, ContentInterface $content = null)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Pages';

        $renderer === null && $renderer = new Renderer($path);

        parent::__construct($renderer, $content);
    }

    /**
     * Locates the posts from a specified directory.
     *
     * @param  string|null $directory
     * @return self
     */
    public function locate($directory = null)
    {
        $directory === null && $directory = $this->source;

        $locator = new Locator($this->content);

        $this->pages = $locator->locate((string) $directory);

        return $this;
    }

    /**
     * Returns the output directory path.
     *
     * @return string
     */
    public function output()
    {
        return $this->output;
    }

    /**
     * Sets the value of a specified property.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->$key = $value;

        return $this;
    }

    /**
     * Returns the source directory path.
     *
     * @return string
     */
    public function source()
    {
        return $this->source;
    }
}
