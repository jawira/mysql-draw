<?php declare(strict_types=1);

namespace Jawira\MysqlDraw;

use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\MiniGetopt\MiniGetopt;
use RuntimeException;
use function compact;
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
    $doc    = $mg->doc('Generate a diagram from your MySQL database.');

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

    $url    = $getopt['u'] ?? $getopt['url'] ?? null;
    $format = $getopt['f'] ?? $getopt['format'] ?? 'svg';
    $size   = $getopt['s'] ?? $getopt['size'] ?? 'midi';
    $help   = $getopt['h'] ?? $getopt['help'] ?? null;

    if (empty($getopt)) {
      echo $doc;
      exit(1);
    }

    if ($help === false) {
      echo $doc;
      exit(0);
    }

    if (empty($url)) {
      throw new RuntimeException('Database url not set');
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
