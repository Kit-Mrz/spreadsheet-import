<?php

namespace Mrzkit\SpreadsheetImport;

use Mrzkit\SpreadsheetImport\Contracts\ParameterContract;

class Parameter implements ParameterContract
{
    /** @var int */
    private $factorId;

    /** @var int */
    private $tenantId;

    /** @var int */
    private $userId;

    public function __construct(int $factorId, int $tenantId, int $userId)
    {
        $this->factorId = $factorId;
        $this->tenantId = $tenantId;
        $this->userId   = $userId;
    }

    public function getFactorId() : int
    {
        return $this->factorId;
    }

    public function getTenantId() : int
    {
        return $this->tenantId;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }
}
