<?php

namespace Staticka\Console;

use Staticka\Content\ContentInterface;

/**
 * Locator
 *
 * @package Console
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Locator
{
    /**
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * Initializes the content instance.
     *
     * @param \Staticka\Content\ContentInterface $content
     */
    public function __construct(ContentInterface $content)
    {
        $this->content = $content;
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

        $this->directory = (string) $directory;

        $pattern = $directory . '/*.' . $extension;

        $files = $this->rglob((string) $pattern);

        foreach ((array) $files as $file) {
            $data = array('layout' => (string) 'default');

            $this->pages[] = $this->page($file, $data);
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
        $filename = str_replace($this->directory, '', $file);

        $filename = str_replace('\\', '/', $filename);

        $extension = '.' . $this->content->extension();

        $uri = strtolower(str_replace($extension, '', $filename));

        $uri = str_replace('/index', '', $uri);

        $data['permalink'] = $uri[0] !== '/' ? '/' . $uri : $uri;

        return new \Staticka\Page($file, (array) $data);
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