<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\SpreadsheetImport\Contracts\StyleContract;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

class SheetStyle implements StyleContract
{
    /**
     * @desc 模板样式
     * @return Style
     */
    public function style() : Style
    {
        //设置格式
        $sharedStyle = new Style();

        $sharedStyle->applyFromArray(
            [
                // 字体
                'font'      => [
                    'bold' => true,
                ],

                // 对齐
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],

                // 颜色填充
                'fill'      => [
                    'fillType' => Fill::FILL_SOLID,
                    'color'    => ['argb' => 'FFCCFFCC']
                ],

                // 边框
                'borders'   => [
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]
        );

        /**
         * // 粗体
         * //$activeSheet->getStyle($coordinate)->getFont()->setBold(true);
         * // 字体大小
         * //$activeSheet->getStyle($coordinate)->getFont()->setSize(14);
         * // 背景颜色
         * //$activeSheet->getStyle($coordinate)->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color("FF87CEFA"))->setEndColor(new Color("FF87CEFA"));
         */

        return $sharedStyle;
    }
}
