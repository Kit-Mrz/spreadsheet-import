<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\Exceptions\ImportException;
use Mrzkit\SpreadsheetImport\Contracts\ImportContract;
use Mrzkit\SpreadsheetImport\Contracts\InputContract;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use function collect;

class ImportInput implements InputContract
{

    /** @var Worksheet */
    protected $activeSheet;

    /** @var Collection 配置集合 */
    protected $config;

    /** @var Collection 头部集合 */
    protected $header;

    /** @var Collection 数据集合 */
    protected $body;

    /** @var ImportContract */
    protected $spreadsheetImportContract;

    public function __construct(ImportContract $spreadsheetImportContract)
    {
        $path = $spreadsheetImportContract->path();

        $configContract = $spreadsheetImportContract->config();

        $configHandler = new Configuration($configContract);

        $this->config = $configHandler->getConfigCollect()->keyBy("name");

        $inputFileType = IOFactory::identify($path);

        $reader = IOFactory::createReader($inputFileType);

        $reader->setReadDataOnly(true);

        $this->activeSheet = $reader->load($path)->getActiveSheet();

        $this->handleHeader();

        $this->handleBody();

        $this->spreadsheetImportContract = $spreadsheetImportContract;
    }

    /**
     * @return Collection
     */
    public function config() : Collection
    {
        return $this->config;
    }

    /**
     * @return Collection
     */
    public function header() : Collection
    {
        return $this->header;
    }

    /**
     * @return Collection
     */
    public function body() : Collection
    {
        return $this->body;
    }

    /**
     * @return ImportContract
     */
    public function getSpreadsheetImportContract() : ImportContract
    {
        return $this->spreadsheetImportContract;
    }

    /**
     * @return Worksheet
     */
    protected function getActiveSheet() : Worksheet
    {
        return $this->activeSheet;
    }

    /**
     * @desc 处理表头
     */
    protected function handleHeader()
    {
        $headerCollect = collect([]);

        foreach ($this->getActiveSheet()->getRowIterator(1, 1) as $row) {
            //
            $rowCellIterator = $row->getCellIterator();

            $rowCellIterator->setIterateOnlyExistingCells(false);

            foreach ($rowCellIterator as $cell) {
                //
                $letter = $cell->getColumn();

                $cellValue = $cell->getValue();

                $configItem = $this->config()->get($cellValue);

                if (is_null($configItem)) {
                    throw new ImportException("您提供的模板与配置不相符，列 {$letter} 的表头 {$cellValue} 不能识别.");
                }

                $headerCollect->put($letter, $configItem);
            }
        }

        $this->header = $headerCollect;
    }

    /**
     * @desc 处理表体
     */
    protected function handleBody()
    {
        $bodyCollect = collect([]);

        foreach ($this->getActiveSheet()->getRowIterator(2) as $index => $row) {
            $tempRow = [];

            $rowCellIterator = $row->getCellIterator();

            $rowCellIterator->setIterateOnlyExistingCells(false);

            foreach ($rowCellIterator as $cell) {
                //
                $letter = $cell->getColumn();
                //
                $configItem = $this->header()->get($letter);
                //
                if (is_null($configItem)) {
                    throw new ImportException("配置错误");
                }
                //
                $cellValue = $cell->getValue();
                //
                if (is_null($cellValue) && $configItem['require']) {
                    throw new ImportException("{$letter}:{$index}是必填项");
                }
                //
                if ( !is_null($cellValue)) {
                    $cellValue = preg_replace('/^\s+|\s+$/', '', $cellValue);
                }

                $tempRow[$letter] = $cellValue;
            }

            $bodyCollect->add($tempRow);
        }

        $this->body = $bodyCollect;
    }
}
