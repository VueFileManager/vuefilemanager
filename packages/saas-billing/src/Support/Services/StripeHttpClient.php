<?php
namespace VueFileManager\Subscription\Support\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;

trait StripeHttpClient
{
    private string $secret;
    private string $api;

    public function __construct()
    {
        $this->secret = config('subscription.credentials.stripe.secret');

        $this->api = 'https://api.stripe.com/v1';
    }

    public function get($url): PromiseInterface|Response
    {
        return Http::withToken($this->secret)
            ->asForm()
            ->get($this->api . $url);
    }

    public function post($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->secret)
            ->asForm()
            ->post($this->api . $url, $data);
    }

    public function patch($url, $data): PromiseInterface|Response
    {
        return Http::withToken($this->secret)
            ->asForm()
            ->patch($this->api . $url, $data);
    }

    public function delete($url): PromiseInterface|Response
    {
        return Http::withToken($this->secret)
            ->asForm()
            ->delete($this->api . $url);
    }
}
