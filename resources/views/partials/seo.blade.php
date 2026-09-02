@php
    $seoTitle = trim($__env->yieldContent('title', __('Restrack — منصة البحث الطبي · Research Track Platform')));
    $seoDesc = trim($__env->yieldContent('meta_description', __('منصة عربية فاخرة لإتقان البحث الطبي من المبتدئ إلى الباحث الناشر — اشتراك واحد يفتح المسار كاملاً.')));
    $canonical = url()->current();
    // An admin-set card wins; otherwise the static 1200x630 brand card in public/.
    $ogCustom = \App\Models\SeoMeta::ogImage(request()->route()?->getName());
    $ogImage = $ogCustom ?: asset('og-image.png');
@endphp
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDesc }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="theme-color" content="#0b1428">

{{-- hreflang (bilingual) --}}
<link rel="alternate" hreflang="ar" href="{{ $canonical }}">
<link rel="alternate" hreflang="x-default" href="{{ $canonical }}">

{{-- Open Graph / Twitter --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Restrack">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_SA' : 'en_US' }}">
{{-- vector source for the bundled card: public/og-image.svg (PNG is what the platforms actually render) --}}
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ __('Restrack — Research Track Platform · منصة البحث الطبي') }}">
@unless ($ogCustom)
    {{-- only the bundled card has known dimensions --}}
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endunless
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $ogImage }}">

@stack('seo')

{{-- Organization + WebSite structured data --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'EducationalOrganization',
            'name' => 'Restrack — مؤسسة ريستراك للتدريب',
            'url' => url('/'),
            'description' => __('منصة عربية لتعليم البحث الطبي من المبتدئ إلى الباحث الناشر.'),
        ],
        [
            '@type' => 'WebSite',
            'name' => 'Restrack',
            'url' => url('/'),
            'inLanguage' => 'ar',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/').'/?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
