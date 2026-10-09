<?php
namespace VueFileManager\Subscription\Support\Services;

use ErrorException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Promise\PromiseInterface;

trait PayPalHttpClient
{
    private string $id;
    private string $secret;

    private string $api;

    public function __construct()
    {
        $this->secret = config('subscription.credentials.paypal.secret');
        $this->id = config('subscription.credentials.paypal.id');

        $this->api = filter_var(config('subscription.credentials.paypal.is_live'), FILTER_VALIDATE_BOOLEAN)
            ? 'https://api-m.paypal.com/v1'
            : 'https://api-m.sandbox.paypal.com/v1';
    }

    public function get($url): PromiseInterface|Response
    {
        return Http::withToken($this->getAccessToken())
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->get("{$this->api}$url");
    }

    public function post($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->getAccessToken())
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->api}$url", $data);
    }

    public function patch($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->getAccessToken())
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->patch("{$this->api}$url", $data);
    }

    private function getAccessToken(): string
    {
        return Cache::remember('paypal_access_token', 3600, function () {
            $response = Http::withBasicAuth($this->id, $this->secret)
                ->withBody(
                    'grant_type=client_credentials',
                    'text/plain'
                )
                ->withHeaders([
                    'Content-Type'    => 'application/x-www-form-urlencoded',
                    'Accept'          => 'application/json',
                    'Accept-Language' => 'en_US',
                ])->post("$this->api/oauth2/token");

            if ($response->failed()) {
                throw new ErrorException($response->json()['error_description']);
            }

            return $response->json()['access_token'];
        });
    }
}
