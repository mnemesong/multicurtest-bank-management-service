<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountBalanceManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;

class BankAccountBalanceManagerStub implements
    BankAccountBalanceManagerInterface
{
    private CurrencyOperationManagerStub $currencyOperationManager;

    /**
     * @param CurrencyOperationManagerStub $currencyOperationManager
     */
    public function __construct(
        CurrencyOperationManagerStub $currencyOperationManager
    ) {
        $this->currencyOperationManager = $currencyOperationManager;
    }

    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface {
        return $this->currencyOperationManager
            ->calcCurrencyBalanceInAccount($accId, $curId, false);
    }
}