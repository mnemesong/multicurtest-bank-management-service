# multicurtest-bank-management-service
Divan.ru test task: Bank management service


## Description
Service, that provides api for global currency changing operations in the bank.

All transactions executes in aggregation:
- BankAccountRec
- AmountCurrencyVal
- CurrencyConversionMultiplierVal


## Source structure
- managers
  - BankAccountBalanceManagerInterface
  - BankAccountManagerInterface
  - CurrencyManagerInterface
- records
  - BankAccountRecInterface
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
     * Command to create a new currency in the bank, with definition
     * of multipliers to all else existing currencies.
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
     * Command to switch off currency in the bank and freeze all amounts
     * in all account in this currency.
     * @param string $curId
     * @param string $switchToDefaultCurId
     * @return void
     */
    public function switchOffCurrency(
        string $curId,
        string $switchToDefaultCurId
    ): void {...}

    /**
     * Command to change conversion multiplier from currency
     * to currency in the bank.
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