<?php

namespace Staticka\Console\Factories;

use Staticka\Builder;
use Staticka\Console\Renderers\TwigRenderer;
use Staticka\Console\Website;
use Staticka\Layout;

/**
 * Site Factory
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SiteFactory
{
    /**
     * @var string[]
     */
    protected $plates;

    /**
     * @param string[] $plates
     */
    public function __construct($plates)
    {
        $this->plates = $plates;
    }

    /**
     * Creates a new WebsiteContract instance.
     *
     * @param  string $layout
     * @param  array  $data
     * @return \Staticka\Contracts\WebsiteContract
     */
    public function make($layout = null, $data = array())
    {
        if ($layout === null)
        {
            $layout = new Layout;

            foreach ($data['filters'] as $filter)
            {
                $layout->filter(new $filter);
            }
        }

        $data = $data['variables'];

        $twig = new TwigRenderer($this->plates, $data);

        $builder = new Builder($twig);

        return new Website($builder, $layout);
    }
}
