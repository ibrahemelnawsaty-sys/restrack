@php($home = route('home'))
<header class="bar rise in">
    <div class="glass">
        <a class="brand" href="{{ $home }}" style="text-decoration:none">
            <svg class="logo" viewBox="0 0 40 40" aria-hidden="true"><defs><linearGradient id="lg" x1="0" y1="1" x2="1" y2="0"><stop offset="0" stop-color="#B9932F"/><stop offset="1" stop-color="#8B7CFF"/></linearGradient></defs>
                <rect x="6" y="22" width="6" height="12" rx="3" fill="url(#lg)"/><rect x="17" y="13" width="6" height="21" rx="3" fill="url(#lg)"/><rect x="28" y="5" width="6" height="29" rx="3" fill="url(#lg)"/></svg>
            <div><b>Restrack</b><span>مؤسسة ريستراك للتدريب</span></div>
        </a>
        <nav class="links" aria-label="التنقل الرئيسي">
            <a href="{{ $home }}#features">المميزات</a>
            <a href="{{ $home }}#program">البرنامج</a>
            <a href="{{ $home }}#pricing">الأسعار</a>
            <a href="{{ $home }}#faq">الأسئلة</a>
        </nav>
        <div class="bar-actions">
            <button class="tbtn" id="themeBtn" aria-label="تبديل الوضع الليلي والنهاري"><svg class="ico" id="themeIco" aria-hidden="true"><use href="#i-moon"/></svg><span id="themeTxt">ليلي</span></button>
            @auth
                @php($u = auth()->user())
                <a class="btn btn-gold-line btn-sm bar-cta" href="{{ route($u->homeRoute()) }}">{{ $u->isAdmin() ? 'لوحة الإدارة' : ($u->isInstructor() ? 'بوّابة المدرّب' : ($u->isAmbassador() ? 'لوحة السفير' : 'لوحتي')) }}</a>
                <form method="POST" action="{{ route('logout') }}" class="bar-cta" style="display:inline">
                    @csrf
                    <button type="submit" class="login" style="background:none;border:none;cursor:pointer;font-family:inherit">خروج</button>
                </form>
            @else
                <a class="login" href="{{ route('login') }}">تسجيل الدخول</a>
                <a class="btn btn-gold-line btn-sm bar-cta" href="{{ route('register') }}">ابدأ الآن</a>
            @endauth
            <button class="tbtn menuBtn" id="menuBtn" aria-expanded="false" aria-controls="mobileMenu" aria-label="القائمة"><svg class="ico" aria-hidden="true"><use href="#i-menu"/></svg></button>
        </div>
    </div>
    <div class="mobile-menu glass" id="mobileMenu" hidden>
        <a href="{{ $home }}#features">المميزات</a>
        <a href="{{ $home }}#program">البرنامج</a>
        <a href="{{ $home }}#pricing">الأسعار</a>
        <a href="{{ $home }}#faq">الأسئلة</a>
        @auth
            @php($u = auth()->user())
            <a href="{{ route($u->homeRoute()) }}">{{ $u->isAdmin() ? 'لوحة الإدارة' : ($u->isInstructor() ? 'بوّابة المدرّب' : ($u->isAmbassador() ? 'لوحة السفير' : 'لوحتي')) }}</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-ghost full" style="font-family:inherit;cursor:pointer">تسجيل الخروج</button></form>
        @else
            <a href="{{ route('login') }}">تسجيل الدخول</a>
            <a class="btn btn-gold" href="{{ route('register') }}"><span class="sheen"></span>ابدأ الآن</a>
        @endauth
    </div>
</header>
