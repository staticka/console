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
     * @param string $content
     * @param array  $data
     */
    public function __construct($content, array $data = array())
    {
        $uri = str_replace('.md', '', basename($content));

        $this->uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        $this->content = (string) $content;

        $this->data = (array) $data;

        $exists = isset($data['page']) === true;

        $exists && $this->template = $data['page'];
    }
}
