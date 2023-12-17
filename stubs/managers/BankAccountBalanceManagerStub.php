<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountBalanceManagerInterface;
use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\CurrencyOperationInAccountRequestRecStub;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;

class BankAccountBalanceManagerStub implements
    BankAccountBalanceManagerInterface
{
    private CurrencyOperationManagerStub $currencyOperationManager;

    /**
     * @param CurrencyOperationManagerStub $currencyOperationManager
     */
    public function __construct(
        CurrencyOperationManagerStub $currencyOperationManager
    ) {
        $this->currencyOperationManager = $currencyOperationManager;
    }

    public function calcFrozenBalanceInCurrencyInAccount(
        string $accId,
        string $curId
    ): AmountInCurrencyValInterface {
        return $this->currencyOperationManager
            ->calcCurrencyBalanceInAccount($accId, $curId, false);
    }

    public function addAndConfirmBalanceCorrectionOperation(
        string $accountId,
        AmountInCurrencyValInterface $amountInCurrencyVal,
        string $description
    ): void {
        $op = $this->currencyOperationManager
            ->createBankCorrectionOperation($accountId, $amountInCurrencyVal)
            ->withDescription("Balance correction operation")
            ->asConfirmed();
        $this->currencyOperationManager
            ->saveOperations([$op]);
    }

    public function declineAllOperationsInProcessAfter(
        string $accId,
        string $curId,
        ?int $afterTimestamp
    ): void  {
        $operations = array_map(
            fn(CurrencyOperationInAccountRequestRecStub $op)
                => $op->asDeclined(),
            $this->currencyOperationManager
                ->getAllOperationsInProcessAfter($accId, $curId, $afterTimestamp)
        );
        $this->currencyOperationManager->saveOperations($operations);
    }
}