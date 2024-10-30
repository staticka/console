<?php

namespace Staticka\Console\Fixture\Sample;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Staticka\Parser;
use Staticka\Render;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Package implements IntegrationInterface
{
    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $class = 'Staticka\Console\Staticka';

        /** @var \Staticka\Console\Staticka */
        $old = $container->get($class);

        $platesPath = $old->getPagesPath();

        $render = new Render($platesPath);

        $old->setParser(new Parser($render));

        $old->getConfigPath();

        $old->getRootPath();

        $old->getPlatesPath();

        return $container->set($class, $old);
    }
}
