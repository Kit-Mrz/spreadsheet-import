<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\SpreadsheetImport\Exceptions\ConfigException;
use Mrzkit\SpreadsheetImport\Contracts\ConfigContract;
use Illuminate\Support\Collection;

class Configuration
{
    use Conversion;

    /**
     * @var Collection
     */
    protected $configCollect;

    public function __construct(ConfigContract $configContract)
    {
        $this->configCollect = collect($configContract->getConfig());

        if ( !$this->check()) {
            throw new ConfigException();
        }
    }

    /**
     * @return Collection
     */
    public function getConfigCollect() : Collection
    {
        return $this->configCollect;
    }

    /**
     * @desc 检测配置
     * @return bool
     */
    public function check() : bool
    {
        $supportType = [
            'int'    => true,
            'float'  => true,
            'string' => true,
            'date'   => true,
        ];

        foreach ($this->getConfigCollect() as $item) {
            if ( !isset($item["require"]) || !isset($item["name"]) || !isset($item["field"]) || !isset($item["table"]) || !isset($item['type'])) {
                return false;
            }

            if ( !isset($supportType[$item["type"]])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @desc 总数
     * @return int
     */
    public function count() : int
    {
        return $this->getConfigCollect()->count();
    }

    /**
     * @desc 范围
     * @return string
     */
    public function getRange() : string
    {
        $start = static::stringFromColumnIndex(1);

        $end = static::stringFromColumnIndex($this->count());

        $range = "{$start}1:{$end}1";

        return $range;
    }
}
