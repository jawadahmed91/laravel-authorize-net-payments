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

## Add this to an existing Laravel project

1. Copy `src/AuthorizeNetGateway.php` to `app/Payments/AuthorizeNet/AuthorizeNetGateway.php` and change its namespace to `App\Payments\AuthorizeNet`.
2. Copy `config/authorize-net.php` into your application's `config` directory.
3. Add `AUTHORIZENET_LOGIN_ID`, `AUTHORIZENET_TRANSACTION_KEY`, and `AUTHORIZENET_MODE=sandbox` to `.env`. Use sandbox credentials while developing.
4. Bind the gateway in a service provider so controllers can receive it through dependency injection.

```php
$this->app->singleton(AuthorizeNetGateway::class, fn () => new AuthorizeNetGateway(
    (string) config('authorize-net.login_id'),
    (string) config('authorize-net.transaction_key'),
    (string) config('authorize-net.mode', 'sandbox'),
));
```

5. Integrate Accept.js or Accept Hosted in the checkout page and send only its opaque `dataDescriptor` and `dataValue` to Laravel.
6. Validate the order on the server, calculate its amount from your own database, and call `chargeOpaqueData()` from a payment service or controller.
7. Store your order ID, Authorize.net transaction ID, amount, currency, and payment status. Use the stored transaction ID when calling `refund()`.
8. Prevent duplicate charges by recording a unique checkout or invoice reference and refusing to charge an order that is already paid.
9. Copy the scenarios from `tests/AuthorizeNetGatewayTest.php` into your test suite and run `php artisan test` without real credentials.
10. Complete a sandbox payment and refund before changing `AUTHORIZENET_MODE` to `live` with production credentials.
