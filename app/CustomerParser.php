<?php

namespace App;


class CustomerParser implements ParserContract
{
  public static function filter(array $row, string $key)
  {
    /**
     * @var array<string> $itemsToRemove
     */
    $itemsToRemove = include __DIR__ . '/Filters/ProductFilter.php';
    $value = $row[$key];
    // Verificar qualquer item dentro de itemsToRemove existe em alguma parte do value se existir então retornar falso
    if (empty($value)) {
      return false;
    }
    foreach ($itemsToRemove as $item) {
      if (stripos($value, $item) !== false) {
        return false;
      }
    }
    return true;
  }
  public static function convert(string $file)
  {
    if (($handle = fopen($file, "r")) !== FALSE) {
      $newFile = basename($file);
      $dir = dirname($file);
      $newFile = $dir . '/../output/' . 'clientes_' . $newFile;
      $newHandle = fopen($newFile, 'w');
      $header = fgetcsv($handle);
      array_unshift($header, 'id');
      fputcsv($newHandle, $header);
      $productsIndex = array_search('produtos', $header);
      $trackingCodeIndex = array_search('rastreio', $header);
      $id = 1;
      while (($data = fgetcsv($handle)) !== FALSE) {
        if (!self::filter($data, $productsIndex - 1)) {
          continue;
        }
        if (empty($data[$trackingCodeIndex - 1])) {
          $data[$trackingCodeIndex - 1] = self::generateRandomTrackingCode();
        }
        array_unshift($data, $id++);
        fputcsv($newHandle, $data);
      }
      fclose($handle);
      fclose($newHandle);

      // Remover duplicados e obter contagem de clientes únicos
      $uniqueCount = self::removeDuplicates($newFile);

      // change file name to include the number of rows
      $name = $dir . '/../output/' . 'clientes_' . $uniqueCount . '_' . date('Y-m-d') . '.csv';
      rename($newFile, $name);
      return $name;
    }
  }

  private static function removeDuplicates(string $file)
  {
    if (($handle = fopen($file, "r")) !== FALSE) {
      $newFile = basename($file);
      $dir = dirname($file);
      $newFile = $dir . '/../output/' . 'clientes_unicos_' . $newFile;
      $newHandle = fopen($newFile, 'w');
      $header = fgetcsv($handle);
      fputcsv($newHandle, $header);

      $phoneIndex = array_search('telefone', $header);
      $uniqueCustomers = [];
      $uniqueCount = 0;

      while (($data = fgetcsv($handle)) !== FALSE) {
        $phone = $data[$phoneIndex];
        if (!isset($uniqueCustomers[$phone])) {
          $uniqueCustomers[$phone] = $data;
          fputcsv($newHandle, $data);
          $uniqueCount++;
        }
      }

      fclose($handle);
      fclose($newHandle);
      rename($newFile, $file);

      return $uniqueCount;
    }
    return 0;
  }
  private static function generateRandomTrackingCode()
  {
    return 'PP' . rand(10000000000, 99999999999) . 'BR';
  }
}