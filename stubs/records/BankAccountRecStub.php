<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\records;

use Pantagruel74\MulticurtestBankManagementService\records\BankAccountRecInterface;
use Webmozart\Assert\Assert;

class BankAccountRecStub implements BankAccountRecInterface
{
    private string $id;
    private array $currencies;
    private string $mainCurrency;
    private ?int $lastSummaryTimestamp;

    /**
     * @param string $id
     * @param array $currencies
     * @param string $mainCurrency
     * @param int|null $lastSummaryTimestamp
     */
    public function __construct(
        string $id,
        array $currencies,
        string $mainCurrency,
        ?int $lastSummaryTimestamp
    ) {
        Assert::allString($currencies);
        Assert::inArray($mainCurrency, $currencies);
        $this->id = $id;
        $this->currencies = $currencies;
        $this->mainCurrency = $mainCurrency;
        $this->lastSummaryTimestamp = $lastSummaryTimestamp;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrencyIds(): array
    {
        return $this->currencies;
    }

    public function addCurrencyIds(array $curIds): BankAccountRecInterface
    {
        $c = clone $this;
        $c->currencies = array_merge(
            $this->currencies,
            $curIds
        );
        return $c;
    }

    public function removeCurrencyIds(array $curIds): BankAccountRecInterface
    {
        $c = clone $this;
        $c->currencies = array_filter(
            $this->currencies,
            fn(string $cur) => !in_array($cur, $curIds)
        );
        return $c;
    }

    public function getLastSummaryTimestamp(): ?int
    {
        return $this->lastSummaryTimestamp;
    }

    public function changeMainCurrency(string $curId): BankAccountRecInterface
    {
        Assert::inArray($curId, $this->currencies);
        $c = clone $this;
        $c->mainCurrency = $curId;
        return $c;
    }

    public function getMainCurrency(): string
    {
        return $this->mainCurrency;
    }
}