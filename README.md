# Laravel Authorize.net Payments

Laravel 13 examples for Authorize.net transactions, refunds, customer profiles, and idempotent application workflows.

```bash
composer install
composer test
```

Configure `AUTHORIZENET_LOGIN_ID`, `AUTHORIZENET_TRANSACTION_KEY`, and `AUTHORIZENET_MODE=sandbox` in the environment. This repository never stores credentials or card data.

```php
$transaction = $gateway->chargeOpaqueData(
    amount: '49.99',
    dataDescriptor: $descriptor,
    dataValue: $opaqueValue,
    invoiceNumber: 'INV-1001',
);
```

Use Accept.js or Accept Hosted so raw card details never reach your Laravel server.
