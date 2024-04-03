<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;

/**
 * Contract for Singleton for transactional interactions with bank accounts.
 */
interface BankAccountBalanceManagerInterface
{
    /**
     * Gets frozen balance (balance in some switched off currency)
     * of some account.
     * @param string $accId
     * @param string $curId
     * @return AmountInCurrencyValInterface
     */
    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface;

    /**
     * I'm making the guess, about possibility of existing
     * "manual correction by bank" type of account operations, required description.
     * This method requests this correction for some account to some amount.
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
     * "In process" is the value of state currency operation on accounts.
     * This method commands to decline all operations in some account
     * in some currency after some timestamp.
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