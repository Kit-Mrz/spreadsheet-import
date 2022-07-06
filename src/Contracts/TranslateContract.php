<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

use Mrzkit\SpreadsheetImport\Translator;
use Illuminate\Support\Collection;

interface TranslateContract
{
    /**
     * @desc 转换
     * @param string $letter 列号
     * @param array $header 配置
     * @param Translator $spreadsheetTranslate
     * @return Collection
     */
    public function translate(string $letter, array $header, Translator $spreadsheetTranslate) : Collection;
}
