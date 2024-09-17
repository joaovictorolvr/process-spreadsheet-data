<?php

namespace App\FileHandler;

abstract class FileReader
{
  protected string $storage;
  protected string $combinedFilesPath = 'combined';
  protected string $inputFilesPath = 'input';
  protected string $outputFilesPath = 'output';
  protected string $combinedFilePrefix = 'combined';
  protected string $ext;

  /**
   * @param array<string>
   */
  protected $files = [];
  public function setStorageDir(string $dir): self
  {
    $this->storage = $dir;
    return $this;
  }

  public function createDirs()
  {
    if (!file_exists($this->storage)) {
      mkdir($this->storage);
    }
    if (!file_exists($this->storage . '/' . $this->combinedFilesPath)) {
      mkdir($this->storage . '/' . $this->combinedFilesPath);
    }
    if (!file_exists($this->storage . '/' . $this->inputFilesPath)) {
      mkdir($this->storage . '/' . $this->inputFilesPath);
    }
    if (!file_exists($this->storage . '/' . $this->outputFilesPath)) {
      mkdir($this->storage . '/' . $this->outputFilesPath);
    }
    return $this;
  }

  protected abstract function loadFiles(): array;

  public function combineFiles(): string
  {
    $filePath = $this->createCombinedFile();
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

  private function createCombinedFile(): string
  {
    $combinedFileName = $this->getRandomName();
    return $this->storage . '/' . $this->combinedFilesPath . '/' . $combinedFileName;
  }

  private function getRandomName(): string
  {
    return uniqid($this->combinedFilePrefix) . "." . $this->ext;
  }

  public function getStorage(): string
  {
    return $this->storage;
  }

  public static function factory(string $storage): self
  {
    return (new static())->setStorageDir($storage)->createDirs();
  }

  public function getFiles()
  {
    return $this->files;
  }

  public function performOperation(callable $operation): self
  {
    foreach ($this->getFiles() as $file) {
      $operation($file);
    }
    return $this;
  }

  public function setExt(string $ext): self
  {
    $this->ext = $ext;
    $this->files = $this->loadFiles();
    return $this;
  }
}
