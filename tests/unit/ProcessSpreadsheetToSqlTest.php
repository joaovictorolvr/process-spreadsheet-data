<?php
use PHPUnit\Framework\TestCase;
use App\ProcessSpreadsheetToSql;
use App\FileHandler\InputReader;
use App\Parser\CsvParser;
use App\Parser\ParseXlsxToCsv;

class ProcessSpreadsheetToSqlTest extends TestCase
{
  public function testConvertXlsxToCsv()
  {
    // Create a mock of InputReader
    $inputReaderMock = $this->createMock(InputReader::class);

    // Expect the performOperation method to be called once with the specified parameters
    $inputReaderMock->expects($this->once())
      ->method('performOperation')
      ->with([ParseXlsxToCsv::class, 'convert']);

    // Pass the mock to the ProcessSpreadsheetToSql constructor
    $processSpreadsheetToSql = new ProcessSpreadsheetToSql($inputReaderMock);

    // Call the method to test
    $processSpreadsheetToSql->convertXlsxToCsv();
  }

  public function testJoinFiles()
  {
    $headers = ['header1', 'header2', 'header3'];

    // Mock InputReader
    $inputReaderMock = $this->createMock(InputReader::class);
    // Create an instance of ProcessSpreadsheetToSql with the mocked InputReader
    $processSpreadsheetToSql = $this->getMockBuilder(ProcessSpreadsheetToSql::class)
      ->setConstructorArgs([$inputReaderMock])
      ->onlyMethods(['getHeadersFromInputFiles'])
      ->getMock();


    // Mock getHeadersFromInputFiles method
    $processSpreadsheetToSql->expects($this->once())
      ->method('getHeadersFromInputFiles')
      ->willReturn($headers);

    // Expect the output to be the headers joined by commas
    $this->expectOutputString(implode(',', $headers));

    // Call the method to test
    $processSpreadsheetToSql->joinFiles();
  }
}
