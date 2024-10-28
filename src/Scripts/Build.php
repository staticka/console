<?php

namespace Staticka\Console\Scripts;

use Rougin\Blueprint\Command;
use Rougin\Staticka\Layout;
use Rougin\Staticka\Parser;
use Rougin\Staticka\Render\RenderInterface;
use Rougin\Staticka\Site;
use Staticka\Console\Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Build extends Command
{
    /**
     * @var \Staticka\Console\Staticka
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
     * @var \Rougin\Staticka\Layout
     */
    protected $layout;

    /**
     * @var \Rougin\Staticka\Parser
     */
    protected $parser;

    /**
     * @var \Rougin\Staticka\Render\RenderInterface
     */
    protected $render;

    /**
     * @var \Rougin\Staticka\Site
     */
    protected $site;

    /**
     * @param \Rougin\Staticka\Layout                 $layout
     * @param \Rougin\Staticka\Parser                 $parser
     * @param \Rougin\Staticka\Render\RenderInterface $render
     * @param \Rougin\Staticka\Site                   $site
     * @param \Staticka\Console\Staticka              $staticka
     */
    public function __construct(Layout $layout, Parser $parser, RenderInterface $render, Site $site, Staticka $staticka)
    {
        $this->app = $staticka;

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

            $this->showText('Added "' . $page->getName() . '" page');
        }

        $output = $this->app->getBuildPath();

        $this->site->build($output);

        $this->showPass('Pages successfully compiled!');

        return self::RETURN_SUCCESS;
    }
}
