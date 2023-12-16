<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\records\BankAccountRecInterface;

interface BankAccountMangerInterface
{
    /**
     * @return BankAccountRecInterface[]
     */
    public function getAllAccounts(): array;

    /**
     * @param BankAccountRecInterface[] $accs
     * @return void
     */
    public function saveAccounts(array $accs): void;
}