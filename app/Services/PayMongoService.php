<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PayMongoService
{
    protected $baseUrl;
    protected $secretKey;
    protected $client;

    public function __construct()
    {
        $this->baseUrl = config('services.paymongo.base_url', env('PAYMONGO_BASE_URL'));
        $this->secretKey = env('PAYMONGO_SECRET_KEY');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$this->secretKey, ''],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    protected function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            }

            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function createGCashSource($amount, $successUrl, $failedUrl, $billing = [])
    {
        return $this->handleRequest(function () use ($amount, $successUrl, $failedUrl, $billing) {
            $response = $this->client->post('/sources', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'type' => 'gcash',
                            'amount' => $amount,
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => $successUrl,
                                'failed' => $failedUrl,
                            ],
                            'billing' => [
                                'name' => $billing['name'] ?? null,
                                'email' => $billing['email'] ?? null,
                                'phone' => $billing['phone'] ?? null,
                            ]
                        ],
                    ],
                ],
            ]);

            $source = json_decode($response->getBody(), true);

            return [
                'source_id' => $source['data']['id'] ?? null,
                'checkout_url' => $source['data']['attributes']['redirect']['checkout_url'] ?? null,
            ];
        });
    }

    public function createPaymentFromSource($sourceId, $amount, $description)
    {
        return $this->handleRequest(function () use ($sourceId, $amount, $description) {
            $response = $this->client->post('/payments', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'currency' => 'PHP',
                            'source' => [
                                'id' => $sourceId,
                                'type' => 'source',
                            ],
                            'description' => $description,
                        ],
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        });
    }

    public function getSource($sourceId)
    {
        return $this->handleRequest(function () use ($sourceId) {
            $response = $this->client->get("/sources/{$sourceId}");
            return json_decode($response->getBody(), true);
        });
    }

    public function getPayment($paymentId)
    {
        return $this->handleRequest(function () use ($paymentId) {
            $response = $this->client->get("/payments/{$paymentId}");
            return json_decode($response->getBody(), true);
        });
    }
}
