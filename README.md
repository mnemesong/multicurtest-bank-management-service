# multicurtest-bank-management-service
Divan.ru test task: Bank management service


## Description
Service, that provides api for global currency changing operations in the bank.


## Source structure
- managers
  - BankAccountBalanceManagerInterface
  - BankAccountManagerInterface
  - CurrencyManagerInterface
  - CurrencyOperationManagerInterface
- records
  - BankAccountRecInterface
  - CurrencyOperationInAccountRequestRecInterface
- values
  - AmountCurrencyValInterface
  - CurrencyConversionMultiplierVal
- BankManagementService


## API
```php
<?php
namespace Pantagruel74\MulticurtestBankManagementService;

class BankManagementService
{
    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @param int $decimalPosition
     * @return void
     */
    public function createNewCurrency(
        string $curId,
        array $conversionMultipliersTo,
        int $decimalPosition
    ): void {...}

    /**
     * @param string $curId
     * @param string $switchToDefaultCurId
     * @return void
     */
    public function switchOffCurrency(
        string $curId,
        string $switchToDefaultCurId
    ): void {...}

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal $curConversionMultiplier
     * @return void
     */
    public function changeConversionMultiplierForCurrency(
        string $curId,
        CurrencyConversionMultiplierVal $curConversionMultiplier
    ): void {...}
}
```