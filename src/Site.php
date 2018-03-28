<?php

namespace Staticka\Siemes;

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
     * @var \Staticka\Siemes\Parser
     */
    protected $parser;

    /**
     * Initializes the site instance.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->content = new MarkdownContent;

        $this->parser = new Parser;

        $this->options = array_merge($this->options, $options);

        $path = (string) $this->options['path'];

        $path === '' && $path = (string) __DIR__ . '/Pages';

        $this->renderer = new Renderer((string) $path);
    }

    /**
     * Creates a new post.
     *
     * @param  string $file
     * @param  array  $data
     * @return self
     */
    public function post($file, array $data = array())
    {
        isset($data['page']) || $data['page'] = 'default';

        $this->pages[] = new Post($file, (array) $data);

        return $this;
    }

    /**
     * Locates the posts from a specified directory.
     *
     * @param  string $directory
     * @return self
     */
    public function locate($directory)
    {
        $file = (string) $this->options['post_extension'];

        $pattern = $directory . '/*.' . $file;

        $files = $this->rglob((string) $pattern);

        foreach ((array) $files as $file) {
            $content = (string) file_get_contents($file);

            $output = $this->parser->parse($content);

            $data = $this->parse($output[1], $output[0]);

            $this->post($file, (array) $data);
        }

        return $this;
    }

    /**
     * Parses data from the retrieved YAML values.
     *
     * @param  string $content
     * @param  array  $data
     * @return array
     */
    protected function parse($content, array $data)
    {
        $data['content'] = (string) $content;

        $data['title'] = isset($data['title']) ? $data['title'] : '';

        $html = (string) $this->content()->parse($content);

        preg_match('/<h1>(.*?)<\/h1>/', $html, $matches);

        $data['title'] = isset($matches[1]) ? $matches[1] : $data['title'];

        return $data;
    }

    /**
     * Performs a recursive search in glob.
     *
     * @param  string  $pattern
     * @param  integer $flags
     * @return array
     */
    protected function rglob($pattern, $flags = 0)
    {
        $separator = DIRECTORY_SEPARATOR;

        $flag = GLOB_ONLYDIR | GLOB_NOSORT;

        $files = glob($pattern, $flags);

        $items = glob(dirname($pattern) . '/*', $flag);

        foreach ((array) $items as $item) {
            $directory = $item . $separator . basename($pattern);

            $result = $this->rglob($directory, $flags);

            $files = (array) array_merge($files, $result);
        }

        return $files;
    }
}
