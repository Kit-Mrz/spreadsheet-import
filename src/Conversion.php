<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\Exceptions\ConversionException;

trait Conversion
{
    /**
     * @desc 转换 1 to A
     * @param $columnIndex
     * @return mixed|string
     */
    public static function stringFromColumnIndex(int $columnIndex)
    {
        static $indexCache = [];

        if ( !isset($indexCache[$columnIndex])) {
            $indexValue = $columnIndex;
            $base26     = null;
            do {
                $characterValue = ($indexValue % 26) ? : 26;
                $indexValue     = ($indexValue - $characterValue) / 26;
                $base26         = chr($characterValue + 64) . ($base26 ? : '');
            } while ($indexValue > 0);
            $indexCache[$columnIndex] = $base26;
        }

        return $indexCache[$columnIndex];
    }

    /**
     * @desc 转换 A to 1
     * @param $columnAddress
     * @return float|int|mixed
     * @throws ConversionException
     */
    public static function columnIndexFromString(string $columnAddress)
    {
        static $indexCache = [];

        if (isset($indexCache[$columnAddress])) {
            return $indexCache[$columnAddress];
        }

        static $columnLookup = [
            'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13,
            'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26,
            'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13,
            'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26,
        ];

        if (isset($columnAddress[0])) {
            if ( !isset($columnAddress[1])) {
                $indexCache[$columnAddress] = $columnLookup[$columnAddress];

                return $indexCache[$columnAddress];
            } elseif ( !isset($columnAddress[2])) {
                $indexCache[$columnAddress] = $columnLookup[$columnAddress[0]] * 26 + $columnLookup[$columnAddress[1]];

                return $indexCache[$columnAddress];
            } elseif ( !isset($columnAddress[3])) {
                $indexCache[$columnAddress] = $columnLookup[$columnAddress[0]] * 676 + $columnLookup[$columnAddress[1]] * 26 + $columnLookup[$columnAddress[2]];

                return $indexCache[$columnAddress];
            }
        }

        throw new ConversionException('Column string index can not be ' . ((isset($columnAddress[0])) ? 'longer than 3 characters' : 'empty'));
    }
}
