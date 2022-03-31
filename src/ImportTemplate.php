<?php

namespace App\Supports\SpreadsheetImport;

use App\Supports\SpreadsheetImport\Contracts\TemplateContract;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportTemplate
{
    use Conversion;

    protected $importTemplateConfigProcessor;

    protected $templateStyle;

    protected $spreadsheet;

    public function __construct(TemplateContract $importTemplateContract)
    {
        $this->importTemplateContract = $importTemplateContract;

        $this->importTemplateConfigProcessor = new Configuration($importTemplateContract->config());

        $this->templateStyle = new SheetStyle();

        $this->spreadsheet = new Spreadsheet();
    }

    /**
     * @desc 写入到 Spreadsheet
     * @return Spreadsheet
     */
    protected function write() : Spreadsheet
    {
        $range = $this->importTemplateConfigProcessor->getRange();

        $sharedStyle = $this->templateStyle->style();

        $activeSheet = $this->spreadsheet->getActiveSheet();

        $activeSheet->duplicateStyle($sharedStyle, $range);

        $headers = $this->importTemplateConfigProcessor->getConfigCollect();

        // Sort
        if ($this->importTemplateContract->sort() && $headers->isNotEmpty()) {
            //
            $leftHeaders = collect([]);

            foreach ($headers as $key => $header) {
                if ($header['require'] > 0) {
                    $leftHeaders->add($header);
                    $headers->forget($key);
                }
            }

            $headers = $leftHeaders->merge($headers);
        }

        if ($headers->isEmpty()) {
            throw new \InvalidArgumentException("参数错误");
        }

        $index = 1;

        foreach ($headers as $header) {
            // 坐标
            $letter = static::stringFromColumnIndex($index++);

            $coordinate = "{$letter}1";

            // 必填
            if ($header['require']) {
                $activeSheet->getStyle($coordinate)->getFont()->getColor()->setARGB(Color::COLOR_RED);
            }

            // 列宽
            $with = (mb_strlen($header['name']) * 4);

            $activeSheet->getColumnDimension($letter)->setWidth($with);

            // 设置值
            $activeSheet->setCellValue($coordinate, $header['name']);
        }

        return $this->spreadsheet;
    }

    /**
     * @desc 保存
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function save() : string
    {
        $xlsx = new Xlsx($this->spreadsheet);

        $filename = $this->importTemplateContract->path() . $this->importTemplateContract->name();

        $savePath = dirname($filename);

        if ( !file_exists($savePath)) {
            mkdir($savePath, 0755, true);
        }

        $xlsx->save($filename);

        return $this->importTemplateContract->name();
    }

    /**
     * @desc 执行
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exec() : string
    {
        $this->write();

        return $this->save();
    }
}
