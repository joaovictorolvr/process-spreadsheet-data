<?php

namespace App\FileHandler;

final class OutputReader extends FileReader {

  public function __construct(protected string $ext = 'sql')
  {
    parent::__construct();
    $this->files = $this->loadFiles();
  }

  protected function loadFiles(): array
  {
    return glob($this->getStorage() . "/*.{$this->ext}");
  }

}