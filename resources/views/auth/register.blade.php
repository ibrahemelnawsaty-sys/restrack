@extends('layouts.app')

@section('title', __('إنشاء حساب — Restrack'))

@section('content')
  <div class="auth-wrap">
    <div class="glass auth-card rise in" style="max-width:520px;padding:clamp(30px,4vw,44px);border-radius:20px">
      <div class="pico" style="margin-inline:auto"><svg class="ico" aria-hidden="true"><use href="#i-cap"/></svg></div>
      <h1 style="margin-top:18px;font-size:clamp(1.5rem,3vw,1.85rem);letter-spacing:-.015em">{{ __('ابدأ رحلتك') }}</h1>
      <div class="sec-rule plain" style="margin-top:18px"><span class="dot"></span></div>
      <p class="sub">{{ __('أنشئ حسابك المجاني وابدأ من المستوى الأول.') }}</p>

      @if (!empty($referrer))
        <div class="flash rise in" role="status" style="margin-top:0;margin-bottom:18px;text-align:center">{{ __('أنت مدعوٌّ من') }} <b>{{ $referrer->name }}</b></div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        @if (!empty($refCode))<input type="hidden" name="ref" value="{{ $refCode }}">@endif
        <div class="field" style="margin-bottom:16px">
          <label for="name">{{ __('الاسم الكامل') }}</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus style="padding:.9rem 1rem">
          @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom:16px">
          <label for="email">{{ __('البريد الإلكتروني') }}</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required dir="ltr" style="text-align:start;padding:.9rem 1rem">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom:16px">
          <label for="password">{{ __('كلمة المرور') }}</label>
          <input id="password" name="password" type="password" required style="padding:.9rem 1rem">
          @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom:16px">
          <label for="password_confirmation">{{ __('تأكيد كلمة المرور') }}</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required style="padding:.9rem 1rem">
        </div>

        <div class="field" style="margin-bottom:16px;padding-top:16px;border-top:1px solid var(--g-border)">
          <label>{{ __('هل تمّت دعوتك من قِبَل دكتور؟') }}</label>
          <div style="display:flex;gap:12px;margin-top:4px">
            <label class="check opt" style="flex:1;margin-bottom:0"><input type="radio" name="invited" value="no" style="accent-color:var(--gold-2)" @checked(empty($referrer) && old('invited', 'no') === 'no')> {{ __('لا') }}</label>
            <label class="check opt" style="flex:1;margin-bottom:0"><input type="radio" name="invited" value="yes" style="accent-color:var(--gold-2)" @checked(!empty($referrer) || old('invited') === 'yes')> {{ __('نعم') }}</label>
          </div>
        </div>

        <div class="field" style="margin-bottom:16px" id="doctorPick" @unless(!empty($referrer) || old('invited') === 'yes') hidden @endunless>
          <label for="doctorSearch">{{ __('اختر الدكتور الذي دعاك') }}</label>
          <input id="doctorSearch" type="text" placeholder="{{ __('ابحث باسم الدكتور…') }}" autocomplete="off" style="padding:.9rem 1rem">
          <select name="referrer_id" id="doctorSelect" size="6" style="padding:.5rem">
            <option value="">{{ __('— اختر الدكتور —') }}</option>
            @foreach ($referrers as $r)
              <option value="{{ $r->id }}" @selected((string) old('referrer_id', optional($referrer)->id) === (string) $r->id)>{{ $r->name }}</option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-gold full" style="margin-top:22px"><span class="sheen"></span>{{ __('إنشاء الحساب') }}</button>
      </form>

      <p class="form-foot" style="margin-top:22px;padding-top:18px;border-top:1px solid var(--g-border)">{{ __('لديك حساب بالفعل؟') }} <a href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a></p>
    </div>
  </div>

  @push('scripts')
    <script nonce="{{ Vite::cspNonce() }}">
      (function(){
        var yes=document.querySelector('input[name=invited][value=yes]');
        var no=document.querySelector('input[name=invited][value=no]');
        var pick=document.getElementById('doctorPick');
        var search=document.getElementById('doctorSearch');
        var sel=document.getElementById('doctorSelect');
        if(!yes||!no||!pick)return;
        function toggle(){ pick.hidden=!yes.checked; if(!yes.checked && sel){ sel.value=''; } }
        yes.addEventListener('change',toggle); no.addEventListener('change',toggle);
        if(search&&sel){
          var opts=Array.prototype.slice.call(sel.options).map(function(o){return {v:o.value,t:o.text};});
          search.addEventListener('input',function(){
            var q=search.value.trim(); sel.innerHTML='';
            opts.filter(function(o){return o.v==='' || o.t.indexOf(q)>-1;}).forEach(function(o){
              var el=document.createElement('option'); el.value=o.v; el.text=o.t; sel.appendChild(el);
            });
          });
        }
      })();
    </script>
  @endpush
@endsection
