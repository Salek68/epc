
@extends('layouts.app')

@section('title', 'خرید اشتراک')

@section('content')

<div class="page-heading">
    <h1>اشتراک‌های کاتالوگ EPC</h1>
    <p>برای استفاده از امکانات سامانه، پلن موردنظر خود را انتخاب کنید.</p>
</div>

@if(session('success'))
    <div class="card" style="border-right:4px solid #16a34a; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif
@if(session('subscription_error'))
    <div class="card" style="border-right:4px solid #dc2626; margin-bottom:20px;">
        {{ session('subscription_error') }}
    </div>
@endif
@if($errors->any())
    <div class="card" style="border-right:4px solid #dc2626; margin-bottom:20px;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="card" style="margin-bottom:25px;">
    <h2>اشتراک کاربران</h2>
    <p>دسترسی به امکانات کاتالوگ و جست‌وجوی قطعات خودروهای چینی.</p>

    <div class="category-grid">

        @foreach($plans['user'] as $key => $plan)
            <div class="category-item">
                <h3>{{ $plan['title'] }}</h3>

                <h2>
                    {{ number_format($plan['price']) }}
                    <small>تومان</small>
                </h2>

                <p>مدت اعتبار: {{ $plan['duration_days'] }} روز</p>

                <form method="POST" action="{{ route('subscriptions.store') }}">
                    @csrf
                    <input type="hidden" name="role" value="user">
                    <input type="hidden" name="plan" value="{{ $key }}">

                    <button type="submit" class="btn">
                        انتخاب و خرید
                    </button>
                </form>
            </div>
        @endforeach

    </div>
</div>

@if($isSeller)

    <div class="card">
        <h2>اشتراک فروشندگان</h2>
        <p>برای ثبت و مدیریت محصولات فروشگاهی در سامانه.</p>

        <div class="category-grid">

            @foreach($plans['seller'] as $key => $plan)
                <div class="category-item">
                    <h3>{{ $plan['title'] }}</h3>

                    <h2>
                        {{ number_format($plan['price']) }}
                        <small>تومان</small>
                    </h2>

                    <p>مدت اعتبار: {{ $plan['duration_days'] }} روز</p>

                    <form method="POST" action="{{ route('subscriptions.store') }}">
                        @csrf
                        <input type="hidden" name="role" value="seller">
                        <input type="hidden" name="plan" value="{{ $key }}">

                        <button type="submit" class="btn">
                            انتخاب و خرید
                        </button>
                    </form>
                </div>
            @endforeach

        </div>
    </div>

@else

    <div class="card" style="margin-top:20px;">
        <h3>اشتراک فروشندگان</h3>
        <p>
            برای خرید اشتراک فروشندگی ابتدا باید درخواست فروشندگی
            خود را ثبت کنید و منتظر تأیید مدیریت بمانید.
        </p>

        <a href="{{ route('seller.register') }}" class="btn">
            ثبت درخواست فروشندگی
        </a>
    </div>

@endif

@endsection
