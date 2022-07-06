<?php

namespace Mrzkit\SpreadsheetImport\TranslateResource;

use Illuminate\Support\Collection;
use Mrzkit\SpreadsheetImport\Exceptions\ImportException;
use Mrzkit\SpreadsheetImport\Contracts\TranslateContract;
use Mrzkit\SpreadsheetImport\Translator;

class IntegerValueTranslate implements TranslateContract
{
    public function translate(string $letter, array $header, Translator $spreadsheetTranslate) : Collection
    {
        $columns = $spreadsheetTranslate->columns($letter);

        $collect = collect([]);

        foreach ($columns as $column) {
            if ( !intval($column)) {
                throw new ImportException("第 {$letter} 列, 值为：{$column}，请输入数字");
            }
        }

        return $collect;
    }

}

