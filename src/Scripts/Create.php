<?php

namespace Staticka\Console\Scripts;

use Rougin\Blueprint\Command;
use Staticka\Depots\PageDepot;
use Staticka\System;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Create extends Command
{
    /**
     * @var string
     */
    protected $name = 'create';

    /**
     * @var string
     */
    protected $description = 'Create a new page';

    /**
     * @var \Staticka\Depots\PageDepot
     */
    protected $page;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @param \Staticka\Depots\PageDepot $page
     * @param \Staticka\System           $system
     */
    public function __construct(PageDepot $page, System $system)
    {
        $path = $system->getPagesPath();

        $this->path = $path;

        $this->page = $page;

        $timezone = $system->getTimezone();

        $this->timezone = $timezone;
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->addArgument('name', 'Name of the page');
    }

    /**
     * @return integer
     */
    public function run()
    {
        // @codeCoverageIgnoreStart
        if (! is_dir($this->path))
        {
            mkdir($this->path, 0777, true);
        }
        // @codeCoverageIgnoreEnd

        date_default_timezone_set($this->timezone);

        /** @var string */
        $name = $this->getArgument('name');

        $data = array('name' => $name);

        $data['link'] = $this->page->getSlug($name);

        $this->page->create($data);

        $this->showPass('"' . $name . '" page successfully created!');

        return self::RETURN_SUCCESS;
    }
}
