<?php

namespace App\FileHandler;

class InputReader extends FileReader
{
  public function __construct(protected string $ext = 'csv')
  {
    parent::__construct();
    $this->files = $this->loadFiles();
  }

  /**
   * @return array<string>
   */
  protected function loadFiles(): array
  {
    // load input directory based in ext
    return glob($this->getStorage() . "/*.{$this->ext}");
  }

  public function getFiles(): array
  {
    return $this->files;
  }
}