<?php

namespace Staticka\Console;

use Symfony\Component\Console\Command\Command as Symfony;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Command extends Symfony
{
    /**
     * @var string[]
     */
    protected $paths = [];

    /**
     * @var string[]
     */
    protected $styles = [];

    /**
     * @param string[] $paths
     * @param string[] $styles
     */
    public function __construct($paths, $styles = [])
    {
        $this->paths = $paths;

        $this->styles = $styles;

        parent::__construct($this->name);
    }
}
