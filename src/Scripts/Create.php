<?php

namespace Staticka\Console\Scripts;

use Rougin\Blueprint\Command;
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
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @param \Staticka\System $system
     */
    public function __construct(System $system)
    {
        $this->path = $system->getPagesPath();

        $this->timezone = $system->getTimezone();
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
     * TODO: Move logic to a depot from "staticka/staticka" instead.
     *
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        // @codeCoverageIgnoreStart
        if (! is_dir($this->path))
        {
            mkdir($this->path, 0777, true);
        }
        // @endCoverageIgnoreEnd

        date_default_timezone_set($this->timezone);

        /** @var string */
        $name = $this->getArgument('name');

        // Set the file name of the new page ---------------------
        $file = $this->getSlug($name);

        $prefix = date('YmdHis');

        $file = $this->path . '/' . $prefix . '_' . $file . '.md';
        // -------------------------------------------------------

        $data = $this->setData($name);

        file_put_contents($file, $data);

        $this->showPass('"' . $name . '" page successfully created!');

        return self::RETURN_SUCCESS;
    }

    /**
     * TODO: Migrate code to "staticka/staticka" instead.
     *
     * @link https://stackoverflow.com/a/2103815
     *
     * @param string $text
     *
     * @return string
     */
    protected function getSlug($text)
    {
        // Convert to entities --------------------------
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------

        // Regex to convert accented chars into their closest a-z ASCII equivelent --------------
        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';

        /** @var string */
        $text = preg_replace($pattern, '$1', $text);
        // --------------------------------------------------------------------------------------

        // Convert back from entities -------------------------
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------------

        // Any straggling caracters that are not strict alphanumeric are replaced with a dash ---
        /** @var string */
        $text = preg_replace('~[^0-9a-z]+~i', '-', $text);
        // --------------------------------------------------------------------------------------

        // Trim / cleanup / all lowercase ---
        $text = trim($text, '-');

        return strtolower($text);
        // ----------------------------------
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function setData($name)
    {
        $path = __DIR__ . '/../Plates/default.md';

        /** @var string */
        $data = file_get_contents($path);

        $data = str_replace('[TITLE]', $name, $data);

        $link = $this->getSlug($name);

        return str_replace('[LINK]', $link, $data);
    }
}
