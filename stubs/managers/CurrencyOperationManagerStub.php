<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyOperationManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\records\CurrencyOperationInAccountRequestRecInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\CurrencyOperationInAccountRequestRecStub;
use Webmozart\Assert\Assert;

class CurrencyOperationManagerStub implements CurrencyOperationManagerInterface
{
    private array $operations = [];

    /**
     * @param CurrencyOperationInAccountRequestRecStub[] $operations
     */
    public function __construct(array $operations)
    {
        Assert::allIsAOf($operations,
            CurrencyOperationInAccountRequestRecStub::class);
        $this->operations = $operations;
    }

    public function createBankCorrectionOperation(
        string $accountId,
        AmountInCurrencyValInterface $amountInCurrencyVal
    ): CurrencyOperationInAccountRequestRecInterface {
        return new CurrencyOperationInAccountRequestRecStub(
            $accountId,
            "",
            $amountInCurrencyVal,
            false
        );
    }

    public function saveOperations(array $operations): void
    {
        $this->operations = array_merge(
            $this->operations,
            $operations
        );
    }

    public function getAllOperationsInProcessAfter(
        string $accId,
        string $curId,
        ?int $afterTimestamp
    ): array {
        return array_filter(
            $this->operations,
            fn(CurrencyOperationInAccountRequestRecStub $op)
                => (($op->getAccId() === $accId)
                    && ($op->getAmount()->getCurId() === $curId))
        );
    }
}