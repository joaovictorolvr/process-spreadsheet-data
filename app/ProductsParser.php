<?php

namespace App;

class ProductsParser implements ParserContract
{

  public static function convert(string $file)
  {
    if (($handle = fopen($file, "r")) !== FALSE) {
      $newFile = basename($file);
      $dir = dirname($file);
      $newFile = $dir . '/../output/' . 'produtos_' . $newFile;
      $newHandle = fopen($newFile, 'w');
      $header = fgetcsv($handle);
      fputcsv($newHandle, $header);
      $productsIndex = array_search('produtos', $header);
      $valueIndex = array_search('valor', $header);
      $customerIdIndex = array_search('id', $header);
      $row = self::row(['produtos' => $productsIndex, 'valor' => $valueIndex, 'id' => $customerIdIndex]);
      $headers = ['customer_id', 'produtos', 'valor'];
      fputcsv($newHandle, $headers);
      while (($data = fgetcsv($handle)) !== FALSE) {
        fputcsv($newHandle, [
          $data[$row['customer_id']],
          $data[$row['produtos']],
          $data[$row['valor']]
        ]);
      }
      fclose($handle);
      fclose($newHandle);
      return $newFile;
    }
  }

  private static function row(array $row)
  {
    return [
      'produtos' => $row['produtos'],
      'customer_id' => $row['id'],
      'valor' => $row['valor']
    ];
  }
}