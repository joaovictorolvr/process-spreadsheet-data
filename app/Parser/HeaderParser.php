<?php

namespace App\Parser;

class HeaderParser
{
  public static function parse(string $inputFile)
  {
    $firstLine = self::getFirstLine($inputFile);
    $headers = str_getcsv($firstLine);
    return $headers;
  }

  public static function parseFiles(array $inputFiles)
  {
    $headers = [];
    foreach ($inputFiles as $inputFile) {
      $headers[] = self::parse($inputFile);
    }
    return $headers;
  }

  // get first line of a file

  private static function getFirstLine(string $inputFile)
  {
    $file = fopen($inputFile, 'r');
    $firstLine = fgets($file);
    fclose($file);
    return $firstLine;
  }
}