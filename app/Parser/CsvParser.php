<?php

namespace App\Parser;

class CsvParser
{
  public function __construct(protected string $file)
  {
    $this->file = $file;
  }

  public function headers()
  {
    $file = fopen($this->file, 'r');
    $headers = fgetcsv($file);
    fclose($file);
    return $headers;
  }
  public function rows()
  {
    $file = fopen($this->file, 'r');
    if ($file === false) {
      throw new \RuntimeException("Unable to open file: {$this->file}");
    }
    $headers = fgetcsv($file);
    if ($headers === false) {
      throw new \RuntimeException("Unable to read headers from file: {$this->file}");
    }
    while (($row = fgetcsv($file)) !== false) {
      yield array_combine($headers, $row);
    }
    fclose($file);
  }

  public static function removeHeaders(string $filePath)
  {
    $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');

    $inputHandle = fopen($filePath, 'r');
    $outputHandle = fopen($tempFilePath, 'w');

    if ($inputHandle === false || $outputHandle === false) {
      throw new \Exception("Unable to open file handles.");
    }

    fgets($inputHandle);

    while (!feof($inputHandle)) {
      $buffer = fread($inputHandle, 8192);
      fwrite($outputHandle, $buffer);
    }

    fclose($inputHandle);
    fclose($outputHandle);

    rename($tempFilePath, $filePath);
  }
}