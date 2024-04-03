<?php

namespace Pantagruel74\MulticurtestBankManagementService\values;

/**
 * Contract of value-object of amount in some existing currency.
 */
interface AmountInCurrencyValInterface
{
    /**
     * Get id of currency of current object.
     * @return string
     */
    public function getCurId(): string;

    /**
     * Checks is amount of this object positive?
     * @return bool
     */
    public function isPositive(): bool;

    /**
     * Checks is amount of this object zero?
     * @return bool
     */
    public function isZero(): bool;

    /**
     * Reverse amount of this object.
     * @return $this
     */
    public function reverse(): self;
}