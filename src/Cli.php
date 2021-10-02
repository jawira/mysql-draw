<?php declare(strict_types=1);

namespace Jawira\MysqlDraw;

use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\MiniGetopt\MiniGetopt;
use RuntimeException;
use function compact;
use function getenv;
use const PHP_EOL;

class Cli
{
  /**
   * Return cli options
   *
   * @throws \Jawira\MiniGetopt\MiniGetoptException
   */
  static protected function loadOptions(): array
  {
    $mg = new MiniGetopt();
    $mg->addRequired('u', 'url', 'Database url in doctrine format', 'url');
    $mg->addRequired('f', 'format', 'Diagram format (default: svg)', 'puml|svg|png');
    $mg->addRequired('s', 'size', 'Diagram size (default: midi)', 'mini|midi|maxi');
    $mg->addNoValue('h', 'help', 'Show help');
    $getopt = $mg->getopt();
    $usage  = ['mysql-draw --url=mysql://user:pass@host/db_name --size=mini --format=png',
               'mysql-draw --url=mysql://user:pass@host:3306/db_name?serverVersion=5.7',
               'mysql-draw --url=mysql://user:pass@host/db_name?serverVersion=mariadb-10.3.22',
               'DATABASE_URL=mysql://user:pass@host/db_name mysql-draw',];
    $doc    = $mg->doc('Generate a diagram from your MySQL database.', $usage);

    return compact('getopt', 'doc');
  }

  /**
   * @throws \Doctrine\DBAL\Exception
   * @throws \Jawira\MiniGetopt\MiniGetoptException
   * @throws \Exception
   */
  static public function main()
  {
    ['getopt' => $getopt, 'doc' => $doc] = self::loadOptions();

    $env    = getenv('DATABASE_URL') ?: null;
    $url    = $getopt['u'] ?? $getopt['url'] ?? $env;
    $format = $getopt['f'] ?? $getopt['format'] ?? 'svg';
    $size   = $getopt['s'] ?? $getopt['size'] ?? 'midi';
    $help   = $getopt['h'] ?? $getopt['help'] ?? null;

    if (empty($getopt) && empty($env)) {
      echo $doc;
      exit(1);
    }

    if ($help === false) {
      echo $doc;
      exit(0);
    }

    if (empty($url)) {
      throw new RuntimeException('Database url not set, use --url or DATABASE_URL environment variable.');
    }

    $puml    = self::generatePuml($url, $size);
    $diagram = self::generateDiagram($puml, $format);
    file_put_contents("database.$format", $diagram);
    echo 'mysql-draw by Jawira Portugal', PHP_EOL;
  }

  /**
   * @throws \Doctrine\DBAL\Exception
   * @return string
   */
  protected static function generatePuml(string $url, string $size): string
  {
    $params     = ['url'    => $url,
                   'driver' => 'pdo_mysql'];
    $connection = DriverManager::getConnection($params);
    $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    $dbDraw = new DbDraw($connection);

    return $dbDraw->generatePuml($size);
  }

  protected static function generateDiagram(string $puml, string $format)
  {
    if ($format === 'puml') {
      return $puml;
    }
    throw new RuntimeException('Format not implemented');
  }

}
