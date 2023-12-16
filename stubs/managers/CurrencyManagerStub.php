<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;
use Webmozart\Assert\Assert;

class CurrencyManagerStub implements CurrencyManagerInterface
{
    private array $currencies = [];

    public array $multipiers = [];

    public function __construct()
    {
        $this->currencies = ["RUB", "EUR"];
        $this->multipiers = [
            "RUB" => [
                "EUR" => 0.01,
                "USD" => 0.01,
            ],
            "EUR" => [
                "RUB" => 100,
                "USD" => 1,
            ],
            "USD" => [
                "RUB" => 100,
                "EUR" => 1
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function getAllCurrenciesExists(): array
    {
        return $this->currencies;
    }

    /**
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string
    {
        return strtoupper($newCurId);
    }

    public function convertAmountTo(
        AmountInCurrencyValInterface $amount,
        string $targetCurrency
    ): AmountInCurrencyValInterface {
        /* @var AmountCurrencyValStub $amount */
        $convertMulti = $this->multipiers[$amount->getCurId()][$targetCurrency];
        Assert::notEmpty($convertMulti);
        return new AmountCurrencyValStub(
            $targetCurrency,
            $convertMulti * $amount->getVal()
        );
    }

    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void {
        $multiplier = $conversionMultipliersTo->getMultiplier();
        $inversedMultiplier = 1 / $conversionMultipliersTo->getMultiplier();
        $toCur = $conversionMultipliersTo->getCurTo();
        $this->multipiers[$fromCur][$toCur] = $multiplier;
        $this->multipiers[$toCur][$fromCur] = $inversedMultiplier;
    }

    public function addCurrency(
        string $curId,
        array $conversionMultipliersTo
    ): void {
        Assert::false(in_array($curId, $this->currencies));
        $this->currencies[] = $curId;
        foreach ($conversionMultipliersTo as $m) {
            $this->multipiers[$curId][$m->getCurTo()] = $m->getMultiplier();
            $this->multipiers[$m->getCurTo()][$curId] = (1 / $m->getMultiplier());
        }
    }

    public function switchOffCurrency(string $curId): void
    {
        $this->currencies = array_filter(
            $this->currencies,
            fn(string $c) => $c !== $curId
        );
    }
}