<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

interface ValidateContract
{
    /**
     * @desc 对数据进行校验
     * @param array $rows 要校验的数据集合
     * @return array
     */
    public function validate(array $rows) : array;
}
