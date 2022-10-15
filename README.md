# MySQL Draw

**📐 Minimalist standalone tool to generate MySQL database diagrams.**

![Packagist Version](https://img.shields.io/packagist/v/jawira/mysql-draw?style=for-the-badge)
![Packagist Downloads](https://img.shields.io/packagist/dt/jawira/mysql-draw?style=for-the-badge)
![Packagist License](https://img.shields.io/packagist/l/jawira/mysql-draw?style=for-the-badge)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/jawira/mysql-draw/php?style=for-the-badge)

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
