<?php

namespace Mrzkit\SpreadsheetImport\Test\Example;

use Mrzkit\SpreadsheetImport\ImportInput;
use Mrzkit\SpreadsheetImport\Parameter;
use Mrzkit\SpreadsheetImport\Translator;

class ExampleClient
{
    public static function main()
    {
        $filename = '/tmp/xxxx.xlsx';

        $spreadsheetInput = new ImportInput(new ExampleImport($filename));

        $spreadsheetParameter = new Parameter(1, 2, 3);

        $spreadsheetTranslate = new Translator($spreadsheetInput, $spreadsheetParameter);

        $spreadsheetTranslate->exec();
    }
}
