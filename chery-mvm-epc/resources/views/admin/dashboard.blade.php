
@extends('layouts.app')

@section('title', 'پنل مدیریت')

@section('content')

<div class="page-heading">
    <h1>پنل مدیریت سامانه</h1>
    <p class="muted">مدیریت فروشندگان، اشتراک‌ها و درخواست‌های سامانه EPC</p>
</div>

<div class="hero">
    <h1>خوش آمدید به پنل مدیریت</h1>
    <p>از این بخش می‌توانید درخواست‌های فروشندگی و پرداخت اشتراک‌ها را مدیریت کنید.</p>
</div>

<div class="stats">

    <div class="stat">
        <div class="stat-label">درخواست‌های فروشندگی در انتظار بررسی</div>
        <div class="stat-value">
            {{ number_format($pendingSellers) }}
        </div>
        <a href="{{ route('admin.sellers.index') }}" class="small">
            مشاهده درخواست‌ها ←
        </a>
    </div>

    <div class="stat">
        <div class="stat-label">فروشندگان تأییدشده</div>
        <div class="stat-value">
            {{ number_format($approvedSellers) }}
        </div>
        <a href="{{ route('admin.sellers.index') }}" class="small">
            مدیریت فروشندگان ←
        </a>
    </div>

    <div class="stat">
        <div class="stat-label">پرداخت‌های اشتراک در انتظار بررسی</div>
        <div class="stat-value">
            {{ number_format($pendingTransactions) }}
        </div>
        <a href="{{ route('admin.subscription-transactions.index') }}" class="small">
            بررسی پرداخت‌ها ←
        </a>
    </div>

    <div class="stat">
        <div class="stat-label">پرداخت‌های تأییدشده</div>
        <div class="stat-value">
            {{ number_format($approvedTransactions) }}
        </div>
        <a href="{{ route('admin.subscription-transactions.index') }}" class="small">
            مشاهده تراکنش‌ها ←
        </a>
    </div>

</div>

<div class="card">
    <h2>مدیریت بخش‌های سامانه</h2>

    <div class="category-grid">

        <a href="{{ route('admin.sellers.index') }}" class="category-item">
            <strong>🏪 مدیریت فروشندگان</strong>
            <small>مشاهده و بررسی درخواست‌های فروشندگی</small>
        </a>

        <a href="{{ route('admin.subscription-transactions.index') }}" class="category-item">
            <strong>💳 مدیریت اشتراک‌ها</strong>
            <small>تأیید پرداخت و فعال‌سازی اشتراک کاربران</small>
        </a>

        <a href="{{ route('catalog.index') }}" class="category-item">
            <strong>🚗 کاتالوگ قطعات</strong>
            <small>رفتن به صفحه اصلی کاتالوگ EPC</small>
        </a>

        <a href="{{ route('catalog.category') }}" class="category-item">
            <strong>🔧 دسته‌بندی قطعات</strong>
            <small>مشاهده قطعات و دسته‌بندی خودروها</small>
        </a>

    </div>
</div>

@endsection
