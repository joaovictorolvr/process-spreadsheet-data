<?php

namespace App\FileHandler;

class InputReader extends FileReader
{
  public function __construct(protected string $ext = 'csv', string $storageDir)
  {
    $this->setStorageDir($storageDir);
    $this->files = $this->loadFiles();
  }

  /**
   * @return array<string>
   */
  protected function loadFiles(): array
  {
    return glob($this->getStorage() . "/*.{$this->ext}");
  }

  public function getStorage(): string
  {
    return parent::getStorage() . $this->inputFilesPath;
  }

  public function setExt(string $ext): FileReader
  {
    parent::setExt($ext);
    $this->files = $this->loadFiles();
    return $this;
  }
}