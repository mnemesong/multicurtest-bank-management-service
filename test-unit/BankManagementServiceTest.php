<?php

namespace Pantagruel74\MulticurtestBankManagementServiceTest;

use Pantagruel74\MulticurtestBankManagementService\BankManagementService;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\BankAccountBalanceManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\BankAccountManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\CurrencyManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\managers\CurrencyOperationManagerStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\BankAccountRecStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\CurrencyOperationInAccountRequestRecStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;
use PHPUnit\Framework\TestCase;

class BankManagementServiceTest extends TestCase
{
    public function testCreateNewCurrencyValid()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
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

    public function testCreateNewCurrencyInvalidCuId()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->expectException(\InvalidArgumentException::class);
        $service->createNewCurrency("eur", [
            new CurrencyConversionMultiplierVal("RUB", 2),
            new CurrencyConversionMultiplierVal("EUR", 180)
        ], 0);
    }

    public function testCreateNewCurrencyInvalidConverseMultipliers1()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->expectException(\InvalidArgumentException::class);
        $service->createNewCurrency("bob", [
            new CurrencyConversionMultiplierVal("RUB", 2),
            new CurrencyConversionMultiplierVal("BOB", 180)
        ], 0);
    }

    public function testCreateNewCurrencyInvalidConverseMultipliers2()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->expectException(\InvalidArgumentException::class);
        $service->createNewCurrency("bob", [
            new CurrencyConversionMultiplierVal("RUB", 2),
        ], 0);
    }

    public function testChangeSomeConversionMultiplerValid()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $service->changeConversionMultiplierForCurrency(
            "RUB",
            new CurrencyConversionMultiplierVal("EUR", 1)
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->assertEquals(
            $currencyManager->convertAmountTo(
                new AmountCurrencyValStub("RUB", 200),
                "EUR"
            )->getVal(),
            200
        );
        $this->assertEquals(
            $currencyManager->convertAmountTo(
                new AmountCurrencyValStub("EUR", 200),
                "RUB"
            )->getVal(),
            200
        );
    }

    public function testChangeSomeConversionMultiplerInvalidCurrency()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->expectException(\InvalidArgumentException::class);
        $service->changeConversionMultiplierForCurrency(
            "BOB",
            new CurrencyConversionMultiplierVal("EUR", 1)
        );
    }

    public function testChangeSomeConversionMultiplerInvalidMultiplier()
    {
        $accountManager = new BankAccountManagerStub([]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        $this->expectException(\InvalidArgumentException::class);
        $service->changeConversionMultiplierForCurrency(
            "RUB",
            new CurrencyConversionMultiplierVal("RUB", 2)
        );
    }

    public function testSwitchOffCurrencyValid()
    {
        $acc1Id = "acc1-id";
        $acc2Id = "acc2-id";
        $accountManager = new BankAccountManagerStub([
            new BankAccountRecStub($acc1Id, ["EUR", "RUB"],
                "EUR", null),
            new BankAccountRecStub($acc2Id, ["EUR"],
                "EUR", null)
        ]);
        $currencyManager = new CurrencyManagerStub();
        $operationsManager = new CurrencyOperationManagerStub([
            new CurrencyOperationInAccountRequestRecStub($acc1Id, "",
                new AmountCurrencyValStub("EUR", 100), false, true),
            new CurrencyOperationInAccountRequestRecStub($acc1Id, "",
                new AmountCurrencyValStub("EUR", 100), false, false),
            new CurrencyOperationInAccountRequestRecStub($acc1Id, "",
                new AmountCurrencyValStub("EUR", 500), true, false),
            new CurrencyOperationInAccountRequestRecStub($acc1Id, "",
                new AmountCurrencyValStub("RUB", 10000), false, true),
            new CurrencyOperationInAccountRequestRecStub($acc1Id, "",
                new AmountCurrencyValStub("EUR", 50), false, true),
            new CurrencyOperationInAccountRequestRecStub($acc2Id, "",
                new AmountCurrencyValStub("EUR", 150), false, true),
        ]);
        $balanceManager = new BankAccountBalanceManagerStub($operationsManager);
        $service = new BankManagementService(
            $accountManager,
            $currencyManager,
            $balanceManager
        );
        $this->assertEquals(["RUB", "EUR"],
            $currencyManager->getAllCurrenciesExists());
        // Before operation
        $this->assertEquals(
            $accountManager->findAcc($acc1Id)->getMainCurrency(),
            "EUR"
        );
        $this->assertEquals(
            $accountManager->findAcc($acc1Id)->getMainCurrency(),
            "EUR"
        );
        //Acc1 EUR:
        $this->assertEquals(
            150,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc1Id,"EUR",true)
                ->getVal()
        );
        $this->assertEquals(
            250,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc1Id,"EUR",false)
                ->getVal()
        );
        //Acc1 RUB:
        $this->assertEquals(
            10000,
            $operationsManager->calcCurrencyBalanceInAccount(
                $acc1Id,
                "RUB",
                true
            )->getVal()
        );
        $this->assertEquals(
            10000,
            $operationsManager->calcCurrencyBalanceInAccount(
                $acc1Id,
                "RUB",
                false
            )->getVal()
        );
        //Acc2 EUR:
        $this->assertEquals(
            150,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"EUR",true)
                ->getVal()
        );
        $this->assertEquals(
            150,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"EUR",false)
                ->getVal()
        );
        //Acc2 RUB:
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"RUB",true)
                ->getVal()
        );
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"RUB",false)
                ->getVal()
        );
        //Switching off EUR
        $service->switchOffCurrency("EUR", "RUB");
        $this->assertEquals(["RUB"], $currencyManager->getAllCurrenciesExists());
        // After operation
        $this->assertEquals(
            $accountManager->findAcc($acc1Id)->getMainCurrency(),
            "RUB"
        );
        $this->assertEquals(
            $accountManager->findAcc($acc1Id)->getMainCurrency(),
            "RUB"
        );
        print_r($operationsManager);
        //Acc1 EUR:
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc1Id,"EUR",true)
                ->getVal()
        );
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc1Id,"EUR",false)
                ->getVal()
        );
        //Acc1 RUB:
        $this->assertEquals(
            25000,
            $operationsManager->calcCurrencyBalanceInAccount(
                $acc1Id,
                "RUB",
                true
            )->getVal()
        );
        $this->assertEquals(
            25000,
            $operationsManager->calcCurrencyBalanceInAccount(
                $acc1Id,
                "RUB",
                false
            )->getVal()
        );
        //Acc2 EUR:
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"EUR",true)
                ->getVal()
        );
        $this->assertEquals(
            0,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"EUR",false)
                ->getVal()
        );
        //Acc2 RUB:
        $this->assertEquals(
            15000,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"RUB",true)
                ->getVal()
        );
        $this->assertEquals(
            15000,
            $operationsManager
                ->calcCurrencyBalanceInAccount($acc2Id,"RUB",false)
                ->getVal()
        );
    }

}