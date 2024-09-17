<?php

namespace App\DatabaseSchema;

class DatabaseSchema
{
  private $tables = [];

  public function addTable(SchemaTable $table)
  {
    $this->tables[] = $table;
  }

  public function generateSQL()
  {
    $sql = '';
    foreach ($this->tables as $table) {
      $sql .= "CREATE TABLE `{$table->getName()}` (\n";
      foreach ($table->getColumns() as $column => $definition) {
        $sql .= "    `{$column}` {$definition},\n";
      }
      foreach ($table->getForeignKeys() as $foreignKey) {
        $sql .= "    FOREIGN KEY (`{$foreignKey['column']}`) REFERENCES `{$foreignKey['referenced_table']}` (`{$foreignKey['referenced_column']}`),\n";
      }
      $sql = rtrim($sql, ",\n") . "\n);\n\n";
    }
    return rtrim($sql, "\n");
  }

  private function createTableSQL(SchemaTable $table, &$createdTables, &$tableSQLs)
  {
    if (in_array($table->getName(), $createdTables)) {
      return;
    }

    foreach ($table->getForeignKeys() as $foreignKey) {
      if (!in_array($foreignKey['referenced_table'], $createdTables)) {
        $referencedTable = $this->findTableByName($foreignKey['referenced_table']);
        if ($referencedTable) {
          $this->createTableSQL($referencedTable, $createdTables, $tableSQLs);
        }
      }
    }

    $tableSQLs[] = $table->createTableSQL();
    $createdTables[] = $table->getName();
  }

  private function findTableByName($tableName)
  {
    foreach ($this->tables as $table) {
      if ($table->getName() === $tableName) {
        return $table;
      }
    }
    return null;
  }

  public static function createTable($tableName, $columns): SchemaTable
  {
    $table = new SchemaTable($tableName, $columns);
    return $table;
  }
}


// $userTable = new SchemaTable('users', [
//   'id' => 'integer PRIMARY KEY',
//   'name' => 'varchar(255)',
//   'email' => 'varchar(255)'
// ]);

// $productTable = new SchemaTable('products', [
//   'id' => 'integer PRIMARY KEY',
//   'user_id' => 'integer',
//   'name' => 'varchar(255)',
//   'price' => 'decimal(10,2)'
// ]);

// $productTable->addForeignKey('user_id', 'users', 'id');

// $databaseSchema = new DatabaseSchema();
// $databaseSchema->addTable($userTable);
// $databaseSchema->addTable($productTable);

// echo $databaseSchema->generateSQL();
