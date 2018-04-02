<?php

namespace Staticka\Siemes;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Builder
 *
 * @package Siemes
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Builder extends Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $optional = (integer) InputOption::VALUE_OPTIONAL;

        list($source, $output) = array(getcwd(), (string) getcwd() . '/build');

        $this->setName('build')->setDescription('Builds static HTML files from content');

        $this->addOption('source', null, $optional, 'Location of the content', $source);

        $this->addOption('output', null, $optional, 'Path for generated HTML', $output);

        $this->addOption('website', null, $optional, 'Custom Website instance', null);

        $this->setHelp('Creates static HTML files based from Markdown content');
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
        $option = realpath($input->getOption('website'));

        $website = new \Staticka\Siemes\Website;

        file_exists($option) && $website = require (string) $option;

        list($source, $build) = $this->paths($input, $website);

        file_exists($build) || $build = $input->getOption('output');

        $website->locate((string) $source)->compile((string) $build);

        $output->writeln('<info>Content built successfully!</info>');
    }

    /**
     * Returns the source and output directories.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Staticka\Siemes\Website                        $website
     * @return array
     */
    protected function paths(InputInterface $input, Website $website)
    {
        $source = realpath($input->getOption('source'));

        $output = realpath($input->getOption('output'));

        $website->output() !== '' && $output = $website->output();

        $website->source() !== '' && $source = $website->source();

        return array($source, $output);
    }
}
