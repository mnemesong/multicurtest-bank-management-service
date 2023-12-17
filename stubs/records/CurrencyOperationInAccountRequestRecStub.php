<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\records;

use Pantagruel74\MulticurtestBankManagementService\records\CurrencyOperationInAccountRequestRecInterface;
use Pantagruel74\MulticurtestBankManagementServiceStubs\values\AmountCurrencyValStub;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class CurrencyOperationInAccountRequestRecStub implements
    CurrencyOperationInAccountRequestRecInterface
{
    private string $id;
    private string $description;
    private AmountCurrencyValStub $amount;
    private bool $isDeclined;
    private bool $isConfirmed;
    private string $accId;

    /**
     * @param string $accId
     * @param string $description
     * @param AmountCurrencyValStub $amount
     * @param bool $isDeclined
     * @param bool $isConfirmed
     */
    public function __construct(
        string $accId,
        string $description,
        AmountCurrencyValStub $amount,
        bool $isDeclined = false,
        bool $isConfirmed = false
    ) {
        $this->id = Uuid::uuid4();
        $this->accId = $accId;
        $this->description = $description;
        $this->amount = $amount;
        $this->isDeclined = $isDeclined;
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return string
     */
    public function getAccId(): string
    {
        return $this->accId;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function getAmount(): AmountCurrencyValStub
    {
        return $this->amount;
    }

    public function withDescription(
        string $desc
    ): CurrencyOperationInAccountRequestRecInterface {
        $c = clone $this;
        $c->description = $desc;
        return $c;
    }

    public function asDeclined(): CurrencyOperationInAccountRequestRecInterface
    {
        Assert::false($this->isConfirmed);
        $c = clone $this;
        $c->isDeclined = true;
        return $c;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isDeclined(): bool
    {
        return $this->isDeclined;
    }

    /**
     * @return CurrencyOperationInAccountRequestRecInterface
     */
    public function asConfirmed(): CurrencyOperationInAccountRequestRecInterface
    {
        Assert::false($this->isDeclined);
        $c = clone $this;
        $c->isConfirmed = true;
        return $c;
    }
}