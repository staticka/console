<?php

namespace Staticka\Siemes;

use Staticka\Content\ContentInterface;
use Staticka\Page;

/**
 * Locator
 *
 * @package Siemes
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Locator
{
    /**
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * @var \Staticka\Siemes\Parser
     */
    protected $parser;

    /**
     * Initializes the content instance.
     *
     * @param \Staticka\Content\ContentInterface $content
     */
    public function __construct(ContentInterface $content)
    {
        $this->content = $content;

        $this->parser = new Parser;
    }

    /**
     * Locates the posts from a specified directory.
     *
     * @param  string $directory
     * @return self
     */
    public function locate($directory)
    {
        $extension = $this->content->extension();

        $pattern = $directory . '/*.' . $extension;

        $files = $this->rglob((string) $pattern);

        foreach ((array) $files as $file) {
            $content = (string) file_get_contents($file);

            $output = (array) $this->parser->parse($content);

            $data = $this->parse($output[1], $output[0]);

            isset($data['layout']) || $data['layout'] = 'default';

            $this->pages[] = $this->page($file, (array) $data);
        }

        return $this->pages;
    }

    /**
     * Creates a new page.
     *
     * @param  string $file
     * @param  array  $data
     * @return \Staticka\Page
     */
    protected function page($file, array $data = array())
    {
        $extension = (string) '.' . $this->content->extension();

        $uri = str_replace($extension, '', basename($file));

        isset($data['permalink']) && $uri = $data['permalink'];

        $uri = $uri[0] !== '/' ? '/' . $uri : (string) $uri;

        list($content, $page) = array($data['content'], $data['layout']);

        return new Page($uri, $content, $page, (array) $data);
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

        $html = (string) $this->content->parse($content);

        preg_match('/<h1>(.*?)<\/h1>/', $html, $matches);

        isset($matches[1]) && $data['title'] = $matches[1];

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