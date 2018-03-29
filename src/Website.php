<?php

namespace Staticka\Siemes;

use Staticka\Content\ContentInterface;
use Zapheus\Renderer\Renderer;
use Zapheus\Renderer\RendererInterface;

/**
 * Website
 *
 * @package Siemes
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Website extends \Staticka\Staticka
{
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
     * @param  string $directory
     * @return self
     */
    public function locate($directory)
    {
        $locator = new Locator($this->content);

        $this->pages = $locator->locate($directory);

        return $this;
    }
}
