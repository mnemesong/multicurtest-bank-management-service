<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;

interface BankAccountBalanceManagerInterface
{
    /**
     * @param string $accId
     * @param string $curId
     * @return AmountInCurrencyValInterface
     */
    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface;

    /**
     * @param string $accountId
     * @param AmountInCurrencyValInterface $amountInCurrencyVal
     * @param string $description
     * @return void
     */
    public function addAndConfirmBalanceCorrectionOperation(
        string $accountId,
        AmountInCurrencyValInterface $amountInCurrencyVal,
        string $description
    ): void;

    /**
     * @param string $accId
     * @param string $curId
     * @param int|null $afterTimestamp
     * @return void
     */
    public function declineAllOperationsInProcessAfter(
        string $accId,
        string $curId,
        ?int $afterTimestamp
    ): void;
}