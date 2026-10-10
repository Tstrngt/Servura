<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;

class CheckoutCart
{
    public const SESSION_KEY = 'checkout.cart';

    public static function get(?Request $request = null): array
    {
        $request ??= request();

        return $request->session()->get(self::SESSION_KEY, []);
    }

    public static function set(array $cart, ?Request $request = null): void
    {
        $request ??= request();
        $request->session()->put(self::SESSION_KEY, $cart);
    }

    public static function clear(?Request $request = null): void
    {
        $request ??= request();
        $request->session()->forget(self::SESSION_KEY);
    }

    public static function initialize(Service $service, ?array $prefill = null, ?Request $request = null): array
    {
        $request ??= request();
        $cart = self::get($request);

        if (empty($cart) || ($service->fulfillment_type === 'domain' && ! empty($prefill['domain']))) {
            $cart = [self::makeItem($service, $prefill)];
            self::set($cart, $request);
        }

        return $cart;
    }

    public static function addService(Service $service, ?ServicePrice $price = null, ?Request $request = null): array
    {
        $cart = self::get($request);

        // Avoid duplicates of the same service.
        foreach ($cart as $item) {
            if (($item['service_id'] ?? null) === $service->id) {
                return $cart;
            }
        }

        $newItem = self::makeItem($service, ['service_price_id' => $price?->id]);

        // If the cart already contains a domain, link it to the new hosting service.
        $domainItem = collect($cart)->first(fn ($item) => ($item['mode'] ?? null) === 'register' || ($item['mode'] ?? null) === 'transfer');
        if ($domainItem && $service->fulfillment_type === 'directadmin') {
            $newItem['domain_mode'] = 'existing';
            $newItem['domain'] = $domainItem['domain'] ?? null;
        }

        $cart[] = $newItem;
        self::set($cart, $request);

        return $cart;
    }

    public static function addDomain(
        string $domain,
        string $mode,
        ?string $tld = null,
        ?float $price = null,
        ?string $servicePriceId = null,
        ?string $authCode = null,
        ?Request $request = null,
    ): array {
        $cart = self::get($request);
        $tld ??= self::extractTld($domain);

        // Remove any previous domain item.
        $cart = array_values(array_filter($cart, fn ($item) => ($item['mode'] ?? null) !== 'register' && ($item['mode'] ?? null) !== 'transfer'));

        $domainService = Service::where('fulfillment_type', 'domain')->first();

        $cart[] = [
            'service_id' => $domainService?->id,
            'service_price_id' => $servicePriceId,
            'mode' => $mode,
            'domain' => $domain,
            'tld' => $tld,
            'price' => $price,
            'auth_code' => $authCode,
        ];

        self::set($cart, $request);

        return $cart;
    }

    public static function updateDomainForHostingItem(
        int $hostingItemIndex,
        string $mode,
        ?string $domain = null,
        ?string $authCode = null,
        ?Request $request = null,
    ): array {
        $cart = self::get($request);

        if (! isset($cart[$hostingItemIndex])) {
            return $cart;
        }

        $cart[$hostingItemIndex]['domain_mode'] = $mode;
        $cart[$hostingItemIndex]['domain'] = $domain;
        $cart[$hostingItemIndex]['auth_code'] = $authCode;

        // Remove any separate domain cart item that came from a previous selection.
        $cart = array_values(array_filter($cart, function ($item, $index) use ($hostingItemIndex) {
            if ($index === $hostingItemIndex) {
                return true;
            }

            return ! in_array($item['mode'] ?? null, ['register', 'transfer'], true);
        }, ARRAY_FILTER_USE_BOTH));

        self::set($cart, $request);

        return $cart;
    }

    public static function removeItem(int $index, ?Request $request = null): array
    {
        $cart = self::get($request);
        unset($cart[$index]);
        self::set(array_values($cart), $request);

        return $cart;
    }

    public static function items(?Request $request = null): array
    {
        return self::get($request);
    }

    private static function makeItem(Service $service, ?array $prefill = null): array
    {
        $item = [
            'service_id' => $service->id,
            'service_price_id' => $prefill['service_price_id'] ?? null,
            'mode' => $service->fulfillment_type === 'domain' && in_array($prefill['mode'] ?? null, ['register', 'transfer'], true)
                ? $prefill['mode']
                : ($service->fulfillment_type === 'domain' ? 'register' : 'none'),
        ];

        if ($service->fulfillment_type === 'domain') {
            $item['domain'] = $prefill['domain'] ?? null;
            $item['tld'] = $prefill['tld'] ?? null;
            $item['price'] = $prefill['price'] ?? null;
        }

        return $item;
    }

    private static function extractTld(string $domain): ?string
    {
        $parts = explode('.', $domain, 2);

        return $parts[1] ?? null;
    }
}
