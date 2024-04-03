<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\records\BankAccountRecInterface;

/**
 * Contract for singleton, manages bank accounts.
 */
interface BankAccountMangerInterface
{
    /**
     * Requests all accounts as array.
     * @return BankAccountRecInterface[]
     */
    public function getAllAccounts(): array;

    /**
     * Save array of bank accounts.
     * @param BankAccountRecInterface[] $accs
     * @return void
     */
    public function saveAccounts(array $accs): void;
}