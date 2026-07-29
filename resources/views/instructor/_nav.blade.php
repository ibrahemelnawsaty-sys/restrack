@php
  $navItems = [
    'instructor.dashboard' => 'لوحتي',
    'instructor.lectures.index' => 'محاضراتي',
  ];
@endphp
<nav class="glass tile rise in" style="display:flex;gap:6px;flex-wrap:wrap;padding:10px;margin-bottom:20px;align-items:center">
  @foreach ($navItems as $route => $label)
    <a href="{{ route($route) }}"
       class="{{ request()->routeIs(str_replace('.index','',$route).'*') ? 'btn btn-gold-line btn-sm' : '' }}"
       style="{{ request()->routeIs(str_replace('.index','',$route).'*') ? '' : 'padding:.5rem .8rem;text-decoration:none;color:var(--ink-2);font-weight:700;font-size:.85rem;border-radius:10px' }}">{{ $label }}</a>
  @endforeach
  <a href="{{ route('home') }}" style="margin-inline-start:auto;padding:.5rem .8rem;text-decoration:none;color:var(--ink-3);font-weight:700;font-size:.82rem">{{ __('← الموقع') }}</a>
</nav>
