<?php

namespace Staticka\Console;

use Staticka\Console\Factories\PageFactory;
use Staticka\Website as Staticka;

/**
 * Website
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Website extends Staticka
{
    /**
     * @var string[]
     */
    protected $paths;

    /**
     * TODO: Create ScriptRendererContract
     * @var \Staticka\Console\Contracts\ScriptRendererContract
     */
    protected $script;

    /**
     * TODO: Create StyleRendererContract
     * @var \Staticka\Console\Contracts\StyleRendererContract
     */
    protected $style;

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param  string $output
     * @return void
     */
    public function build($output)
    {
        $this->prepare($this->paths['pages']);

        try
        {
            if (file_exists($output))
            {
                $this->clear($output);
            }

            parent::build($output);

            $this->style->build();
        }
        catch (\Exception $e)
        {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Sets the specified paths for the website.
     *
     * @param  string[] $paths
     * @return self
     */
    public function paths($paths)
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * Sets the StyleRenderer for the website.
     *
     * @param  \Staticka\Console\Contracts\StyleRendererContract $style
     * @return self
     */
    public function style($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Prepares the pages for generation.
     *
     * @param  string $path
     * @return void
     */
    protected function prepare($path)
    {
        $pages = Locator::rglob("$path/**.md");

        $this->pages = array();

        $factory = new PageFactory($this->layout());

        $metadata = array();

        foreach ($pages as $index => $file)
        {
            $metadata[$index] = $factory->data($file);
        }

        uasort($metadata, function ($a, $b)
        {
            return $a['created_at'] < $b['created_at'];
        });

        foreach ($pages as $index => $file)
        {
            $data = $metadata[$index];

            $data['pages'] = $metadata;

            $this->add($factory->make($file, $data));
        }
    }
}
