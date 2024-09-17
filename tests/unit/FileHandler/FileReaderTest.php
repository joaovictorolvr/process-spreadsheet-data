<?php

use PHPUnit\Framework\TestCase;
use App\FileHandler\FileReader;

class ConcreteFileReader extends FileReader
{
  protected string $combinedFilePrefix = 'combined';
  protected string $ext = 'txt';
  protected function loadFiles(): array
  {
    // Implementação simples para o teste
    return $this->files;
  }

  public function setFiles(array $files): void {
    $this->files = $files;
  }
}

class FileReaderTest extends TestCase
{
  private $fileReader;
  private $testStoragePath;

  protected function setUp(): void
  {
    $this->fileReader = ConcreteFileReader::factory(__DIR__ . '/storage/');
    $this->testStoragePath = $this->fileReader->getStorage();

    // Criando arquivos de teste
    $this->createTestFiles();
  }

  protected function tearDown(): void
  {
    // Removendo o diretório de teste após os testes
    $this->removeDirectory($this->testStoragePath);
  }

  private function createFile(string $path, string $content)
  {
    file_put_contents($path, $content);
    return $path;
  }
  private function createTestFiles()
  {
    $file1 = $this->testStoragePath . 'input/file1.txt';
    $file2 = $this->testStoragePath . 'input/file2.txt';

    $this->createFile($file1, "Conteúdo do arquivo 1\n");
    $this->createFile($file2, "Conteúdo do arquivo 2\n");

    $this->fileReader->setFiles([$file1, $file2]);
  }

  private function removeDirectory($path)
  {
    if (!is_dir($path)) {
      return;
    }
    $items = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($items, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
      $todo = ($file->isDir() ? 'rmdir' : 'unlink');
      $todo($file->getRealPath());
    }
    rmdir($path);
  }

  public function testDirectoriesAreCreated()
  {
    $this->assertDirectoryExists($this->testStoragePath);
    $this->assertDirectoryExists($this->testStoragePath . 'input/');
    $this->assertDirectoryExists($this->testStoragePath . 'output/');
    $this->assertDirectoryExists($this->testStoragePath . 'combined/');
  }

  public function testCombineFiles()
{
    // Ensure the combined files directory exists
    $this->assertDirectoryExists(dirname($this->fileReader->combineFiles()));

    // Invoke the combineFiles method and get the path to the combined file
    $combinedFilePath = $this->invokeMethod($this->fileReader, 'combineFiles');

    // Assert that the combined file exists
    $this->assertFileExists($combinedFilePath);

    // Read the content of the combined file
    $expectedContent = "Conteúdo do arquivo 1\nConteúdo do arquivo 2\n";
    $actualContent = file_get_contents($combinedFilePath);

    // Assert that the content matches the expected content
    $this->assertEquals($expectedContent, $actualContent);
}

  public function testSetStorageDir()
  {
    $newStoragePath = __DIR__ . '/new_storage/';
    $this->fileReader->setStorageDir($newStoragePath);

    $this->assertEquals($newStoragePath, $this->fileReader->getStorage());
    $this->removeDirectory($newStoragePath);
  }

  public function testCreateDirs()
  {
    $newStoragePath = __DIR__ . '/new_storage/';
    $this->fileReader->setStorageDir($newStoragePath)->createDirs();

    $this->assertDirectoryExists($newStoragePath);
    $this->assertDirectoryExists($newStoragePath . 'input/');
    $this->assertDirectoryExists($newStoragePath . 'output/');
    $this->assertDirectoryExists($newStoragePath . 'combined/');
    $this->removeDirectory($newStoragePath);
  }

  private function invokeMethod(&$object, $methodName, array $parameters = [])
  {
    $reflection = new ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
  }
}
