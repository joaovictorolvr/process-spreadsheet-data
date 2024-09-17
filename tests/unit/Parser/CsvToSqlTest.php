<?php
use PHPUnit\Framework\TestCase;
use App\Parser\CsvToSql;

class CsvToSqlTest extends TestCase
{
  protected $csvToSql;

  protected function setUp(): void
  {
    // create test.csv file
    $file = fopen(__DIR__ . '/test.csv', 'w');
    fwrite($file, "id,name,email\n1,John Doe,john@example.com\n");
    fwrite($file, "2,Jane Doe,jane@example.com\n");
    // write null email
    fwrite($file, "3,John Doe,\n");
    fclose($file);
    $this->csvToSql = new CsvToSql(__DIR__ . "/test.csv", ['id', 'name', 'email'], 'users');

  }

  protected function tearDown(): void
  {
    // remove test.csv file
    unlink(__DIR__ . '/test.csv');
  }

  public function testColumns()
  {
    $expected = "(id, name, email)";
    $this->assertEquals($expected, $this->csvToSql->columns());
  }

  public function testRows()
  {
    $expected = [
      "(1, John Doe, john@example.com)",
      "(2, Jane Doe, jane@example.com)",
      "(3, John Doe, NULL)"
    ];
    $result = iterator_to_array($this->csvToSql->rows());
    $this->assertEquals($expected, $result);
  }

}