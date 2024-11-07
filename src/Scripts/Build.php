<?php

namespace Staticka\Console\Scripts;

use Rougin\Blueprint\Command;
use Staticka\Layout;
use Staticka\Parser;
use Staticka\Render\RenderInterface;
use Staticka\Site;
use Staticka\System;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Build extends Command
{
    /**
     * @var \Staticka\System
     */
    protected $app;

    /**
     * @var string
     */
    protected $name = 'build';

    /**
     * @var string
     */
    protected $description = 'Convert .md files to .html';

    /**
     * @var \Staticka\Layout
     */
    protected $layout;

    /**
     * @var \Staticka\Parser
     */
    protected $parser;

    /**
     * @var \Staticka\Render\RenderInterface
     */
    protected $render;

    /**
     * @var \Staticka\Site
     */
    protected $site;

    /**
     * @param \Staticka\Layout                 $layout
     * @param \Staticka\Parser                 $parser
     * @param \Staticka\Render\RenderInterface $render
     * @param \Staticka\Site                   $site
     * @param \Staticka\System                 $system
     */
    public function __construct(Layout $layout, Parser $parser, RenderInterface $render, Site $site, System $system)
    {
        $this->app = $system;

        $this->layout = $layout;

        $this->parser = $parser;

        $this->site = $site;

        $this->render = $render;
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $pages = $this->app->getPages();

        $this->parser->setRender($this->render);

        $this->site->setParser($this->parser);

        foreach ($pages as $page)
        {
            $page->setLayout($this->layout);

            $this->site->addPage($page);
        }

        $output = $this->app->getBuildPath();

        $this->site->build($output);

        $this->showPass('Pages successfully compiled!');

        return self::RETURN_SUCCESS;
    }
}
