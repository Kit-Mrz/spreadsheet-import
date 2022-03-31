<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

interface TemplateContract
{
    /**
     * @desc 排序
     * @return bool
     */
    public function sort() : bool;

    /**
     * @desc 配置
     * @return ConfigContract
     */
    public function config() : ConfigContract;

    /**
     * @desc 保存路径
     * @return string
     */
    public function path() : string;

    /**
     * @desc 保存名称
     * @return string
     */
    public function name() : string;
}
