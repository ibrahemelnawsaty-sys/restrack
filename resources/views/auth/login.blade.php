@extends('layouts.app')

@section('title', __('تسجيل الدخول — Restrack'))

@section('content')
  <div class="auth-wrap">
    <div class="glass auth-card rise in" style="padding:clamp(30px,4vw,44px);border-radius:20px">
      <div class="pico" style="margin-inline:auto"><svg class="ico" aria-hidden="true"><use href="#i-key"/></svg></div>
      <h1 style="margin-top:18px;font-size:clamp(1.5rem,3vw,1.85rem);letter-spacing:-.015em">{{ __('تسجيل الدخول') }}</h1>
      <div class="sec-rule plain" style="margin-top:18px"><span class="dot"></span></div>
      <p class="sub">{{ __('أهلاً بعودتك — تابع رحلتك في البحث الطبي.') }}</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field" style="margin-bottom:16px">
          <label for="email">{{ __('البريد الإلكتروني') }}</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus dir="ltr" style="text-align:start;padding:.9rem 1rem">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom:16px">
          <label for="password">{{ __('كلمة المرور') }}</label>
          <input id="password" name="password" type="password" required style="padding:.9rem 1rem">
          @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <label class="check"><input type="checkbox" name="remember" style="accent-color:var(--gold-2)"> {{ __('تذكّرني على هذا الجهاز') }}</label>
        <button type="submit" class="btn btn-gold full" style="margin-top:22px"><span class="sheen"></span>{{ __('دخول') }}</button>
      </form>

      <p class="form-foot" style="margin-top:22px;padding-top:18px;border-top:1px solid var(--g-border)">{{ __('ليس لديك حساب؟') }} <a href="{{ route('register') }}">{{ __('أنشئ حساباً مجاناً') }}</a></p>
    </div>
  </div>
@endsection
