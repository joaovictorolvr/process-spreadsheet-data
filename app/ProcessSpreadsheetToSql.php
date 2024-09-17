<?php
namespace App;

use App\FileHandler\InputReader;
use App\Parser\CsvParser;
use App\Parser\ParseNumbersToXlsx;
use App\Parser\ParseXlsxToCsv;

class ProcessSpreadsheetToSql
{
  private InputReader $inputReader;
  private array $headers;

  public function __construct(InputReader $inputReader)
  {
    $this->inputReader = $inputReader;
  }

  public function convertNumbers(): void
  {
    $this->inputReader
      ->setExt('numbers')
      ->performOperation([ParseNumbersToXlsx::class, 'convert']);
  }

  public function convertXlsxToCsv()
  {
    $this->inputReader
      ->setExt('xlsx')
      ->performOperation([ParseXlsxToCsv::class, 'convert'])
      ->setExt('csv');
  }

  public function joinFiles()
  {
    $this->headers = $this->getHeadersFromInputFiles();
    $this->inputReader->performOperation([CsvParser::class, 'removeHeaders']);
    $combinedFile = $this->inputReader->combineFiles();
    var_dump($this->headers);

  }

  public function getHeadersFromInputFiles()
  {
    $files = $this->inputReader->getFiles();
    $firstFile = $files[0];
    $parser = new CsvParser($firstFile);
    $headers = $parser->headers();
    return $headers;
  }
}