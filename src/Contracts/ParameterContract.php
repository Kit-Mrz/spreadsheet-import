<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

interface ParameterContract
{
    /**
     * @desc 分表因子
     * @return int
     */
    public function getFactorId() : int;

    /**
     * @desc 租户ID
     * @return int
     */
    public function getTenantId() : int;

    /**
     * @desc 用户ID
     * @return int
     */
    public function getUserId() : int;
}
