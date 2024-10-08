<?php

namespace App\Parser;

class ParseXlsxToCsv
{
  public static function convert(string $inputFile)
  {
    $outputFile = str_replace('.xlsx', '.csv', $inputFile);

    $command = escapeshellcmd(XLSX2_CSV . " '$inputFile' '$outputFile'");

    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
      throw new \Exception("Erro na conversão do arquivo. Código de erro: $returnVar");
    }

    unlink($inputFile);

    return $outputFile;
  }
}