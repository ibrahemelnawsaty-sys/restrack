@php($home = route('home'))
@php($otherLocale = app()->getLocale() === 'ar' ? 'en' : 'ar')
<header class="bar rise in">
    <div class="glass">
        <a class="brand" href="{{ $home }}" style="text-decoration:none">
            <svg class="logo" viewBox="0 0 40 40" aria-hidden="true"><defs><linearGradient id="lg" x1="0" y1="1" x2="1" y2="0"><stop offset="0" stop-color="#B9932F"/><stop offset="1" stop-color="#8B7CFF"/></linearGradient></defs>
                <rect x="6" y="22" width="6" height="12" rx="3" fill="url(#lg)"/><rect x="17" y="13" width="6" height="21" rx="3" fill="url(#lg)"/><rect x="28" y="5" width="6" height="29" rx="3" fill="url(#lg)"/></svg>
            <div><b>Restrack</b><span>{{ __('مؤسسة ريستراك للتدريب') }}</span></div>
        </a>
        <nav class="links" aria-label="{{ __('التنقل الرئيسي') }}">
            <a href="{{ $home }}#features">{{ __('المميزات') }}</a>
            <a href="{{ $home }}#program">{{ __('البرنامج') }}</a>
            <a href="{{ $home }}#pricing">{{ __('الأسعار') }}</a>
            <a href="{{ $home }}#faq">{{ __('الأسئلة') }}</a>
        </nav>
        <div class="bar-actions">
            <button class="tbtn" id="themeBtn" aria-label="{{ __('تبديل الوضع') }}"><svg class="ico" id="themeIco" aria-hidden="true"><use href="#i-moon"/></svg></button>
            <a class="tbtn" href="{{ route('lang.switch', $otherLocale) }}" aria-label="{{ __('تبديل اللغة') }}" title="{{ __('تبديل اللغة') }}"><svg class="ico" aria-hidden="true"><use href="#i-globe"/></svg><span>{{ $otherLocale === 'en' ? 'EN' : __('ع') }}</span></a>
            @auth
                @php($u = auth()->user())
                <a class="btn btn-gold-line btn-sm bar-cta" href="{{ route($u->homeRoute()) }}">{{ $u->isAdmin() ? __('لوحة الإدارة') : ($u->isInstructor() ? __('بوّابة المدرّب') : ($u->isAmbassador() ? __('لوحة السفير') : __('لوحتي'))) }}</a>
                <form method="POST" action="{{ route('logout') }}" class="bar-cta" style="display:inline">
                    @csrf
                    <button type="submit" class="login" style="background:none;border:none;cursor:pointer;font-family:inherit">{{ __('خروج') }}</button>
                </form>
            @else
                <a class="login" href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a>
                <a class="btn btn-gold-line btn-sm bar-cta" href="{{ route('register') }}">{{ __('ابدأ الآن') }}</a>
            @endauth
            <button class="tbtn menuBtn" id="menuBtn" aria-expanded="false" aria-controls="mobileMenu" aria-label="{{ __('القائمة') }}"><svg class="ico" aria-hidden="true"><use href="#i-menu"/></svg></button>
        </div>
    </div>
    <div class="mobile-menu glass" id="mobileMenu" hidden>
        <a href="{{ $home }}#features">{{ __('المميزات') }}</a>
        <a href="{{ $home }}#program">{{ __('البرنامج') }}</a>
        <a href="{{ $home }}#pricing">{{ __('الأسعار') }}</a>
        <a href="{{ $home }}#faq">{{ __('الأسئلة') }}</a>
        @auth
            @php($u = auth()->user())
            <a href="{{ route($u->homeRoute()) }}">{{ $u->isAdmin() ? __('لوحة الإدارة') : ($u->isInstructor() ? __('بوّابة المدرّب') : ($u->isAmbassador() ? __('لوحة السفير') : __('لوحتي'))) }}</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-ghost full" style="font-family:inherit;cursor:pointer">{{ __('تسجيل الخروج') }}</button></form>
        @else
            <a href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a>
            <a class="btn btn-gold" href="{{ route('register') }}"><span class="sheen"></span>{{ __('ابدأ الآن') }}</a>
        @endauth
    </div>
</header>
