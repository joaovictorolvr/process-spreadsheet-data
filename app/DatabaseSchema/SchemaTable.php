<?php

namespace App\DatabaseSchema;

class SchemaTable
{
  private $tableName;
  private $columns;
  private $foreignKeys = [];

  public function __construct($name, array $columns)
  {
    $this->tableName = $name;
    $this->columns = $columns;
  }

  public function addForeignKey($column, $referencedTable, $referencedColumn)
  {
    $this->foreignKeys[] = [
      'column' => $column,
      'referenced_table' => $referencedTable,
      'referenced_column' => $referencedColumn
    ];
  }

  public function createTableSQL()
  {
    $columnsSQL = [];

    foreach ($this->columns as $columnName => $columnType) {
      $columnsSQL[] = "`$columnName` $columnType";
    }

    foreach ($this->foreignKeys as $foreignKey) {
      $columnsSQL[] = "FOREIGN KEY (`{$foreignKey['column']}`) REFERENCES `{$foreignKey['referenced_table']}` (`{$foreignKey['referenced_column']}`)";
    }

    $columnsString = implode(",\n    ", $columnsSQL);

    $sql = "CREATE TABLE `{$this->tableName}` (\n    $columnsString\n);";
    return $sql;
  }

  public function showSchema()
  {
    echo "Tabela: {$this->tableName}\n";
    echo "Colunas:\n";
    foreach ($this->columns as $columnName => $columnType) {
      echo "- $columnName: $columnType\n";
    }
    if (!empty($this->foreignKeys)) {
      echo "Relacionamentos:\n";
      foreach ($this->foreignKeys as $foreignKey) {
        echo "- {$foreignKey['column']} -> {$foreignKey['referenced_table']}({$foreignKey['referenced_column']})\n";
      }
    }
  }

  public function getName()
  {
    return $this->tableName;
  }

  public function getForeignKeys()
  {
    return $this->foreignKeys;
  }

  public function getColumns()
  {
    return $this->columns;
  }
}

// // Exemplo de uso
// $table = new SchemaTable('orders', [
//     'id' => 'integer PRIMARY KEY',
//     'user_id' => 'integer',
//     'total' => 'decimal(10,2)'
// ]);

// $table->addForeignKey('user_id', 'users', 'id');

// echo $table->createTableSQL();
// echo "\n\n";

// $table->showSchema();
