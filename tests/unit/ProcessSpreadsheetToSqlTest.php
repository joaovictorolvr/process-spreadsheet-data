<?php

use PHPUnit\Framework\TestCase;
use App\ProcessSpreadsheetToSql;
use App\FileHandler\InputReader;
use App\Parser\CsvParser;
use App\Parser\ParseXlsxToCsv;

class ProcessSpreadsheetToSqlTest extends TestCase
{
  protected $processor;

  protected function setUp(): void
  {
    $this->processor = new ProcessSpreadsheetToSql();
  }

  public function testInstanceCreation()
  {
    $this->assertInstanceOf(ProcessSpreadsheetToSql::class, $this->processor);
  }

  public function testConvertXlsxToCsv()
  {
    $inputReaderMock = $this->createMock(InputReader::class);
    $inputReaderMock->expects($this->once())
      ->method('performOperation')
      ->with([ParseXlsxToCsv::class, 'convert']);

    $reflection = new \ReflectionClass($this->processor);
    $property = $reflection->getProperty('inputReader');
    $property->setAccessible(true);
    $property->setValue($this->processor, $inputReaderMock);

    $this->processor->convertXlsxToCsv();
  }

  public function testJoinFiles()
  {
    $files = ['file1.csv', 'file2.csv'];
    // create files 
    foreach ($files as $file) {
      touch(__DIR__ . "/../../storage/input/$file");
    }

    $headers = ['header1', 'header2'];

    $inputReaderMock = $this->createMock(InputReader::class);
    $inputReaderMock->expects($this->once())
      ->method('getFiles')
      ->willReturn($files);

    $csvParserMock = $this->getMockBuilder(CsvParser::class)
      ->setConstructorArgs(['file1.csv'])
      ->onlyMethods(['headers'])
      ->getMock();
    $csvParserMock->expects($this->once())
      ->method('headers')
      ->willReturn($headers);

    $reflection = new \ReflectionClass($this->processor);
    $property = $reflection->getProperty('inputReader');
    $property->setAccessible(true);
    $property->setValue($this->processor, $inputReaderMock);

    $this->expectOutputString(implode(',', $headers));
    $this->processor->joinFiles();
  }
}
