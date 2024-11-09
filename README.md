# Staticka Console

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

`Console` is a terminal-based package of [Staticka](https://github.com/staticka/staticka) which allows creating and building of pages through a terminal.

## Installation

Installing the `Console` package is possible through [Composer](https://getcomposer.org/):

``` bash
$ composer require staticka/console
```

## Basic Usage

To create a new page, use the `create` command with its title:

``` bash
$ vendor/bin/staticka create "Hello world!"
[PASS] "Hello world!" page successfully created!
```

``` md
<!-- pages/20241028202552_hello-world.md -->

---
name: Hello world!
link: /hello-world
title: Hello world!
description:
tags:
category:
---

# Hello world!
```

```
ciacme/
├── pages/
│   └── 20241028202552_hello-world.md
├── vendor/
└── composer.json
```

After adding some text to the newly created page, use the `build` command to compile the `.md` files to `.html`:

``` bash
$ vendor/bin/staticka build
[PASS] Pages successfully compiled!
```

```
ciacme/
├── build/
│   └── hello-world/
│       └── index.html
├── pages/
│   └── 20241028202552_hello-world.md
├── vendor/
└── composer.json
```

``` html
<!-- build/hello-world/index.html -->
<h1>Hello world!</h1>
```

> [!NOTE]
> `Console` will try to create the required directories (e.g., `build`, `pages`) if they do not exists in the current working directory.

## Using `staticka.yml`

`Console` typically works out of the box without any configuration. But if there is a need to change the path of its other supported directories or needs to extend the core functionalities of `Console`, the `staticka.yml` file can be used in those kind of scenarios:

``` yaml
# staticka.yml

root_path: %%CURRENT_DIRECTORY%%
timezone: Asia/Manila

assets_path: %%CURRENT_DIRECTORY%%/assets
build_path: %%CURRENT_DIRECTORY%%/build
config_path: %%CURRENT_DIRECTORY%%/config
pages_path: %%CURRENT_DIRECTORY%%/pages
plates_path: %%CURRENT_DIRECTORY%%/plates
scripts_path: %%CURRENT_DIRECTORY%%/scripts
styles_path: %%CURRENT_DIRECTORY%%/styles
```

To create the said `staticka.yml` file, simply run the `initialize` command:

``` bash
$ vendor/bin/staticka initialize
[PASS] "staticka.yml" added successfully!
```

After successfully creating the said file, it will provide the following properties below:

### `root_path`

This property specifies the current working directory. By default, it uses the `%%CURRENT_DIRECTORY%%` placeholder that returns the current directory of the `staticka.yml` file:

``` yaml
# staticka.yml

root_path: %%CURRENT_DIRECTORY%%/Sample

# ...
```

### `timezone`

This allows to change the timezone to be used when creating timestamps of a new page. If not specified, `Console` will use the default timezone specified in the `php.ini` file:

``` yaml
# staticka.yml

timezone: Asia/Tokyo

# ...
```

### `assets_path`

This specifies the path for all other web assets like images (`.jpg`, `.png`) and PDF files (`.pdf`). `Console` does not use this specified path but it might be useful to locate the directory for organizing asset files:

``` yaml
# staticka.yml

assets_path: %%CURRENT_DIRECTORY%%/assets

# ...
```

### `build_path`

This is the property that will be used by `Console` to determine the destination directory of the compiled pages:

``` yaml
# staticka.yml

build_path: %%CURRENT_DIRECTORY%%/build

# ...
```

### `config_path`

One of the properties of `Console` that locates the directory for storing configuration files. If defined, it will load its `.php` files to a `Configuration` class. The said class is useful when creating extensions to `Console`:

``` yaml
# staticka.yml

config_path: %%CURRENT_DIRECTORY%%/config

# ...
```

``` php
// config/parser.php

return array(
    /**
     * @var \Staticka\Filter\FilterInterface[]
     */
    'filters' => array(
        'Staticka\Expresso\Filters\GithubFilter',
        'Staticka\Expresso\Filters\ReadmeFilter',
    ),
);
```

``` php
// src/Package.php

namespace Ciacme;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

class Package implements IntegrationInterface
{
    public function define(ContainerInterface $container, Configuration $config)
    {
        // Will try to access the "config/parser.php" file ---
        /** @var class-string[] */
        $filters = $config->get('parser.filters', array());
        // ---------------------------------------------------

        // ...
    }
}
```

> [!NOTE]
> To allow custom packages for `Console`, kindly add the specified package class in `staticka.yml`. Please see the `Extending Console` section below for more information.

### `pages_path`

This is the location of the generated pages from `create` command:

``` yaml
# staticka.yml

pages_path: %%CURRENT_DIRECTORY%%/pages

# ...
```

### `plates_path`

One of the special variables of `Console` to specify a directory that can be used for third-party templating engines (`RenderInterface`):

``` yaml
# staticka.yml

plates_path: %%CURRENT_DIRECTORY%%/plates

# ...
```

### `scripts_path`

This is the property for the directory path of script files (`.js`, `.ts`). Although not being used internally by `Console`, this property can be used when extending core functionalities (e.g., compiling `.js` files through [Webpack](https://webpack.js.org/) when running the `build` command):

``` yaml
# staticka.yml

scripts_path: %%CURRENT_DIRECTORY%%/scripts

# ...
```

### `styles_path`

Same as `scripts_path`, this property specifies the directory path for styling files (`.css`, `.sass`):

``` yaml
# staticka.yml

styles_path: %%CURRENT_DIRECTORY%%/styles

# ...
```

## Extending `Console`

`Console` is based on the [Slytherin](https://github.com/rougin/slytherin) PHP micro-framework which provides an easy way to integrate custom packages through `IntegrationInterface`. The said interface can be used to create instances related to `Staticka`:

``` php
// src/Package.php

namespace Ciacme;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Staticka\Filter\HtmlMinifier;
use Staticka\Layout;

class Package implements IntegrationInterface
{
    /**
     * This sample package will always minify the compiled HTML files.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $layout = new Layout;

        $layout->addFilter(new HtmlMinifier);

        $container->set(get_class($layout), $layout);

        return $container;
    }
}
```

To add the specified custom package, kindly add it to the `staticka.yml` file:

``` yaml
# staticka.yml

root_path: %%CURRENT_DIRECTORY%%

# ...

packages:
  - Ciacme\Package
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

The unit tests of `Console` can be run using the `phpunit` command:

``` bash
$ vendor/bin/phpunit
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/staticka/console/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/staticka/console?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/console.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/console.svg?style=flat-square

[link-build]: https://github.com/staticka/console/actions
[link-changelog]: https://github.com/staticka/console/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/staticka/console/contributors
[link-coverage]: https://app.codecov.io/gh/staticka/console
[link-downloads]: https://packagist.org/packages/staticka/console
[link-license]: https://github.com/staticka/console/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/console