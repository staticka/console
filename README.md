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

Create a new file named `hello-world.md`:

**hello-world.md**

```
# Hello World!

This is my first post that is built by Staticka.
```

Then run the `staticka build` command to build the files:

``` bash
$ staticka build
```

To see the output, open `build/hello-world/index.html` in a web browser.

**build/hello-world/index.html**

``` html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hello World</title>
</head>
<body>
  <h1>Hello World</h1>
  <p>This is my first post that is built by Staticka.</p>
</body>
</html>
```

### Options

* `--source` - Location of the content files

``` bash
$ staticka build --source="pages"
$ # Checks content files from `page` directory.
```

* `--output` - Path for the generated files

``` bash
$ staticka build --output="output"
$ # Puts all the compiled files to `output` directory.
```

* `--website` - Specify a custom Website instance

``` bash
$ staticka build --website="Acme.php"
$ # Uses `Acme.php` as `Siemes\Website` instance.
```

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