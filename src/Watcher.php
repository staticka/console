<?php

namespace Staticka\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Watcher
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Watcher extends Command
{
    /**
     * @var string
     */
    protected $description = 'Rebuilds site if there are changes';

    /**
     * @var string
     */
    protected $name = 'watch';

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
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locator = new Locator($this->paths);

        $command = $this->getApplication()->find('build');

        while (true)
        {
            foreach ($this->paths as $name => $path)
            {
                if ($name === 'public')
                {
                    continue;
                }

                if ($locator->empty($name))
                {
                    $locator->update($name);

                    continue;
                }

                if ($locator->changed($name))
                {
                    $message = "Changes found in \"$name\"...";

                    $output->writeln("<info>$message</info>");

                    $command->run($input, $output);

                    $locator->update((string) $name);

                    break;
                }
            }
        }
    }
}
