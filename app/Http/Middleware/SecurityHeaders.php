<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Baseline browser hardening for every document the app serves.
     *
     * The CSP is nonce-based: each inline <script> in the views carries
     * {{ Vite::cspNonce() }}, so script-src needs no 'unsafe-inline'.
     * Inline style="" attributes are used all over the views (a nonce cannot
     * cover attributes), so style-src keeps 'unsafe-inline' — the honest cost.
     *
     * HSTS is deliberately NOT sent from PHP: it belongs on the web server
     * (Hostinger/LiteSpeed) once HTTPS is proven, and a wrong max-age here
     * would lock the domain out of HTTP for months.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Vite::useCspNonce();

        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');

        // A policy only means anything on a document — the private video stream and
        // the certificate PDF are byte responses and are left untouched.
        if ($this->isDocument($response) && ! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->policy($nonce));
        }

        return $response;
    }

    /** The shipped policy. Every source is same-origin; there is no third-party asset. */
    protected function policy(string $nonce): string
    {
        $script = ["'self'", "'nonce-".$nonce."'"];
        $style = ["'self'", "'unsafe-inline'"];
        $connect = ["'self'"];

        // `npm run dev` serves assets (and the HMR socket) from the Vite dev server.
        foreach ($this->viteDevOrigins() as $origin) {
            $script[] = $origin;
            $style[] = $origin;
            $connect[] = $origin;
        }

        // POST /checkout/{plan} answers with a redirect to Paymob's hosted checkout.
        // Several browsers check form-action against that redirect, so the gateway
        // origin has to be listed or the payment flow dies at the last step.
        $form = array_filter(["'self'", $this->paymobOrigin()]);

        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            'form-action '.implode(' ', $form),
            'script-src '.implode(' ', $script),
            'style-src '.implode(' ', $style),
            "img-src 'self' data:",
            "font-src 'self'",
            "media-src 'self'",
            'connect-src '.implode(' ', $connect),
        ]);
    }

    /** Scheme + host of the configured payment gateway, e.g. https://ksa.paymob.com. */
    protected function paymobOrigin(): ?string
    {
        $parts = parse_url((string) config('services.paymob.base_url'));

        return isset($parts['scheme'], $parts['host'])
            ? $parts['scheme'].'://'.$parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '')
            : null;
    }

    /** @return list<string> the dev-server origin plus its websocket scheme, or [] in production. */
    protected function viteDevOrigins(): array
    {
        if (! Vite::isRunningHot() || ! is_file($hotFile = Vite::hotFile())) {
            return [];
        }

        $url = rtrim(trim((string) file_get_contents($hotFile)), '/');

        if ($url === '') {
            return [];
        }

        return [$url, str_replace(['https://', 'http://'], ['wss://', 'ws://'], $url)];
    }

    /** Laravel leaves Content-Type unset on plain view responses, so "unset" counts as HTML. */
    protected function isDocument(Response $response): bool
    {
        $type = strtolower((string) $response->headers->get('Content-Type', ''));

        return $type === '' || str_contains($type, 'text/html');
    }
}
