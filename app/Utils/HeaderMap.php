<?php

namespace App\Utils;

abstract class HeaderMap
{
  public function __construct(protected array $map)
  {
  }

  public function bindValues(array $values): array
  {
    $result = [];

    // Inicialize todos os headers no array $result com valores vazios
    foreach ($this->map as $mappedKey => $aliases) {
      $result[$mappedKey] = ""; // Inicializa com valor vazio
    }

    // Preencha os valores existentes conforme o mapeamento
    foreach ($this->map as $mappedKey => $aliases) {
      foreach ($aliases as $alias) {
        if (array_key_exists($alias, $values)) {
          $result[$mappedKey] = $values[$alias];
          break;
        }
      }
    }

    return $result;
  }
  public function getHeaders(): array
  {
    return array_keys($this->map);
  }
  public function getMap(): array
  {
    return $this->map;
  }
}