<?php

namespace Pantagruel74\MulticurtestBankManagementService\values;

interface AmountInCurrencyValInterface
{
    public function getCurId(): string;
    public function isPositive(): bool;
    public function isZero(): bool;
    public function reverse(): self;
}