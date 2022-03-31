<?php

namespace Mrzkit\SpreadsheetImport\Test\Example;

use Mrzkit\SpreadsheetImport\Contracts\ConfigContract;
use Mrzkit\SpreadsheetImport\Contracts\ImportContract;
use Mrzkit\SpreadsheetImport\Translator;
use Illuminate\Support\Collection;

class ExampleImport implements ImportContract
{
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function path() : string
    {
        return $this->path;
    }

    public function config() : ConfigContract
    {
        return new ExampleImportConfig();
    }

    public function handle(Collection $collect, Translator $spreadsheetTranslate) : bool
    {
        return true;
    }

}
