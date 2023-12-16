<?php

namespace Pantagruel74\MulticurtestBankManagementServiceTest;

use Pantagruel74\MulticurtestBankManagementService\BankManagementService;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\BankAccountBalanceManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\BankAccountManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\CurrencyManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\CurrencyOperationManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\BankAccountRecStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;
use PHPUnit\Framework\TestCase;

class BankManagementServiceTest extends TestCase
{
    public function testCreateNewCurrency()
    {
        $balanceManager = new BankAccountBalanceManagerStub();
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $operationsManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $service->createNewCurrency("bob", [
            new CurrencyConversionMultiplierVal("RUB", 2),
            new CurrencyConversionMultiplierVal("EUR", 180)
        ], 0);
        $this->assertEquals(["RUB", "EUR", "BOB"],
            $currencyManager->getAllCurrenciesExists());
        $this->assertEquals(
            $currencyManager->convertAmountTo(
                new AmountCurrencyValStub("RUB", 200),
                "BOB"
            )->getVal(),
            100
        );
    }
}