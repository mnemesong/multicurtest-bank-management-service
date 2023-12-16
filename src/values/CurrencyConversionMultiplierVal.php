<?php

namespace Pantagruel74\MulticurtestBankManagementService\values;

class CurrencyConversionMultiplierVal
{
    private string $curTo;
    private float $multiplier;

    /**
     * @param string $curTo
     * @param float $multiplier
     */
    public function __construct(string $curTo, float $multiplier)
    {
        $this->curTo = $curTo;
        $this->multiplier = $multiplier;
    }

    /**
     * @return string
     */
    public function getCurTo(): string
    {
        return $this->curTo;
    }

    /**
     * @return float
     */
    public function getMultiplier(): float
    {
        return $this->multiplier;
    }

}