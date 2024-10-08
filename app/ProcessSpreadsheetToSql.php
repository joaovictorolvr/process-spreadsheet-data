<?php
namespace App;

use App\FileHandler\InputReader;
use App\Parser\CsvParser;
use App\Parser\ParseNumbersToXlsx;
use App\Parser\ParseXlsxToCsv;
use App\Utils\HeaderMap;

class ProcessSpreadsheetToSql
{
  private InputReader $inputReader;
  private array $headers = [];

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
      ->performOperation([ParseXlsxToCsv::class, 'convert']);
    $this->inputReader->setExt('csv');
  }

  public function mapAndCombineFiles(HeaderMap $mapper)
  {
    $this->inputReader->setExt('csv');
    return $this->inputReader->combineFiles([$mapper, 'bindValues'], $mapper->getHeaders());
  }

  public static function processCsvFile(ParserContract $parser, string $file)
  {
    return $parser::convert($file);
  }

  private function getHeadersFromInputFiles()
  {
    $this->inputReader->setExt('csv');
    return $this->inputReader->performOperation([CsvParser::class, 'headers']);
  }

  public function getHeaders()
  {
    $headers = [];
    array_push($headers, $this->getHeadersFromInputFiles());
    $this->headers = $headers;
    return $this->headers;
  }

  public function toSql(string $inputFile)
  {

  }
}