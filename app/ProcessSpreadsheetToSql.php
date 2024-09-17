<?php

namespace App;

use App\FileHandler\InputReader;
use App\Parser\CsvParser;
use App\Parser\ParseXlsxToCsv;
class ProcessSpreadsheetToSql
{
  private InputReader $inputReader;

  public function __construct()
  {
    $this->inputReader = new InputReader();
  }
  public function convertXlsxToCsv()
  {
    $this->inputReader = new InputReader('xlsx');
    $this->inputReader->performOperation([ParseXlsxToCsv::class, 'convert']);

  }
  public function joinFiles()
  {
    $inputReader = new InputReader();
    $files = $inputReader->getFiles();
    var_dump($files);
    $headersFromFirstFile = (new CsvParser(array_shift($files)))->headers();
    echo $headersFromFirstFile;
    //$inputReader->performOperation([CsvParser::class, 'removeHeaders']);
  }
}