<?php

namespace Pantagruel74\MulticurtestBankManagementService\managers;

use Pantagruel74\MulticurtestBankManagementService\records\CurrencyOperationInAccountRequestRecInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;

interface CurrencyOperationManagerInterface
{
    /**
     * @param string $accountId
     * @param AmountInCurrencyValInterface $amountInCurrencyVal
     * @return CurrencyOperationInAccountRequestRecInterface
     */
    public function createBankCorrectionOperation(
        string $accountId,
        AmountInCurrencyValInterface $amountInCurrencyVal
    ): CurrencyOperationInAccountRequestRecInterface;

    /**
     * @param AmountInCurrencyValInterface[] $operations
     * @return void
     */
    public function saveOperations(
        array $operations
    ): void;

    /**
     * @param string $accId
     * @param string $curId
     * @param int|null $afterTimestamp
     * @return CurrencyOperationInAccountRequestRecInterface[]
     */
    public function getAllOperationsInProcessAfter(
        string $accId,
        string $curId,
        ?int $afterTimestamp
    ): array;
}