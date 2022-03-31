<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

use Mrzkit\SpreadsheetImport\Translator;
use Illuminate\Support\Collection;

interface ImportContract
{
    /**
     * @desc 要导入的文档路径
     * @return string
     */
    public function path() : string;

    /**
     * @desc 配置
     * @return ConfigContract
     */
    public function config() : ConfigContract;

    /**
     * @desc 处理
     * @param Collection $collect
     * @param \App\Supports\SpreadsheetImport\Translator $spreadsheetTranslate
     * @return mixed
     */
    public function handle(Collection $collect, Translator $spreadsheetTranslate) : bool;
}
