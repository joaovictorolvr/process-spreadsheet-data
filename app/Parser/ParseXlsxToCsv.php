<?php

namespace App\Parser;

class ParseXlsxToCsv
{
  public static function convert(string $inputFile, string $outputFile)
  {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFile);
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
    $writer->save($outputFile);
  }
}