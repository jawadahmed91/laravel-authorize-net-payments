<?php

namespace Tests;

use Illuminate\Support\Facades\Http;
use JawadAhmed\LaravelAuthorizeNet\AuthorizeNetGateway;
use Orchestra\Testbench\TestCase;

final class AuthorizeNetGatewayTest extends TestCase
{
    public function test_it_charges_accept_js_opaque_data(): void
    {
        Http::fake(['https://apitest.authorize.net/*' => Http::response(['transactionResponse' => ['transId' => '123']])]);

        $result = (new AuthorizeNetGateway('login', 'key'))->chargeOpaqueData('49.99', 'COMMON.ACCEPT.INAPP.PAYMENT', 'opaque-token');

        self::assertSame('123', $result['transactionResponse']['transId']);
        Http::assertSent(fn ($request) => $request['createTransactionRequest']['transactionRequest']['amount'] === '49.99');
    }
}
