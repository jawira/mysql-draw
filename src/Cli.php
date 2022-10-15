<?php declare(strict_types=1);

namespace Jawira\MysqlDraw;

use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\MiniGetopt\MiniGetopt;
use Jawira\PlantUmlClient\Client;
use Jawira\PlantUmlClient\Format;
use Jawira\PlantUmlToImage\PlantUml;
use Jawira\PlantUmlToImage\PlantUmlException;
use RuntimeException;
use function compact;
use function getenv;
use function in_array;
use const PHP_EOL;

class Cli
{
  /**
   * Return cli options.
   *
   * @return array{getopt: array, doc: string}
   * @throws \Jawira\MiniGetopt\MiniGetoptException
   */
  static protected function loadOptions(): array
  {
    $mg = new MiniGetopt();
    $mg->addRequired('u', 'url', 'Database url in doctrine format.', 'url');
    $mg->addRequired('f', 'format', 'Diagram format (default: svg).', 'puml|svg|png');
    $mg->addRequired('s', 'size', 'Diagram size (default: midi).', 'mini|midi|maxi');
    $mg->addRequired('j', 'jar', "Path to plantuml.jar.", '/path/to/plantuml.jar');
    $mg->addNoValue('h', 'help', 'Show help');
    $getopt = $mg->getopt();
    $usage  = [
      'mysql-draw --url=mysql://user:pass@host/db_name --size=mini --format=png',
      'mysql-draw --url=mysql://user:pass@host/db_name --jar=/usr/share/plantuml/plantuml.jar',
      'mysql-draw --url=mysql://user:pass@host:3306/db_name?serverVersion=5.7',
      'mysql-draw --url=mysql://user:pass@host/db_name?serverVersion=mariadb-10.3.22',
      'DATABASE_URL=mysql://user:pass@host/db_name mysql-draw',
    ];
    $doc    = $mg->doc('Generate a diagram from your MySQL database.', $usage);

    return compact('getopt', 'doc');
  }

  /**
   * @throws \Doctrine\DBAL\Exception
   * @throws \Jawira\MiniGetopt\MiniGetoptException
   * @throws \Exception
   */
  static public function main(): void
  {
    ['getopt' => $getopt, 'doc' => $doc] = self::loadOptions();

    $env    = getenv('DATABASE_URL') ?: null;
    $url    = $getopt['u'] ?? $getopt['url'] ?? $env;
    $format = $getopt['f'] ?? $getopt['format'] ?? 'svg';
    $size   = $getopt['s'] ?? $getopt['size'] ?? 'midi';
    $jar    = $getopt['j'] ?? $getopt['jar'] ?? null;
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

    $puml     = self::generatePuml($url, $size);
    $diagram  = self::generateDiagram($puml, $format, $jar);
    $filename = "database.$format";
    echo "Writing $filename...", PHP_EOL;
    file_put_contents($filename, $diagram);
    echo 'mysql-draw by Jawira Portugal', PHP_EOL;
  }

  /**
   * @return string
   * @throws \Doctrine\DBAL\Exception
   */
  protected static function generatePuml(string $url, string $size, ?string $jar = null): string
  {
    $params     = ['url' => $url,
      'driver' => 'pdo_mysql'];
    $connection = DriverManager::getConnection($params);
    $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    $connection->getDatabasePlatform()->registerDoctrineTypeMapping('geometry', 'string');
    $dbDraw = new DbDraw($connection);

    return $dbDraw->generatePuml($size);
  }

  /**
   * Convert PlantUML diagram to image.
   *
   * @param string $puml PlantUML diagram.
   * @param string $format Target format.
   *
   * @return string
   * @throws \Jawira\PlantUmlClient\ClientException
   * @throws PlantUmlException
   */
  protected static function generateDiagram(string $puml, string $format, ?string $jar = null): string
  {
    if ($format === 'puml') {
      return $puml;
    }
    if (!in_array($format, [Format::PNG, Format::SVG])) {
      throw new RuntimeException("Invalid format $format");
    }

    // Trying to use PlantUml locally
    $plantUmlToImage = new PlantUml();
    if (is_string($jar)) {
      echo "Setting Jar $jar...", PHP_EOL;
      $plantUmlToImage->setJar($jar);
    }
    echo 'Trying to use PlantUML locally...', PHP_EOL;
    if ($plantUmlToImage->isPlantUmlAvailable()) {
      return $plantUmlToImage->convertTo($puml, $format);
    } else {
      echo 'PlantUML not found locally...', PHP_EOL;
    }

    // Using web client
    $client = new Client();
    $server = $client->getServer();
    echo "Trying to use PlantUML web server $server...", PHP_EOL;
    return $client->generateImage($puml, $format);
  }

}
