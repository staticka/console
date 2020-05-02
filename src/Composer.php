<?php

namespace Staticka\Console;

/**
 * Composer
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Composer
{
    /**
     * @var array
     */
    protected $data =
    [
        'filters' =>
        [
            'Staticka\\Filters\\HtmlMinifier',
            'Staticka\\Filters\\ScriptMinifier',
            'Staticka\\Filters\\StyleMinifier',
        ],
        'paths' =>
        [
            'pages' => '$ROOT$/pages',
            'plates' => '$BASE$/Plates',
            'public' => '$ROOT$/public',
            'scripts' => '$BASE$/Scripts',
            'styles' => '$BASE$/Styles'
        ],
        'styles' =>
        [
            'main.scss' => 'css/main.css',
        ],
        'variables' =>
        [
            'base_url' => 'https://staticka.github.io/',
            'website' => 'Staticka',
        ],
    ];

    /**
     * @var string
     */
    protected $path = '';

    /**
     * Initializes the reader instance.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $file = $path . '/composer.json';

        $this->path = (string) $path;

        $this->data = $this->parse($this->data);

        $json = file_get_contents($file);

        $json = json_decode($json, true);

        if (isset($json['staticka']))
        {
            $config = (array) $json['staticka'];

            $data = array_merge($this->data, $config);

            $this->data = $this->parse($data);
        }
    }

    /**
     * Returns the "staticka" data from the file.
     *
     * @return string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Parses the specified data.
     *
     * @param  array $data
     * @return array
     */
    protected function parse($data)
    {
        $base = dirname(__FILE__) . '/Templates';

        $root = (string) $this->path;

        $callback = function (&$value) use ($base, $root)
        {
            $value = str_replace('$BASE$', $base, $value);

            $value = str_replace('$ROOT$', $root, $value);
        };

        array_walk_recursive($data, $callback);

        return $data;
    }
}
