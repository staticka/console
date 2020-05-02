<?php

namespace Staticka\Console\Factories;

use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Matter;
use Staticka\Page;

/**
 * Page Factory
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PageFactory
{
    /**
     * @var \Staticka\Contracts\LayoutContract
     */
    protected $layout;

    /**
     * @param \Staticka\Contract\LayoutContract $layout
     */
    public function __construct(LayoutContract $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Returns the data from the specified file.
     *
     * @param  string $file
     * @param  array  $data
     * @return array
     */
    public function data($file, $data = array())
    {
        $data['filename'] = basename($file);

        $data['id'] = substr($data['filename'], 0, 14);

        $data['created_at'] = strtotime($data['id']);

        $data = (array) $this->tags($data);

        $content = file_get_contents($file);

        list($info, $body) = Matter::parse($content);

        $data[PageContract::DATA_BODY] = $body;

        $data = array_merge($data, $info);

        return array_merge($data, compact('file'));
    }

    /**
     * Creates a new Page instance.
     *
     * @param  string $name
     * @param  array  $data
     * @return \Staticka\Contract\PageContract
     */
    public function make($name, $data = array())
    {
        $data = $data ? $data : $this->data($name);

        $layout = $data[PageContract::DATA_LAYOUT];

        $data[PageContract::DATA_PATH] = $layout;

        return new Page($this->layout, (array) $data);
    }

    /**
     * Returns the data with parsed tags.
     *
     * @param  array $data
     * @return array
     */
    protected function tags(array $data)
    {
        $data['tag_items'] = array();

        if (isset($data['tags']) && is_string($data['tags']))
        {
            $data['tag_items'] = explode(',', $data['tags']);

            foreach ($data['tag_items'] as $key => $value)
            {
                $data['tag_items'][$key] = trim($value);
            }
        }

        return (array) $data;
    }
}
