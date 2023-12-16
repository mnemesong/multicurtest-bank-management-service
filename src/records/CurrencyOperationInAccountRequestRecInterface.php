<?php

namespace Pantagruel74\MulticurtestBankManagementService\records;

interface CurrencyOperationInAccountRequestRecInterface
{
    public function withDescription(string $desc): self;

    public function asDeclined(): self;

    public function asConfirmed(): self;
}