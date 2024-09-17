<?php

namespace App\Utils;

class HeaderMap
{
  public function __construct(protected array $map)
  {
  }

  public function getMap(): array
  {
    return $this->map;
  }

  public function bindValues(array $values): array
  {
    $result = [];
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
}