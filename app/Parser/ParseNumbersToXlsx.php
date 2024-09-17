<?php

namespace App\Parser;

class ParseNumbersToXlsx
{
  public static function convert(string $inputFile)
  {
    $outputFile = str_replace('.numbers', '.xlsx', $inputFile);
    $pythonScript = __DIR__ . '/numbers_to_xlsx.py';
    $command = escapeshellcmd(PYTHON . " '$pythonScript' '$inputFile' '$outputFile'");

    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
      throw new \Exception("Erro na conversão do arquivo. Código de erro: $returnVar");
    }

    unlink($inputFile);

    return $outputFile;
  }
}
