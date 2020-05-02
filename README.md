# Console

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A console application for [Staticka](https://staticka.github.io/). Useful for building content and templates into static HTMLs using terminal commands.

## Installation

Install `Console` via [Composer](https://getcomposer.org/):

``` bash
$ composer require staticka/console
```

## Basic Usage

Create a new file by running `staticka create`:

``` bash
$ staticka create "Hello World"
```

**pages/hello-world.md**

```
---
name: Hello World
permalink: /hello-world
layout: main.twig
title: Hello World
description: 
---

# Hello World

This is my first post that is built by Staticka.
```

Then run the `staticka build` command to build the files:

``` bash
$ staticka build
```

To see the output, open `public/hello-world/index.html` in a web browser.

**public/hello-world/index.html**

``` html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hello World - Staticka</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,700;1,300;1,700&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand text-decoration-none" href="/">Rougin Gutib</a>
    </div>
  </nav>
  <div class="jumbotron bg-dark text-white">
    <div class="container">
      <h1>Hello World</h1>
      <p></p>
    </div>
  </div>
  <div class="container post">
    <h1>Hello World</h1>
    <p>This is my first post that is built by Staticka.</p>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
```

**NOTE**: The result above is minified by default.

### Watching files for changes

To build the website after changes are made on specified folders, run the `staticka watch` in the terminal:

``` bash
$ staticka watch

Changes found in "pages"...
Building website...
Website built successfully!
```

By default, it will watch any changes found in the `pages` directory.

## Adding additional data

It is possible to add additional data by adding a new property named `staticka` in the `composer.json`.

``` json
{
    "staticka":
    {
        "filters":
        [
            "Staticka\\Filter\\StyleMinifier",
            "Staticka\\Filter\\HtmlMinifier",
            "Staticka\\Filter\\ScriptMinifier"
        ],
        "paths":
        {
            "pages": "$ROOT$/pages",
            "plates": "$ROOT$/plates",
            "public": "$ROOT$/public",
            "scripts": "$ROOT$/scripts",
            "styles": "$ROOT$/styles"
        },
        "variables":
        {
            "github": "https://github.com/rougin",
            "base_url": "https://roug.in/",
            "website": "Rougin Gutib"
        }
    },
    "require":
    {
        "staticka/expresso": "~0.1"
    }
}
```

**NOTE**: `$ROOT$` is a special variable that corresponds to the directory of the `composer.json` file.

### Filters

Filters are helpful utilities that can alter the output after being generated. Some notable examples are the `HtmlMinifier`, `StyleMinifier`, and `ScriptMinifier` which minifies specified elements in a static page. By default, the mentioned filters were already included.

### Paths

These are a list of paths that are being used by Staticka in generating static pages and also being checked for changes when using the `staticka watch` command:

* `pages` - folder path where the Markdown templates are being stored. When creating a new page through `staticka create`, the new file will be saved in this path.
* `plates` - the location of the Twig templates. This can be used in updating the templates besides on the default layout.
* `public` - where the static pages be stored after building.
* `scripts` - location of the Javascript files
* `styles` - location of the SASS files. By default, Staticka compiles SASS files and also provided `Bootstrap 4` SASS files out of the box.

### Variables

This section contains variables that can be passed for each page being generated. This might be useful when passing global variables such as the base URL, site name, or a text that must be available in all pages.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-code-quality]: https://img.shields.io/scrutinizer/g/staticka/console.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/console.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/staticka/console.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/staticka/console/master.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/console.svg?style=flat-square

[link-changelog]: https://github.com/staticka/console/blob/master/CHANGELOG.md
[link-code-quality]: https://scrutinizer-ci.com/g/staticka/console
[link-contributors]: https://github.com/staticka/console/contributors
[link-downloads]: https://packagist.org/packages/staticka/console
[link-license]: https://github.com/staticka/console/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/console
[link-scrutinizer]: https://scrutinizer-ci.com/g/staticka/console/code-structure
[link-travis]: https://travis-ci.org/staticka/console