<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

interface ConfigContract
{
    /**
     * @desc 获取配置
     * @return array
     */
    public function getConfig() : array;
}
