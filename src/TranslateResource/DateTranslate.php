<?php

namespace Mrzkit\SpreadsheetImport\TranslateResource;

use Illuminate\Support\Collection;
use Mrzkit\Exceptions\ImportException;
use Mrzkit\SpreadsheetImport\Contracts\TranslateContract;
use Mrzkit\SpreadsheetImport\Translator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DateTranslate implements TranslateContract
{
    public function translate(string $letter, array $header, Translator $spreadsheetTranslate) : Collection
    {
        $columns = $spreadsheetTranslate->columns($letter);

        $collect = collect([]);

        foreach ($columns as $column) {
            try {
                $time = Date::excelToTimestamp($column);
            } catch (\Exception $e) {
                //
                $time = strtotime($column);

                if ($time === false) {
                    throw new ImportException("请输入规范的时间日期，如：2022-03-15 或 2022/03/15");
                }
            }

            $dateFormat = date("Y-m-d", $time);

            $collect->put($column, ['key' => $dateFormat, 'value' => ""]);
        }

        return $collect;
    }

}
