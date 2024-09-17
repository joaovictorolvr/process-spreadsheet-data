
<?php
use PHPUnit\Framework\TestCase;
use App\Parser\ParseXlsxToCsv;


class ParseXlsxToCsvTest extends TestCase
{
  private $inputFile;
  private $outputFile;

  protected function setUp(): void
  {
    $this->inputFile = __DIR__ . '/test.xlsx';
    $this->outputFile = __DIR__ . '/test.csv';

    // Create a sample XLSX file for testing
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello');
    $sheet->setCellValue('B1', 'World');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($this->inputFile);
  }

  protected function tearDown(): void
  {
    // Clean up the test files
    if (file_exists($this->inputFile)) {
      unlink($this->inputFile);
    }
    if (file_exists($this->outputFile)) {
      unlink($this->outputFile);
    }
  }

  public function testConvert()
  {
    ParseXlsxToCsv::convert($this->inputFile, $this->outputFile);

    $this->assertFileExists($this->outputFile);

    $csvContent = file_get_contents($this->outputFile);
    $this->assertStringContainsString('Hello', $csvContent);
    $this->assertStringContainsString('World', $csvContent);
  }
}