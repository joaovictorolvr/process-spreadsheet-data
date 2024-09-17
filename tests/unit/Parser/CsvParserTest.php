<?php
use PHPUnit\Framework\TestCase;
use App\Parser\CsvParser;


class CsvParserTest extends TestCase
{
  protected string $testFile;

  protected function setUp(): void
  {
    $this->testFile = __DIR__ . '/test.csv';
    $csvContent = "name,age,city\nJohn,30,New York\nJane,25,Los Angeles";
    file_put_contents($this->testFile, $csvContent);
  }

  protected function tearDown(): void
  {
    if (file_exists($this->testFile)) {
      unlink($this->testFile);
    }
  }

  public function testHeaders()
  {
    $parser = new CsvParser($this->testFile);
    $headers = $parser->headers();
    $this->assertEquals(['name', 'age', 'city'], $headers);
  }

  public function testRows()
  {
    $parser = new CsvParser($this->testFile);
    $rows = [];
    foreach($parser->rows() as $row) {
      $this->assertArrayHasKey('name', $row);
      $this->assertArrayHasKey('age', $row);
      $this->assertArrayHasKey('city', $row);
      $rows[] = $row;
    }
    $expectedRows = [
      ['name' => 'John', 'age' => '30', 'city' => 'New York'],
      ['name' => 'Jane', 'age' => '25', 'city' => 'Los Angeles']
    ];
    $this->assertEquals($expectedRows, $rows);
  }
}