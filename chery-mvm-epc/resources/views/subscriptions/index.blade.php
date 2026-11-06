
@extends('layouts.app')

@section('title', 'خرید اشتراک | PartCar')

@section('content')

<div class="pc-subscription-page" dir="rtl">

    <style>
        .pc-subscription-page {
            --pc-navy: #0B1730;
            --pc-blue: #087BFA;
            --pc-text: #17243A;
            --pc-muted: #718096;
            --pc-border: #E7ECF3;
            --pc-bg: #F4F7FB;
            color: var(--pc-text);
            padding: 10px 0 40px;
        }

        .pc-subscription-page * {
            box-sizing: border-box;
        }

        .pc-subscription-page .sub-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .pc-subscription-page .sub-title {
            margin: 0;
            font-size: 25px;
            font-weight: 850;
            color: var(--pc-navy);
            letter-spacing: -.5px;
        }

        .pc-subscription-page .sub-description {
            margin: 9px 0 0;
            color: var(--pc-muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .pc-subscription-page .sub-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #EAF3FF;
            color: #087BFA;
            border-radius: 30px;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 800;
        }

        .pc-subscription-page .sub-alert {
            padding: 15px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.9;
            border: 1px solid;
        }

        .pc-subscription-page .sub-alert-success {
            background: #EFFAF4;
            color: #167346;
            border-color: #C8EBD6;
        }

        .pc-subscription-page .sub-alert-error {
            background: #FFF3F2;
            color: #B42318;
            border-color: #F6D0CC;
        }

        .pc-subscription-page .sub-alert p {
            margin: 3px 0;
        }

        .pc-subscription-page .sub-section {
            margin-bottom: 30px;
        }

        .pc-subscription-page .section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 17px;
        }

        .pc-subscription-page .section-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #EAF3FF;
            color: var(--pc-blue);
            font-size: 20px;
            flex-shrink: 0;
        }

        .pc-subscription-page .section-heading h2 {
            font-size: 17px;
            font-weight: 850;
            color: var(--pc-navy);
            margin: 0 0 5px;
        }

        .pc-subscription-page .section-heading p {
            font-size: 12px;
            color: var(--pc-muted);
            margin: 0;
        }

        .pc-subscription-page .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 17px;
        }

        .pc-subscription-page .plan-card {
            background: #fff;
            border: 1px solid var(--pc-border);
            border-radius: 17px;
            padding: 23px;
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 250px;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .pc-subscription-page .plan-card:hover {
            border-color: #9CCBFF;
            box-shadow: 0 12px 30px rgba(15, 45, 85, .07);
            transform: translateY(-3px);
        }

        .pc-subscription-page .plan-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 22px;
        }

        .pc-subscription-page .plan-name {
            font-size: 16px;
            font-weight: 850;
            color: var(--pc-navy);
            margin: 0;
        }

        .pc-subscription-page .plan-type {
            font-size: 10px;
            font-weight: 800;
            color: #087BFA;
            background: #EAF3FF;
            padding: 6px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .pc-subscription-page .plan-price {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 12px;
        }

        .pc-subscription-page .price-number {
            font-size: 27px;
            font-weight: 900;
            letter-spacing: -.8px;
            color: var(--pc-navy);
        }

        .pc-subscription-page .price-unit {
            color: var(--pc-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .pc-subscription-page .plan-duration {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--pc-muted);
            font-size: 12px;
            padding: 12px 0;
            border-top: 1px solid #F0F2F6;
            margin-top: 3px;
        }

        .pc-subscription-page .plan-duration span {
            color: var(--pc-blue);
            font-weight: 800;
        }

        .pc-subscription-page .plan-action {
            width: 100%;
            margin-top: auto;
            border: 0;
            border-radius: 10px;
            background: var(--pc-blue);
            color: #fff;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s ease;
        }

        .pc-subscription-page .plan-action:hover {
            background: #0666D1;
        }

        .pc-subscription-page .seller-notice {
            background: #fff;
            border: 1px solid var(--pc-border);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pc-subscription-page .seller-notice-content {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex: 1;
            min-width: 220px;
        }

        .pc-subscription-page .seller-notice-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #FFF5E5;
            color: #C27A12;
            font-size: 20px;
        }

        .pc-subscription-page .seller-notice h3 {
            font-size: 15px;
            font-weight: 850;
            margin: 0 0 7px;
            color: var(--pc-navy);
        }

        .pc-subscription-page .seller-notice p {
            font-size: 12px;
            line-height: 2;
            color: var(--pc-muted);
            margin: 0;
        }

        .pc-subscription-page .seller-register-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--pc-navy);
            color: #fff !important;
            text-decoration: none !important;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            transition: background .2s ease;
        }

        .pc-subscription-page .seller-register-btn:hover {
            background: #1A3158;
        }

        .pc-subscription-page .sub-footer-note {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F0F6FF;
            border: 1px solid #DCEAFF;
            color: #41658F;
            padding: 14px 17px;
            border-radius: 12px;
            font-size: 12px;
            line-height: 1.9;
            margin-top: 25px;
        }

        @media(max-width: 600px) {
            .pc-subscription-page .sub-title {
                font-size: 21px;
            }

            .pc-subscription-page .plans-grid {
                grid-template-columns: 1fr;
            }

            .pc-subscription-page .plan-card {
                min-height: auto;
            }

            .pc-subscription-page .seller-notice {
                align-items: stretch;
            }

            .pc-subscription-page .seller-register-btn {
                width: 100%;
            }
        }
    </style>

    {{-- عنوان صفحه --}}
    <div class="sub-header">
        <div>
            <h1 class="sub-title">پلن‌های اشتراک پارت‌کار</h1>
            <p class="sub-description">
                پلن مناسب خود را انتخاب کنید و به امکانات تخصصی کاتالوگ قطعات دسترسی داشته باشید.
            </p>
        </div>

        <div class="sub-label">
            <span>✦</span>
            اشتراک‌های فعال سامانه
        </div>
    </div>

    {{-- پیام‌ها --}}
    @if(session('success'))
        <div class="sub-alert sub-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('subscription_error'))
        <div class="sub-alert sub-alert-error">
            {{ session('subscription_error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="sub-alert sub-alert-error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- اشتراک کاربران --}}
    <section class="sub-section">

        <div class="section-heading">
            <div class="section-icon">👤</div>
            <div>
                <h2>اشتراک کاربران</h2>
                <p>برای جست‌وجو و استفاده از بانک اطلاعات قطعات خودروهای چینی</p>
            </div>
        </div>

        <div class="plans-grid">

            @foreach($plans['user'] as $key => $plan)

                <div class="plan-card">

                    <div class="plan-top">
                        <h3 class="plan-name">{{ $plan['title'] }}</h3>
                        <span class="plan-type">پلن کاربری</span>
                    </div>

                    <div class="plan-price">
                        <span class="price-number">
                            {{ number_format($plan['price']) }}
                        </span>
                        <span class="price-unit">تومان</span>
                    </div>

                    <div class="plan-duration">
                        <span>◷</span>
                        مدت اعتبار:
                        <span>{{ number_format($plan['duration_days']) }} روز</span>
                    </div>

                    <form method="POST" action="{{ route('subscriptions.store') }}">
                        @csrf

                        <input type="hidden" name="role" value="user">
                        <input type="hidden" name="plan" value="{{ $key }}">

                        <button type="submit" class="plan-action">
                            انتخاب و خرید اشتراک
                        </button>
                    </form>

                </div>

            @endforeach

        </div>
    </section>

    {{-- اشتراک فروشندگان --}}
    @if($isSeller)

        <section class="sub-section">

            <div class="section-heading">
                <div class="section-icon">🏪</div>
                <div>
                    <h2>اشتراک فروشندگان</h2>
                    <p>برای معرفی محصولات، ثبت موجودی و مدیریت فروشگاه در پارت‌کار</p>
                </div>
            </div>

            <div class="plans-grid">

                @foreach($plans['seller'] as $key => $plan)

                    <div class="plan-card">

                        <div class="plan-top">
                            <h3 class="plan-name">{{ $plan['title'] }}</h3>
                            <span class="plan-type">پلن فروشندگی</span>
                        </div>

                        <div class="plan-price">
                            <span class="price-number">
                                {{ number_format($plan['price']) }}
                            </span>
                            <span class="price-unit">تومان</span>
                        </div>

                        <div class="plan-duration">
                            <span>◷</span>
                            مدت اعتبار:
                            <span>{{ number_format($plan['duration_days']) }} روز</span>
                        </div>

                        <form method="POST" action="{{ route('subscriptions.store') }}">
                            @csrf

                            <input type="hidden" name="role" value="seller">
                            <input type="hidden" name="plan" value="{{ $key }}">

                            <button type="submit" class="plan-action">
                                انتخاب و خرید اشتراک
                            </button>
                        </form>

                    </div>

                @endforeach

            </div>
        </section>

    @else

        <section class="sub-section">

            <div class="section-heading">
                <div class="section-icon">🏪</div>
                <div>
                    <h2>اشتراک فروشندگان</h2>
                    <p>امکانات ویژه فروشگاه‌های قطعات خودرو</p>
                </div>
            </div>

            <div class="seller-notice">

                <div class="seller-notice-content">
                    <div class="seller-notice-icon">🔒</div>

                    <div>
                        <h3>فروشنده قطعات خودرو هستید؟</h3>
                        <p>
                            برای استفاده از امکانات فروشندگی، ابتدا درخواست خود را ثبت کنید.
                            پس از بررسی و تأیید مدیریت، امکان خرید اشتراک فروشندگان برای شما فعال خواهد شد.
                        </p>
                    </div>
                </div>

                <a href="{{ route('seller.register') }}" class="seller-register-btn">
                    ثبت درخواست فروشندگی
                    <span>←</span>
                </a>

            </div>

        </section>

    @endif

    <div class="sub-footer-note">
        <span>ⓘ</span>
        برای خرید هر پلن، دکمه انتخاب و خرید همان پلن را بزنید. اطلاعات و مبلغ اشتراک مطابق پلن انتخابی محاسبه خواهد شد.
    </div>

</div>

@endsection
