<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;

interface BankAccountBalanceManagerInterface
{
    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface;
}