<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\managers;

use Pantagruel74\MulticurtestBankManagementService\managers\BankAccountMangerInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\records\BankAccountRecStub;
use Webmozart\Assert\Assert;

class BankAccountManagerStub implements BankAccountMangerInterface
{
    private array $accs = [];

    /**
     * @param BankAccountRecStub[] $accs
     */
    public function __construct(array $accs)
    {
        Assert::allIsAOf($accs, BankAccountRecStub::class);
        $this->accs = $accs;
    }

    /***
     * @return BankAccountRecStub[]
     */
    public function getAllAccounts(): array
    {
        return $this->accs;
    }

    /**
     * @param BankAccountRecStub[] $accs
     * @return void
     */
    public function saveAccounts(array $accs): void
    {
        Assert::allIsAOf($accs, BankAccountRecStub::class);
        $addAccIds = array_map(
            fn(BankAccountRecStub $acc) => $acc->getId(),
            $this->accs
        );
        $this->accs = array_merge(
            array_filter(
                $this->accs,
                fn(BankAccountRecStub $acc)
                    => !in_array($acc->getId(), $addAccIds)
            ),
            $accs
        );
    }
}