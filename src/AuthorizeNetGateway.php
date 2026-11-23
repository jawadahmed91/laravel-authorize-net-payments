<?php

namespace JawadAhmed\LaravelAuthorizeNet;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class AuthorizeNetGateway
{
    public function __construct(
        private readonly string $loginId,
        private readonly string $transactionKey,
        private readonly string $mode = 'sandbox',
    ) {}

    /** @return array<string, mixed> */
    public function chargeOpaqueData(string $amount, string $dataDescriptor, string $dataValue, ?string $invoiceNumber = null): array
    {
        return $this->post('createTransactionRequest', [
            'transactionRequest' => [
                'transactionType' => 'authCaptureTransaction',
                'amount' => $amount,
                'payment' => ['opaqueData' => ['dataDescriptor' => $dataDescriptor, 'dataValue' => $dataValue]],
                'order' => ['invoiceNumber' => $invoiceNumber],
            ],
        ]);
    }

    /** @return array<string, mixed> */
    public function refund(string $transactionId, string $amount, string $lastFourDigits): array
    {
        return $this->post('createTransactionRequest', [
            'transactionRequest' => [
                'transactionType' => 'refundTransaction',
                'amount' => $amount,
                'refTransId' => $transactionId,
                'payment' => ['creditCard' => ['cardNumber' => $lastFourDigits, 'expirationDate' => 'XXXX']],
            ],
        ]);
    }

    /** @param array<string, mixed> $body @return array<string, mixed> */
    private function post(string $requestName, array $body): array
    {
        return $this->api()->post('', [
            $requestName => [
                'merchantAuthentication' => ['name' => $this->loginId, 'transactionKey' => $this->transactionKey],
                ...$body,
            ],
        ])->throw()->json();
    }

    private function api(): PendingRequest
    {
        $url = $this->mode === 'live'
            ? 'https://api.authorize.net/xml/v1/request.api'
            : 'https://apitest.authorize.net/xml/v1/request.api';
        return Http::baseUrl($url)->acceptJson()->asJson();
    }
}
