#!/usr/bin/env php
<?php declare(strict_types=1);

try {
  set_time_limit(0);
  Phar::mapPhar('mysql-draw');
  require 'phar://mysql-draw/vendor/autoload.php';
  echo 'hello', PHP_EOL;
} catch (Throwable $throwable) {
  echo $throwable->getMessage() . PHP_EOL;
  exit(1);
}
exit(0);
__HALT_COMPILER();
