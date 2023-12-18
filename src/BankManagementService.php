<?php

namespace Pantagruel74\MulticurtestBankManagementService;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountBalanceManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountMangerInterface;
use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\records\BankAccountRecInterface;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Webmozart\Assert\Assert;

class BankManagementService
{
    private BankAccountMangerInterface $bankAccountManger;
    private CurrencyManagerInterface $currencyManager;
    private BankAccountBalanceManagerInterface $bankAccountBalanceManager;

    /**
     * @param BankAccountMangerInterface $bankAccountManger
     * @param CurrencyManagerInterface $currencyManager
     * @param BankAccountBalanceManagerInterface $bankAccountBalanceManager
     */
    public function __construct(
        BankAccountMangerInterface $bankAccountManger,
        CurrencyManagerInterface $currencyManager,
        BankAccountBalanceManagerInterface $bankAccountBalanceManager
    ) {
        $this->bankAccountManger = $bankAccountManger;
        $this->currencyManager = $currencyManager;
        $this->bankAccountBalanceManager = $bankAccountBalanceManager;
    }

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @param int $decimalPosition
     * @return void
     */
    public function createNewCurrency(
        string $curId,
        array $conversionMultipliersTo,
        int $decimalPosition
    ): void {
        Assert::true($decimalPosition >= 0,
            "Decimal position should be positive integer number");
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
        $this->currencyManager
            ->addCurrency($curId, $conversionMultipliersTo, $decimalPosition);
    }

    /**
     * @param string $curId
     * @param string $switchToDefaultCurId
     * @return void
     */
    public function switchOffCurrency(
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
            $this->bankAccountBalanceManager->declineAllOperationsInProcessAfter(
                $acc->getId(),
                $curId,
                $acc->getLastSummaryTimestamp($curId)
            );
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
    public function changeConversionMultiplierForCurrency(
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
            $acc = (in_array($newDefaultCurrencyInAcc, $acc->getCurrencyIds())
                    ? $acc
                    : $acc->addCurrencyIds([$newDefaultCurrencyInAcc]))
                ->changeMainCurrency($newDefaultCurrencyInAcc);
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
            $this->bankAccountBalanceManager
                ->addAndConfirmBalanceCorrectionOperation(
                    $acc->getId(),
                    $frozenBalance->reverse(),
                    "Currency " . $curId . " is switch off"
                        . " correction write off operation"
                );
            $this->bankAccountBalanceManager
                ->addAndConfirmBalanceCorrectionOperation(
                    $acc->getId(),
                    $conversionAmount,
                    "Currency " . $curId . " is switch off"
                        . " correction write in operation"
                );
        }
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
            count($curMultipliers),
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