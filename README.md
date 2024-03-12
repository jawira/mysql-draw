# MySQL Draw

> [!IMPORTANT]
> This repository has been archived, consider
> using [jawira/doctrine-diagram-bundle](https://github.com/jawira/doctrine-diagram-bundle) instead.

**📐 Standalone tool to generate MySQL database diagrams.**

[![Latest Stable Version](http://poser.pugx.org/jawira/mysql-draw/v)](https://packagist.org/packages/jawira/mysql-draw)
[![Total Downloads](http://poser.pugx.org/jawira/mysql-draw/downloads)](https://packagist.org/packages/jawira/mysql-draw)
[![License](http://poser.pugx.org/jawira/mysql-draw/license)](https://packagist.org/packages/jawira/mysql-draw)
[![PHP Version Require](http://poser.pugx.org/jawira/mysql-draw/require/php)](https://packagist.org/packages/jawira/mysql-draw)

## Usage

Database url option (**--url**) is required. Alternatively you can use
**DATABASE_URL** environment variable.

```console
mysql-draw --url=mysql://user:pass@host/db_name --size=mini --format=png
```

## How to install

### Phar file

Download the latest _.phar_ file from releases page <https://github.com/jawira/mysql-draw/releases>:

```console
wget https://github.com/jawira/mysql-draw/releases/download/v0.1.0/mysql-draw.phar
php mysql-draw.phar --help
```

Or install globally:

```console
mv mysql-draw.phar /usr/local/bin/mysql-draw
chmod +x /usr/local/bin/mysql-draw
```

### Composer

```console
composer require jawira/mysql-draw --dev
vendor/bin/mysql-draw --help
```

## Requirements

- PHP 7.4 or newer
- mbstring extension
- mysql extension

## Contributing

If you liked this project, ⭐ [star it on GitHub](https://github.com/jawira/mysql-draw).

## License

This library is licensed under the [MIT license](LICENSE.md).


***

## Packages from jawira

<dl>

<dt>
    <a href="https://packagist.org/packages/jawira/doctrine-diagram-bundle">jawira/doctrine-diagram-bundle
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/doctrine-diagram-bundle?icon=github"/></a>
</dt>
<dd>Symfony Bundle to generate database diagrams.</dd>

<dt>
    <a href="https://packagist.org/packages/jawira/db-draw">jawira/db-draw
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/db-draw?icon=github"/></a>
</dt>
<dd>Library to generate database diagrams.</dd>

<dt>
    <a href="https://packagist.org/packages/jawira/plantuml-client"> jawira/plantuml-client
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/plantuml-client?icon=github"/></a>
</dt>
<dd>Convert PlantUML diagrams into images (svg, png, ...).</dd>

<dt><a href="https://packagist.org/packages/jawira/">more...</a></dt>
</dl>
