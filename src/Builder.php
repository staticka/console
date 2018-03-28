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
        $build = (string) $input->getOption('output');

        $source = (string) $input->getOption('source');

        file_exists($source) && $source = realpath($source);

        file_exists($build) && $build = realpath($build);

        $site = new \Staticka\Siemes\Site;

        $site->locate((string) $source)->compile((string) $build);

        $output->writeln('<info>Content built successfully!</info>');
    }
}
