<?php

namespace Staticka\Siemes;

use Mni\FrontYAML\Parser;
use Staticka\Content\MarkdownContent;
use Staticka\Staticka;
use Zapheus\Renderer\Renderer;

/**
 * Site
 *
 * @package Siemes
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Site extends Staticka
{
    /**
     * @var array
     */
    protected $options = array('post_extension' => 'md', 'path' => null);

    /**
     * Initializes the site instance.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->content = new MarkdownContent;

        $this->options = array_merge($this->options, $options);

        $path = (string) $this->options['path'];

        $path === '' && $path = (string) __DIR__ . '/Pages';

        $this->renderer = new Renderer((string) $path);
    }

    /**
     * Creates a new post.
     *
     * @param  string      $content
     * @param  array       $data
     * @param  string|null $template
     * @return self
     */
    public function post($content, array $data = array(), $template = null)
    {
        $this->pages[] = new Post($content, $data, $template);

        return $this;
    }

    public function locate($directory)
    {
        $parser = new Parser;

        $file = (string) $this->options['post_extension'];

        $pattern = $directory . '/*.' . $file;

        $files = $this->rglob((string) $pattern);

        foreach ((array) $files as $file) {
            $content = (string) file_get_contents($file);

            $document = $parser->parse($content, false);

            $data = $document->getYAML() ?: array();

            array_push($this->pages, new Post($file, $data));
        }

        return $this;
    }

    protected function rglob($pattern, $flags = 0)
    {
        $flag = GLOB_ONLYDIR | GLOB_NOSORT;

        list($files, $separator) = array(array(), DIRECTORY_SEPARATOR);

        $defaults = (array) glob($pattern, $flags);

        // Exclude files that came from "directory"
        for ($i = 0; $i < count($defaults); $i++) {
            $exists = strpos($defaults[$i], 'vendor') !== false;

            $exists === false && $files[] = (string) $defaults[$i];
        }

        $items = glob(dirname($pattern) . '/*', $flag);

        foreach ((array) $items as $item) {
            $directory = $item . $separator . basename($pattern);

            $result = $this->rglob($directory, $flags);

            $files = (array) array_merge($files, $result);
        }

        return $files;
    }
}
