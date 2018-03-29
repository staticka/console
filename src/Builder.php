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
        $website = $input->getOption('website');

        file_exists($website) && $website = require $website;

        list($source, $build) = $this->paths($input);

        $website === null && $website = new Website;

        $website->locate((string) $source)->compile($build);

        $message = '<info>Content built successfully!</info>';

        $output->writeln((string) $message);
    }

    /**
     * Returns the source and output directories.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @return array
     */
    protected function paths(InputInterface $input)
    {
        $output = (string) $input->getOption('output');

        $source = (string) $input->getOption('source');

        file_exists($source) && $source = realpath($source);

        file_exists($output) && $output = realpath($output);

        return array($source, $output);
    }
}
