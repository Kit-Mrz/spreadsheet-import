<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\SpreadsheetImport\Exceptions\ImportException;
use Mrzkit\SpreadsheetImport\Contracts\InputContract;
use Mrzkit\SpreadsheetImport\Contracts\ParameterContract;
use Mrzkit\SpreadsheetImport\Contracts\TranslateContract;
use Illuminate\Support\Collection;

class Translator
{
    /**
     * @var InputContract
     */
    private $spreadsheetInputContract;

    /**
     * @var ParameterContract
     */
    private $spreadsheetParameterContract;

    /**
     * @var array
     */
    private $translateResult = [];

    public function __construct(InputContract $spreadsheetInputContract, ParameterContract $spreadsheetParameterContract)
    {
        $this->spreadsheetInputContract = $spreadsheetInputContract;

        $this->spreadsheetParameterContract = $spreadsheetParameterContract;
    }

    /**
     * @return InputContract
     */
    public function getSpreadsheetInputContract() : InputContract
    {
        return $this->spreadsheetInputContract;
    }

    /**
     * @return ParameterContract
     */
    public function getSpreadsheetParameterContract() : ParameterContract
    {
        return $this->spreadsheetParameterContract;
    }

    /**
     * @desc 获取整列数据
     * @param string $letter
     * @return Collection
     */
    public function columns(string $letter) : Collection
    {
        $columns = $this->getSpreadsheetInputContract()->body()->pluck($letter)->unique()->whereNotNull();

        return $columns;
    }

    /**
     * @desc 获取整列数据
     * @param string $letter
     * @return Collection
     */
    public function columnOriginals(string $letter) : Collection
    {
        $columns = $this->getSpreadsheetInputContract()->body()->pluck($letter)->whereNotNull();

        return $columns;
    }

    /**
     * @return array
     */
    public function getTranslateResult() : array
    {
        return $this->translateResult;
    }

    /**
     * @desc 执行
     */
    public function exec()
    {
        $this->launch();

        $this->handle();

        $this->delegate();

        return $this;
    }

    /**
     * @desc 启动
     */
    private function launch()
    {
        foreach ($this->spreadsheetInputContract->header() as $letter => $header) {
            //
            if ( !is_null($header["class"]) && class_exists($header["class"])) {
                //
                $clazz = new $header["class"]($letter, $this);

                if ($clazz instanceof TranslateContract) {
                    // 各有列执行转换后的结果
                    $rs = $clazz->translate($letter, $header, $this);

                    if ($rs->isNotEmpty()) {
                        //
                        $this->translateResult[$letter] = $rs;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @desc 处理
     */
    private function handle()
    {
        $header = $this->getSpreadsheetInputContract()->header();

        $translateResult = $this->getTranslateResult();

        $this->spreadsheetInputContract->body()->transform(function ($row) use ($translateResult, $header){
            //
            foreach ($row as $letter => $cell) {
                //
                $key = $translateResult[$letter][$cell]["key"] ?? null;

                if ( !is_null($key)) {
                    $row[$letter] = $key;
                }

                // 将 letter 转换为表字段
                $configItem = $header->get($letter);

                $fieldName = $configItem['field'] ?? null;

                $row[$fieldName] = $row[$letter];

                unset($row[$letter]);
            }

            return $row;
        });

        return $this;
    }

    /**
     * @desc 委派
     */
    private function delegate()
    {
        $result = $this->spreadsheetInputContract->getSpreadsheetImportContract()->handle($this->spreadsheetInputContract->body(), $this);

        if ( !$result) {
            throw new ImportException();
        }

        return $this;
    }

}
