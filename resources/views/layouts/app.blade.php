<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</body>
</html>
