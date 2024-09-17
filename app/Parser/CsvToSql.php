<?php

namespace App\Parser;


class CsvToSql extends CsvParser
{
  private array $sqlColumns;
  public function __construct(protected string $file, array $sqlColumns, private string $tableName)
  {
    parent::__construct($file);
    $this->sqlColumns = $sqlColumns;
  }
  public function toSql()
  {
    $this->batchInsert();
  }

  private function values()
  {
    foreach (parent::rows() as $row) {
      $values = [];
      foreach ($this->sqlColumns as $column) {
        $values[] = empty($row[$column]) ? "NULL" : $row[$column];
      }
      yield $values;
    }
  }

  public function rows()
  {
    foreach ($this->values() as $row) {
      yield "(" . implode(", ", $row) . ")";
    }
  }

  public function columns()
  {
    return "(" . implode(", ", $this->sqlColumns) . ")";
  }

  private function insert(array $values)
  {
    $sql = "INSERT INTO {$this->tableName} {$this->columns()} VALUES\n" . implode(",\n", $values) . ";";
    echo $sql . "\n";
  }

  private function batchInsert(int $batchSize = 1000)
  {
    $values = [];
    $count = 0;
    foreach ($this->values() as $row) {
      $values[] = "(" . implode(", ", $row) . ")";
      $count++;
      if ($count === $batchSize) {
        $this->insert($values);
        $values = [];
        $count = 0;
      }
    }
    if (!empty($values)) {
      $this->insert($values);
    }
  }


}