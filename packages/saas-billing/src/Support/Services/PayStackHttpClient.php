<?php
namespace VueFileManager\Subscription\Support\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;

trait PayStackHttpClient
{
    private string $bearer;
    private string $api;

    public function __construct()
    {
        $this->bearer = config('subscription.credentials.paystack.secret');
        $this->api = 'https://api.paystack.co';
    }

    public function post($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->bearer)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->api}$url", $data);
    }

    public function put($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->bearer)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->put("{$this->api}$url", $data);
    }

    public function get($url): PromiseInterface|Response
    {
        return Http::withToken($this->bearer)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->get("{$this->api}$url");
    }

    public function delete($url): PromiseInterface|Response
    {
        return Http::withToken($this->bearer)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->delete("{$this->api}$url");
    }
}
