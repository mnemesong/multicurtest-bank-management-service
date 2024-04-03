<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;

/**
 * Contract of singleton, that manages currencies, available in the bank,
 * and conversion multipliers of them.
 */
interface CurrencyManagerInterface
{
    /**
     * Request all existing currencies as array.
     * @return string[]
     */
    public function getAllCurrenciesExists(): array;

    /**
     * I'm making the guess, that not every name are valid to currency id.
     * (It will required for values equals asserting)
     * And this method store some list of rules of valid currency name,
     * and may convert any name as a valid variant.
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string;

    /**
     * Convert amount in some currency to amount in some other , exists currency.
     * @param AmountInCurrencyValInterface $amount
     * @param string $targetCurrency
     * @return AmountInCurrencyValInterface
     */
    public function convertAmountTo(
        AmountInCurrencyValInterface $amount,
        string $targetCurrency
    ): AmountInCurrencyValInterface;

    /**
     * Commands to set conversion multiplier from some exists currency to
     * some other exists currency.
     * @param string $fromCur
     * @param CurrencyConversionMultiplierVal $conversionMultipliersTo
     * @return void
     */
    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void;

    /**
     * Adds a new currency to the bank.
     * Adding requires to define conversion multipliers to all exists currencies.
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @param int $decimalPosition
     * @return void
     */
    public function addCurrency(
        string $curId,
        array $conversionMultipliersTo,
        int $decimalPosition
    ): void;

    /**
     * Switch off some exists currencies and freeze all balances in all accounts
     * in this currency.
     * @param string $curId
     * @return void
     */
    public function switchOffCurrency(
        string $curId
    ): void;
}