<?php

namespace Staticka\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Staticka\Console\Factories\SassFactory;
use Staticka\Console\Factories\SiteFactory;

/**
 * Builder
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder extends Command
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $description = 'Builds static HTML files from content files';

    /**
     * @var string
     */
    protected $name = 'build';

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDescription($this->description);
    }

    /**
     * Sets the data from the "composer.json".
     *
     * @param  array $data
     * @return self
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Building website...</info>');

        $public = $this->paths['public'];

        $paths = (array) $this->paths;

        $sass = new SassFactory($this->paths['styles']);

        $sass = $sass->make($this->styles, $public);

        $website = new SiteFactory($this->paths['plates']);

        $website = $website->make(null, $this->data);

        $website->style($sass);

        unset($paths['public']);

        $website->paths($paths);

        $website->build($public);

        $output->writeln('<info>Website built successfully!</info>');
    }
}
