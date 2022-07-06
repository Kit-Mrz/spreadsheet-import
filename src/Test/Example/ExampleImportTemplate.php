<?php

namespace Mrzkit\SpreadsheetImport\Test\Example;

use Mrzkit\SpreadsheetImport\Contracts\ConfigContract;
use Mrzkit\SpreadsheetImport\Contracts\TemplateContract;

class ExampleImportTemplate implements TemplateContract
{
    public function sort() : bool
    {
        return true;
    }

    public function config() : ConfigContract
    {
        return new ExampleImportConfig();
    }

    public function path() : string
    {
        return "path";
    }

    public function name() : string
    {
        return "name";
    }

}
