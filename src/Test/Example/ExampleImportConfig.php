<?php

namespace Mrzkit\SpreadsheetImport\Test\Example;

use Mrzkit\SpreadsheetImport\Contracts\ConfigContract;

class ExampleImportConfig implements ConfigContract
{
    public function getConfig() : array
    {
        return [
            [
                'require' => 1, 'name' => '委托人', 'field' => 'assign_id', 'table' => 'patents', 'type' => 'string', 'class' => \Mrzkit\SpreadsheetImport\Test\Translate\FirstColumnTranslate::class,
            ],
        ];
    }

}
