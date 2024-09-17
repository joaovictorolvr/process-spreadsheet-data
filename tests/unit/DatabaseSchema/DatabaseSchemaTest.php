<?php

use PHPUnit\Framework\TestCase;
use App\DatabaseSchema\DatabaseSchema;
use App\DatabaseSchema\SchemaTable;

class DatabaseSchemaTest extends TestCase
{
  public function testAddTable()
  {
    $databaseSchema = new DatabaseSchema();
    $table = new SchemaTable('users', [
      'id' => 'integer PRIMARY KEY',
      'name' => 'varchar(255)',
      'email' => 'varchar(255)'
    ]);

    $databaseSchema->addTable($table);

    $reflection = new ReflectionClass($databaseSchema);
    $property = $reflection->getProperty('tables');
    $property->setAccessible(true);
    $tables = $property->getValue($databaseSchema);

    $this->assertCount(1, $tables);
    $this->assertSame($table, $tables[0]);
  }

  public function testGenerateSQL()
  {
    $databaseSchema = new DatabaseSchema();
    $userTable = new SchemaTable('users', [
      'id' => 'integer PRIMARY KEY',
      'name' => 'varchar(255)',
      'email' => 'varchar(255)'
    ]);

    $productTable = new SchemaTable('products', [
      'id' => 'integer PRIMARY KEY',
      'user_id' => 'integer',
      'name' => 'varchar(255)',
      'price' => 'decimal(10,2)'
    ]);

    $productTable->addForeignKey('user_id', 'users', 'id');

    $databaseSchema->addTable($userTable);
    $databaseSchema->addTable($productTable);

    $expectedSQL = "CREATE TABLE `users` (\n" .
      "    `id` integer PRIMARY KEY,\n" .
      "    `name` varchar(255),\n" .
      "    `email` varchar(255)\n" .
      ");\n\n" .
      "CREATE TABLE `products` (\n" .
      "    `id` integer PRIMARY KEY,\n" .
      "    `user_id` integer,\n" .
      "    `name` varchar(255),\n" .
      "    `price` decimal(10,2),\n" .
      "    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)\n" .
      ");";

    $this->assertEquals($expectedSQL, $databaseSchema->generateSQL());
  }

  public function testFindTableByName()
  {
    $databaseSchema = new DatabaseSchema();
    $table = new SchemaTable('users', [
      'id' => 'integer PRIMARY KEY',
      'name' => 'varchar(255)',
      'email' => 'varchar(255)'
    ]);

    $databaseSchema->addTable($table);

    // Use reflection to access the private method
    $reflection = new ReflectionClass($databaseSchema);
    $method = $reflection->getMethod('findTableByName');
    $method->setAccessible(true);

    // Invoke the private method
    $foundTable = $method->invoke($databaseSchema, 'users');
    $this->assertSame($table, $foundTable);

    $notFoundTable = $method->invoke($databaseSchema, 'nonexistent');
    $this->assertNull($notFoundTable);
  }
}

