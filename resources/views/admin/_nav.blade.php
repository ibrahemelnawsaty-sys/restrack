@php
  $navItems = [
    'admin.dashboard' => 'اللوحة',
    'admin.content.index' => 'المحتوى',
    'admin.levels.index' => 'المستويات',
    'admin.lectures.index' => 'المحاضرات',
    'admin.plans.index' => 'الخطط',
    'admin.faqs.index' => 'الأسئلة',
    'admin.users.index' => 'المستخدمون',
    'admin.subscriptions.index' => 'الاشتراكات',
  ];
@endphp
<nav class="glass tile rise in" style="display:flex;gap:6px;flex-wrap:wrap;padding:10px;margin-bottom:20px">
  @foreach ($navItems as $route => $label)
    <a href="{{ route($route) }}"
       class="{{ request()->routeIs(str_replace('.index','',$route).'*') ? 'btn btn-gold-line btn-sm' : '' }}"
       style="{{ request()->routeIs(str_replace('.index','',$route).'*') ? '' : 'padding:.5rem .8rem;text-decoration:none;color:var(--ink-2);font-weight:700;font-size:.85rem;border-radius:10px' }}">{{ $label }}</a>
  @endforeach
</nav>
