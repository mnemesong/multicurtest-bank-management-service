<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;

interface CurrencyManagerInterface
{
    /**
     * @return string[]
     */
    public function getAllCurrenciesExists(): array;

    /**
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string;

    /**
     * @param AmountInCurrencyValInterface $amount
     * @param string $targetCurrency
     * @return AmountInCurrencyValInterface
     */
    public function convertAmountTo(
        AmountInCurrencyValInterface $amount,
        string $targetCurrency
    ): AmountInCurrencyValInterface;

    /**
     * @param string $fromCur
     * @param CurrencyConversionMultiplierVal $conversionMultipliersTo
     * @return void
     */
    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void;

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @return void
     */
    public function addCurrency(
        string $curId,
        array $conversionMultipliersTo
    ): void;

    /**
     * @param string $curId
     * @return void
     */
    public function switchOffCurrency(
        string $curId
    ): void;
}