<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountBalanceManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;

class BankAccountBalanceManagerStub implements
    BankAccountBalanceManagerInterface
{
    private $accounts = [];

    public function setAccCurrencyBalance(
        string $accId,
        AmountInCurrencyValInterface $amountInCurrencyVal
    ): void {
        if (!key_exists($accId, $this->accounts)) {
            $this->accounts[$accId] = [];
        }
        $this->accounts[$accId][$amountInCurrencyVal->getCurId()] =
            $amountInCurrencyVal;
    }

    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface {
        if (!key_exists($accId, $this->accounts)) {
            return new AmountCurrencyValStub($curId, 0);
        }
        if (!key_exists($curId, $this->accounts[$accId])) {
            return new AmountCurrencyValStub($curId, 0);
        }
        return $this->accounts[$accId][$curId];
    }
}