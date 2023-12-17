<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\CurrencyOperationManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\records\CurrencyOperationInAccountRequestRecInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\CurrencyOperationInAccountRequestRecStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;
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

    public function calcCurrencyBalanceInAccount(
        string $accId,
        string $curId,
        bool $onlyConfirmed
    ): AmountCurrencyValStub {
        $statusFilter = $onlyConfirmed
            ? (fn(CurrencyOperationInAccountRequestRecStub $op)
                => (!$op->isDeclined() && $op->isConfirmed()))
            : (fn(CurrencyOperationInAccountRequestRecStub $op)
                => (!$op->isDeclined()));
        $operations = array_filter(
            $this->operations,
            fn(CurrencyOperationInAccountRequestRecStub $op)
                => (($op->getAccId() === $accId)
                    && ($op->getAmount()->getCurId() === $curId)
                    && ($statusFilter($op)))
        );
        $sum = array_reduce(
            $operations,
            fn(int $acc, CurrencyOperationInAccountRequestRecStub $op)
                => ($acc + $op->getAmount()->getVal()),
            0
        );
        return new AmountCurrencyValStub($curId, $sum);
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
        $saveOperationIds = array_map(
            fn(CurrencyOperationInAccountRequestRecStub $op)
                => $op->getId(),
            $operations
        );
        $this->operations = array_merge(
            array_filter(
                $this->operations,
                fn(CurrencyOperationInAccountRequestRecStub $op)
                    => !in_array($op->getId(), $saveOperationIds)
            ),
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
                    && ($op->getAmount()->getCurId() === $curId)
                    && !$op->isDeclined()
                    && !$op->isConfirmed())
        );
    }
}