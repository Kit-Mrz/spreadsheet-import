<?php

namespace Mrzkit\SpreadsheetImport\Contracts;

use Illuminate\Support\Collection;

interface InputContract
{
    /**
     * @return Collection
     */
    public function config() : Collection;

    /**
     * @return Collection
     */
    public function header() : Collection;


    /**
     * @return Collection
     */
    public function body() : Collection;

    /**
     * @desc
     * @return ImportContract
     */
    public function getSpreadsheetImportContract() : ImportContract;
}
