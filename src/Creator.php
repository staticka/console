<?php

namespace Staticka\Console;

use Staticka\Console\Factories\FileFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creator
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Creator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Creates a new page template';

    /**
     * @var string
     */
    protected $name = 'create';

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDescription($this->description);

        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the migration file');

        $this->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'Prefix of the file');
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
        $prefix = (string) $input->getOption('prefix');

        $name = (string) $input->getArgument('name');

        $factory = new FileFactory($this->paths['pages']);

        $file = $factory->make((string) $name, $prefix);

        $message = "$file created successfully!";

        $output->writeln("<info>$message</info>");
    }
}
