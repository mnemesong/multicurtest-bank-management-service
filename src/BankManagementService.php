<?php

namespace Pantagruel74\MulticurtestBankManagementService;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountBalanceManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountMangerInterface;
use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyOperationManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\records\BankAccountRecInterface;
use Pantagruel74\MulticurtestBankManagementService\records\CurrencyOperationInAccountRequestRecInterface;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Webmozart\Assert\Assert;

class BankManagementService
{
    private BankAccountMangerInterface $bankAccountManger;
    private CurrencyManagerInterface $currencyManager;
    private CurrencyOperationManagerInterface $currencyOperationManager;
    private BankAccountBalanceManagerInterface $bankAccountBalanceManager;

    /**
     * @param BankAccountMangerInterface $bankAccountManger
     * @param CurrencyManagerInterface $currencyManager
     * @param CurrencyOperationManagerInterface $currencyOperationManager
     * @param BankAccountBalanceManagerInterface $bankAccountBalanceManager
     */
    public function __construct(
        BankAccountMangerInterface $bankAccountManger,
        CurrencyManagerInterface $currencyManager,
        CurrencyOperationManagerInterface $currencyOperationManager,
        BankAccountBalanceManagerInterface $bankAccountBalanceManager
    ) {
        $this->bankAccountManger = $bankAccountManger;
        $this->currencyManager = $currencyManager;
        $this->currencyOperationManager = $currencyOperationManager;
        $this->bankAccountBalanceManager = $bankAccountBalanceManager;
    }

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @return void
     */
    public function createNewCurrency(
        string $curId,
        array $conversionMultipliersTo
    ): void {
        $allExistsCurrencies = $this->currencyManager->getAllCurrenciesExists();
        $curId = $this->currencyManager->convertNameToNewCurrencyToValid($curId);
        Assert::false(
            in_array($curId, $allExistsCurrencies),
            "Currency " . $curId . " exists yet"
        );
        $this->assertCurrencyMultipliersList(
            $allExistsCurrencies,
            $conversionMultipliersTo
        );
        $this->currencyManager->addCurrency($curId, $conversionMultipliersTo);
    }

    /**
     * @param string $curId
     * @param string $switchToDefaultCurId
     * @return void
     */
    public function switchOffCurrencySupport(
        string $curId,
        string $switchToDefaultCurId
    ): void {
        $allExistsCurrencies = $this->currencyManager->getAllCurrenciesExists();
        Assert::inArray($curId, $allExistsCurrencies,
            "Currency " . $curId . " are not exists");
        Assert::inArray($switchToDefaultCurId, $allExistsCurrencies,
            "Currency " . $switchToDefaultCurId . " are not exists");
        $this->currencyManager->switchOffCurrency($curId);
        $allAccounts = $this->bankAccountManger->getAllAccounts();
        $allAccounts = array_map(
            fn(BankAccountRecInterface $acc) => $this
                ->removeCurrencyFromAccount($acc, $curId, $switchToDefaultCurId),
            $allAccounts
        );
        $this->bankAccountManger->saveAccounts($allAccounts);
        sleep(2);
        foreach ($allAccounts as $acc) {
            $this->declineAllOperationsInProcess($acc, $curId);
        }
        foreach ($allAccounts as $acc) {
            $this->convertCurrencyBalanceToMainCurrency($acc, $curId);
        }
    }

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal $curConversionMultiplier
     * @return void
     */
    public function changeSomeConversionMultipliersForCurrency(
        string $curId,
        CurrencyConversionMultiplierVal $curConversionMultiplier
    ): void {
        $allExistsCurrencies = $this->currencyManager->getAllCurrenciesExists();
        Assert::inArray($curId, $allExistsCurrencies,
            "Currency " . $curId . " is not exists");
        Assert::inArray($curConversionMultiplier->getCurTo(),
            $allExistsCurrencies,
            "Currency " . $curId . " is not exists");
        Assert::notEq($curConversionMultiplier->getCurTo(), $curId,
            "Conversion multiplier currency are equal to target currency");
        $this->currencyManager
            ->setConversionMultiplier($curId, $curConversionMultiplier);
    }

    /**
     * @param BankAccountRecInterface $acc
     * @param string $curId
     * @param string $newDefaultCurrencyInAcc
     * @return BankAccountRecInterface
     */
    private function removeCurrencyFromAccount(
        BankAccountRecInterface $acc,
        string $curId,
        string $newDefaultCurrencyInAcc
    ): BankAccountRecInterface {
        if($acc->getMainCurrency() === $curId) {
            $otherCurrencies = array_values(array_filter(
                $acc->getCurrencyIds(),
                fn(string $c) => ($c !== $curId)
            ));
            if(count($otherCurrencies) === 0) {
                $acc = $acc
                    ->addCurrencyIds([$newDefaultCurrencyInAcc])
                    ->changeMainCurrency($newDefaultCurrencyInAcc);
            } else {
                $acc->changeMainCurrency($otherCurrencies[0]);
            }
        }
        return $acc->removeCurrencyIds([$curId]);
    }

    /**
     * @param BankAccountRecInterface $acc
     * @param string $curId
     * @return void
     */
    private function convertCurrencyBalanceToMainCurrency(
        BankAccountRecInterface $acc,
        string $curId
    ): void {
        $frozenBalance = $this->bankAccountBalanceManager
            ->calcFrozenBalanceInCurrencyInAccount($acc->getId(), $curId);
        if($frozenBalance->isPositive() && !$frozenBalance->isZero()) {
            $conversionAmount = $this->currencyManager
                ->convertAmountTo($frozenBalance, $acc->getMainCurrency());
            $correctionWriteOffOperation = $this->currencyOperationManager
                ->createBankCorrectionOperation(
                    $acc->getId(),
                    $frozenBalance->reverse()
                )
                ->withDescription("Currency " . $curId . " is switch off"
                    . " correction write off operation")
                ->asConfirmed();
            $correctionWriteInOperation = $this->currencyOperationManager
                ->createBankCorrectionOperation(
                    $acc->getId(),
                    $conversionAmount
                )
                ->withDescription("Currency " . $curId . " is switch off"
                    . " correction write in operation")
                ->asConfirmed();
            $this->currencyOperationManager->saveOperations([
                $correctionWriteOffOperation,
                $correctionWriteInOperation,
            ]);
        }
    }

    /**
     * @param BankAccountRecInterface $acc
     * @param string $curId
     * @return void
     */
    private function declineAllOperationsInProcess(
        BankAccountRecInterface $acc,
        string $curId
    ): void {
        $operationsInProcess = $this->currencyOperationManager
            ->getAllOperationsInProcessAfter(
                $acc->getId(),
                $curId,
                $acc->getLastSummaryTimestamp()
            );
        $operationsInProcess = array_map(
            fn(CurrencyOperationInAccountRequestRecInterface $op)
                => $op->asDeclined(),
            $operationsInProcess
        );
        $this->currencyOperationManager->saveOperations($operationsInProcess);
    }

    /**
     * @param string[] $curIds
     * @param CurrencyConversionMultiplierVal[] $curMultipliers
     * @return void
     */
    private function assertCurrencyMultipliersList(
        array $curIds,
        array $curMultipliers
    ): void {
        Assert::eq(
            count($curIds),
            $curMultipliers,
            "Count of multipliers more then count of currencies"
        );
        $curMultiplierIds = array_map(
            fn(CurrencyConversionMultiplierVal $m)
                => $m->getCurTo(),
            $curMultipliers
        );
        foreach ($curIds as $curId) {
            Assert::inArray($curId, $curMultiplierIds,
                "Multiplier for currency " . $curId . " is not defined");
        }
    }
}