<?php

namespace Staticka\Console;

use Rougin\Blueprint\Blueprint;
use Rougin\Blueprint\Container;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Staticka\Render;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Console extends Blueprint
{
    /**
     * @var string
     */
    protected $name = 'Staticka Console';

    /**
     * @var string
     */
    protected $file = 'staticka.yml';

    /**
     * @var string
     */
    protected $root;

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * @param string $root
     */
    public function __construct($root)
    {
        $namespace = __NAMESPACE__ . '\Scripts';

        $this->setCommandNamespace($namespace);

        $this->setCommandPath(__DIR__ . '/Scripts');

        $this->root = $root;

        $this->setPackages();
    }

    /**
     * @return string
     */
    public function getBuildPath()
    {
        $root = $this->getRootPath();

        return $this->getPath('build_path', $root . '/build');
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        $root = $this->getRootPath();

        return $this->getPath('config_path', $root . '/config');
    }

    /**
     * @return string
     */
    public function getPlatesPath()
    {
        $root = $this->getRootPath();

        return $this->getPath('plates_path', $root . '/plates');
    }

    /**
     * @return string
     */
    public function getPagesPath()
    {
        $root = $this->getRootPath();

        return $this->getPath('pages_path', $root . '/pages');
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        /** @var string */
        $path = realpath($this->root);

        return $this->getPath('root_path', $path);
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        $data = $this->getPath('timezone', 'Asia/Manila');

        return str_replace('\\', '/', $data);
    }

    /**
     * @return \Rougin\Slytherin\Integration\IntegrationInterface[]
     */
    protected function getPackages()
    {
        $data = $this->getParsed();

        $items = array();

        if (array_key_exists('packages', $data))
        {
            /** @var class-string[] */
            $parsed = $data['packages'];

            foreach ($parsed as $item)
            {
                $class = new \ReflectionClass($item);

                /** @var \Rougin\Slytherin\Integration\IntegrationInterface */
                $package = $class->newInstanceArgs(array());

                $items[] = $package;
            }
        }

        return $items;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParsed()
    {
        /** @var string */
        $path = realpath($this->root);

        if (! file_exists($path . '/' . $this->file))
        {
            return array();
        }

        $file = $path . '/' . $this->file;

        /** @var string */
        $file = file_get_contents($file);

        // Replace the constant with root path ----
        $search = '%%CURRENT_DIRECTORY%%';

        $file = str_replace($search, $path, $file);
        // ----------------------------------------

        /** @var array<string, mixed> */
        return Yaml::parse($file);
    }

    /**
     * @param string $name
     * @param string $default
     *
     * @return string
     */
    protected function getPath($name, $default)
    {
        /** @var string */
        $path = realpath($this->root);

        $ds = DIRECTORY_SEPARATOR;

        $default = str_replace(array('/', '\\'), $ds, $default);

        if (! file_exists($path . '/' . $this->file))
        {
            return $default;
        }

        $parsed = $this->getParsed();

        $path = $default;

        if (array_key_exists($name, $parsed))
        {
            /** @var string */
            $path = $parsed[$name];
        }

        $path = str_replace(array('/', '\\'), $ds, $path);

        return $path;
    }

    /**
     * @return void
     */
    protected function setPackages()
    {
        $config = new Configuration;

        $config->load($this->getConfigPath());

        $container = new Container($config);

        $path = $this->getRootPath();

        $package = new Package($path);
        $package->setBuildPath($this->getBuildPath());
        $package->setConfigPath($this->getConfigPath());
        $package->setPagesPath($this->getPagesPath());
        $package->setPlatesPath($this->getPlatesPath());
        $package->setTimezone($this->getTimezone());

        // Initialize the Render instance ---------------
        $platesPath = $this->getPlatesPath();
        $render = new Render($platesPath);

        $name = 'Rougin\Staticka\Render\RenderInterface';

        $container->set($name, $render);
        // ----------------------------------------------

        $container->addPackage($package);

        foreach ($this->getPackages() as $item)
        {
            $container->addPackage($item);
        }

        $this->setContainer($container);
    }
}
