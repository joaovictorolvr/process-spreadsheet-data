<?php

require __DIR__ . '../../vendor/autoload.php';
ini_set('memory_limit', '2048M');

const STORAGE_DIR = __DIR__ . '/../storage/';
const PYTHON = __DIR__ . '/../venv/bin/python3';
const XLSX2_CSV = '/home/jvtuta/.local/bin/xlsx2csv';

$inputHeader = new App\FileHandler\InputReader('xlsx', STORAGE_DIR);
$inputHeader->createDirs();

$processSpreadsheet = new App\ProcessSpreadsheetToSql($inputHeader);
$processSpreadsheet->convertNumbers();
$processSpreadsheet->convertXlsxToCsv();

$inputHeader->setExt('csv');
$processSpreadsheet->joinFiles();





