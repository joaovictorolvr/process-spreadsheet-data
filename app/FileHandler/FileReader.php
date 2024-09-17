<?php

namespace App\FileHandler;

abstract class FileReader
{
  protected string $storage;
  private string $inputFilesPath;
  private string $outputFilesPath;
  private string $combinedFilesPath;

  protected string $combinedFilePrefix;

  protected string $ext;

  /**
   * @param array<string>
   */
  protected $files = [];
  public function __construct()
  {
    $this->setStorageDir(__DIR__ . "/../../storage/");
  }

  public function setStorageDir(string $dir): self
  {
    $this->storage = $dir;
    $this->combinedFilesPath = $this->storage . "combined/";
    $this->inputFilesPath = $this->storage . "input/";
    $this->outputFilesPath = $this->storage . "output/";
    return $this;
  }

  public function createDirs() {
    if (!file_exists($this->storage)) {
      mkdir($this->storage);
    }
    if (!file_exists($this->combinedFilesPath)) {
      mkdir($this->combinedFilesPath);
    }
    if (!file_exists($this->inputFilesPath)) {
      mkdir($this->inputFilesPath);
    }
    if (!file_exists($this->outputFilesPath)) {
      mkdir($this->outputFilesPath);
    }
    return $this;
  }

  protected abstract function loadFiles(): array;

  public function combineFiles(): string
  {
    $filePath = $this->generateCombinedFilePath();
    $fileHandle = fopen($filePath, 'w');
    if ($fileHandle === false) {
      throw new \RuntimeException("Unable to open file for writing: $filePath");
    }

    foreach ($this->files as $file) {
      $handle = fopen($file, 'r');
      if ($handle === false) {
        throw new \RuntimeException("Unable to open file for reading: $file");
      }

      while (!feof($handle)) {
        $chunk = fread($handle, 65536);
        if ($chunk === false) {
          throw new \RuntimeException("Error reading file: $file");
        }
        fwrite($fileHandle, $chunk);
      }
      fclose($handle);
    }

    fclose($fileHandle);
    return $filePath;
  }

  private function generateCombinedFilePath(): string
  {
    $combinedFileName = $this->getRandomName();
    return $this->combinedFilesPath . $combinedFileName;
  }

  private function getRandomName(): string
  {
    return uniqid($this->combinedFilePrefix) . "." . $this->ext;
  }

  public function getStorage(): string
  {
    return $this->storage;
  }

  public static function factory(string $storage): self {
    return (new static())->setStorageDir($storage)->createDirs();
  }

  public function performOperation(callable $operation): void {
    foreach ($this->files as $file) {
      $operation($file);
    }
  }
}
