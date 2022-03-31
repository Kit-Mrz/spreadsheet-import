<?php

namespace Mrzkit\SpreadsheetImport\Test\Translate;

use Mrzkit\SpreadsheetImport\Contracts\TranslateContract;
use Mrzkit\SpreadsheetImport\Translator;
use Illuminate\Support\Collection;

class FirstColumnTranslate implements TranslateContract
{
    public function translate(string $letter, array $header, Translator $spreadsheetTranslate) : Collection
    {
        $columns = $spreadsheetTranslate->columns($letter);

        return collect([]);
    }

}
