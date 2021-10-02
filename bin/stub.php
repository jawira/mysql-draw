#!/usr/bin/env php
<?php declare(strict_types=1);

use Jawira\MysqlDraw\Cli;

try {
  set_time_limit(0);
  Phar::mapPhar('mysql-draw');
  require 'phar://mysql-draw/vendor/autoload.php';
  Cli::main();
} catch (Throwable $throwable) {
  echo $throwable->getMessage() . PHP_EOL;
  exit(1);
}
exit(0);
__HALT_COMPILER();
