<?php

use PHPUnit\Framework\TestCase;
use App\DatabaseSchema\SchemaTable;

class SchemaTableTest extends TestCase
{
  public function testCreateTableSQL()
  {
    $table = new SchemaTable('orders', [
      'id' => 'integer PRIMARY KEY',
      'user_id' => 'integer',
      'total' => 'decimal(10,2)'
    ]);

    $table->addForeignKey('user_id', 'users', 'id');

    $expectedSQL = "CREATE TABLE `orders` (\n    `id` integer PRIMARY KEY,\n    `user_id` integer,\n    `total` decimal(10,2),\n    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)\n);";
    $this->assertEquals($expectedSQL, $table->createTableSQL());
  }

  public function testShowSchema()
  {
    $table = new SchemaTable('orders', [
      'id' => 'integer PRIMARY KEY',
      'user_id' => 'integer',
      'total' => 'decimal(10,2)'
    ]);

    $table->addForeignKey('user_id', 'users', 'id');

    $this->expectOutputString("Tabela: orders\nColunas:\n- id: integer PRIMARY KEY\n- user_id: integer\n- total: decimal(10,2)\nRelacionamentos:\n- user_id -> users(id)\n");
    $table->showSchema();
  }

  public function testGetName()
  {
    $table = new SchemaTable('orders', [
      'id' => 'integer PRIMARY KEY',
      'user_id' => 'integer',
      'total' => 'decimal(10,2)'
    ]);

    $this->assertEquals('orders', $table->getName());
  }

  public function testGetForeignKeys()
  {
    $table = new SchemaTable('orders', [
      'id' => 'integer PRIMARY KEY',
      'user_id' => 'integer',
      'total' => 'decimal(10,2)'
    ]);

    $table->addForeignKey('user_id', 'users', 'id');

    $expectedForeignKeys = [
      [
        'column' => 'user_id',
        'referenced_table' => 'users',
        'referenced_column' => 'id'
      ]
    ];

    $this->assertEquals($expectedForeignKeys, $table->getForeignKeys());
  }
}