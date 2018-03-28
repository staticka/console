<?php

namespace Staticka\Siemes;

use Staticka\Page;

/**
 * Post
 *
 * @package Siemes
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Post extends Page
{
    /**
     * @var string|null
     */
    protected $template = 'default';

    /**
     * Initializes the post instance.
     *
     * @param string $file
     * @param array  $data
     */
    public function __construct($file, array $data = array())
    {
        $uri = str_replace('.md', '', basename($file));

        $this->uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        $this->content = $data['content'];

        $this->data = (array) $data;

        $this->template = $data['page'];
    }
}
