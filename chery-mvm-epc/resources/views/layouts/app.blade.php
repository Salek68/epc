<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b1730">
    <title>@yield('title', 'PartCar | پارت کار')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partcar-system.css') }}?v=2.0.0">
    @stack('styles')
</head>
<body class="pc-app">
<input class="pc-menu-toggle" type="checkbox" id="pc-menu-toggle">
<label class="pc-mobile-scrim" for="pc-menu-toggle"></label>

<aside class="pc-sidebar">
    <div class="pc-sidebar-brand">
        <a href="{{ route('catalog.index') }}" aria-label="PartCar">
            <img src="{{ asset('branding/partcar-horizontal.png') }}" alt="PartCar | پارت کار">
        </a>
    </div>

    @auth
    <div class="pc-user-mini">
        <div class="pc-user-avatar">{{ mb_substr(auth()->user()->name ?? 'P', 0, 1) }}</div>
        <div class="pc-user-meta">
            <strong>{{ auth()->user()->name ?? 'کاربر پارت کار' }}</strong>
            <span>
                @if(auth()->user()->is_admin == 1) مدیر سامانه
                @elseif(auth()->user()->sellerStore?->status === 'approved') فروشنده تأییدشده
                @else کاربر سامانه @endif
            </span>
        </div>
    </div>

    <nav class="pc-side-nav" aria-label="منوی اصلی">
        <div class="pc-nav-caption">فضای کاری</div>
        <a class="pc-nav-link {{ request()->routeIs('catalog.index') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
            <span class="pc-nav-icon">⌕</span><span>جست‌وجوی قطعات</span>
        </a>
        <a class="pc-nav-link {{ request()->routeIs('catalog.category') ? 'active' : '' }}" href="{{ route('catalog.category') }}">
            <span class="pc-nav-icon">▦</span><span>دسته‌بندی قطعات</span>
        </a>
        <a class="pc-nav-link {{ request()->routeIs('brake-bank.*') ? 'active' : '' }}" href="{{ route('brake-bank.index') }}">
            <span class="pc-nav-icon">◈</span><span>بانک لنت</span>
        </a>

        <div class="pc-nav-caption">حساب و اشتراک</div>
        <a class="pc-nav-link {{ request()->routeIs('subscriptions.index') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}">
            <span class="pc-nav-icon">◇</span><span>پلن‌های اشتراک</span>
        </a>
        <a class="pc-nav-link {{ request()->routeIs('subscriptions.my') ? 'active' : '' }}" href="{{ route('subscriptions.my') }}">
            <span class="pc-nav-icon">▤</span><span>اشتراک من</span>
        </a>

        @if(!auth()->user()->is_admin && auth()->user()->sellerStore?->status === 'approved')
        <div class="pc-nav-caption">پنل فروشنده</div>
        <a class="pc-nav-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}" href="{{ route('seller.products.index') }}">
            <span class="pc-nav-icon">▣</span><span>مدیریت محصولات</span>
        </a>
        @endif

        @if(auth()->user()->is_admin == 1)
        <div class="pc-nav-caption">مدیریت سامانه</div>
        <a class="pc-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <span class="pc-nav-icon">⌂</span><span>داشبورد مدیریت</span>
        </a>
        <a class="pc-nav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}" href="{{ route('admin.sellers.index') }}">
            <span class="pc-nav-icon">♙</span><span>مدیریت فروشندگان</span>
        </a>
        <a class="pc-nav-link {{ request()->routeIs('admin.subscription-transactions.*') ? 'active' : '' }}" href="{{ route('admin.subscription-transactions.index') }}">
            <span class="pc-nav-icon">▧</span><span>تراکنش اشتراک‌ها</span>
        </a>
        @endif
    </nav>

    <div class="pc-sidebar-bottom">
        <div class="pc-help-box">
            <strong>نیاز به راهنمایی دارید؟</strong>
            <span>راهنمای استفاده از پارت‌کار</span>
        </div>
               <div class="pc-sidebar-foot">PARTCAR <span>•</span> نسخه سامانه</div>
    </div>
    @endauth
</aside>

<div class="pc-main-shell">
    <header class="pc-topbar">
        <div class="pc-topbar-right">
            <label class="pc-menu-button" for="pc-menu-toggle" aria-label="باز کردن منو">☰</label>
            <div class="pc-breadcrumb">
                <span>پارت‌کار</span><b>/</b><strong>@yield('page-title', 'فضای کاری')</strong>
            </div>
        </div>
        <div class="pc-topbar-left">
            <form class="pc-global-search" action="{{ route('catalog.index') }}" method="GET">
                <span>⌕</span>
                <input type="search" name="search" placeholder="جست‌وجوی نام قطعه یا کد فنی..." aria-label="جست‌وجوی سراسری">
                <kbd>جست‌وجو</kbd>
            </form>
            @auth
                <div class="pc-top-user"><span class="pc-online-dot"></span>{{ auth()->user()->name ?? 'حساب کاربری' }}</div>
            @else
                <a class="pc-top-login" href="{{ route('login') }}">ورود به حساب</a>
            @endauth
        </div>
    </header>

    <main class="pc-main-content">
        @if(session('success'))
            <div class="pc-flash pc-flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="pc-flash pc-flash-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="pc-footer">
        <span>© {{ date('Y') }} PartCar</span>
        <span>قطعه درست، انتخاب مطمئن</span>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
@stack('scripts')
</body>
</html>
