<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Brand icons: the browser fetches /favicon.ico on its own, modern engines prefer the
         SVG, and PNG is the only format iOS accepts for a home-screen tile. --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <script nonce="{{ Vite::cspNonce() }}">(function(){try{var t=localStorage.getItem('theme');if(t==='dark'||t==='light'){document.documentElement.setAttribute('data-theme',t);}}catch(e){}})();</script>
    @include('partials.seo')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
<noscript><style>.rise,.stagger>*{opacity:1!important;transform:none!important;filter:none!important}</style></noscript>

@include('partials.icons')

<div class="aurora"><span class="orb v"></span><span class="orb t"></span><span class="orb g"></span></div>
<div class="grain"></div>

<div class="wrap">
    @include('partials.navbar')

    @if (session('status'))
        <div class="flash rise in" role="status">{{ session('status') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</div>

@stack('scripts')
<script nonce="{{ Vite::cspNonce() }}">/* fail-safe: if the JS bundle didn't load, reveal content instead of a blank page */
setTimeout(function(){if(!window.__rt){document.querySelectorAll('.rise,.stagger').forEach(function(e){e.classList.add('in');});}},1200);</script>
</body>
</html>
