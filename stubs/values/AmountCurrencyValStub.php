<?php

namespace Pantagruel74\MulticurtestBankManagementServiceStubs\values;

use Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface;

class AmountCurrencyValStub implements AmountInCurrencyValInterface
{
    private string $curId;
    private int $val;

    /**
     * @param string $curId
     * @param int $val
     */
    public function __construct(string $curId, int $val)
    {
        $this->curId = $curId;
        $this->val = $val;
    }

    public function getCurId(): string
    {
        return $this->curId;
    }

    public function isPositive(): bool
    {
        return $this->val > 0;
    }

    public function isZero(): bool
    {
        return $this->val === 0;
    }

    public function reverse(): AmountInCurrencyValInterface
    {
        $c = clone $this;
        $c->val = (-1) * $this->val;
        return $c;
    }

    /**
     * @return int
     */
    public function getVal(): int
    {
        return $this->val;
    }

}