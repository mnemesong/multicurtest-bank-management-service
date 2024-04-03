<?php

namespace Pantagruel74\MulticurtestBankManagementService\records;

/**
 * Contract for record of bank account entity
 */
interface BankAccountRecInterface
{
    /**
     * Gets id string value.
     * @return string
     */
    public function getId(): string;

    /**
     * Gets array of all currencies, available on account.
     * @return string[]
     */
    public function getCurrencyIds(): array;

    /**
     * Command to add new currencies as available on account,
     * by currencies, switched on in the bank.
     * @param string[] $curIds
     * @return $this
     */
    public function addCurrencyIds(array $curIds): self;

    /**
     * Command to remove some currencies, available on account.
     * @param string[] $curIds
     * @return $this
     */
    public function removeCurrencyIds(array $curIds): self;

    /**
     * I'm making the guess that Account balance by currency is calculated
     * by formula CurrentState = LastSummaryBalance + SumOfAllOperationsAfterLastSummary.
     * This method requests timestamp of last summary in some currency.
     * @param string $curId
     * @return int|null
     */
    public function getLastSummaryTimestamp(string $curId): ?int;

    /**
     * Command to change main currency on account,
     * from currencies, available on account.
     * @param string $curId
     * @return $this
     */
    public function changeMainCurrency(string $curId): self;

    /**
     * Requests main currency of account.
     * @return string
     */
    public function getMainCurrency(): string;

}