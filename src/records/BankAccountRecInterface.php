<?php

namespace Pantagruel74\MulticurtestBankManagementService\records;

interface BankAccountRecInterface
{
    public function getId(): string;

    /**
     * @return string[]
     */
    public function getCurrencyIds(): array;

    /**
     * @param string[] $curIds
     * @return $this
     */
    public function addCurrencyIds(array $curIds): self;

    /**
     * @param string[] $curIds
     * @return $this
     */
    public function removeCurrencyIds(array $curIds): self;

    /**
     * @param string $curId
     * @return int|null
     */
    public function getLastSummaryTimestamp(string $curId): ?int;

    /**
     * @param string $curId
     * @return $this
     */
    public function changeMainCurrency(string $curId): self;

    /**
     * @return string
     */
    public function getMainCurrency(): string;

}