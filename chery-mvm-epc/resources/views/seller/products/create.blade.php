
@extends('layouts.app')

@section('title', 'ثبت لینک فروش قطعه | پارت‌کار')

@section('content')

<div class="pc-product-create" dir="rtl">

    <style>
        .pc-product-create {
            --pc-blue: #087BFA;
            --pc-navy: #0B1730;
            --pc-text: #172338;
            --pc-muted: #718096;
            --pc-border: #E5EAF1;
            --pc-bg: #F4F7FB;
            color: var(--pc-text);
            padding: 28px 20px 50px;
        }

        .pc-product-create * {
            box-sizing: border-box;
        }

        .pc-product-create .pc-wrapper {
            max-width: 1050px;
            margin: 0 auto;
        }

        .pc-product-create .pc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .pc-product-create .pc-title {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            color: var(--pc-navy);
        }

        .pc-product-create .pc-subtitle {
            color: var(--pc-muted);
            font-size: 13px;
            margin-top: 8px;
        }

        .pc-product-create .pc-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid var(--pc-border);
            border-radius: 10px;
            background: #fff;
            color: #475569;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .pc-product-create .pc-back:hover {
            border-color: var(--pc-blue);
            color: var(--pc-blue);
        }

        .pc-product-create .pc-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 22px;
            align-items: start;
        }

        .pc-product-create .pc-card {
            background: #fff;
            border: 1px solid var(--pc-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 3px 14px rgba(15, 35, 65, .035);
        }

        .pc-product-create .pc-card-title {
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 20px;
            color: var(--pc-navy);
        }

        .pc-product-create .pc-part-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 17px;
            margin-bottom: 25px;
            background: #F7F9FC;
            border: 1px solid var(--pc-border);
            border-radius: 12px;
        }

        .pc-product-create .pc-part-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #EAF3FF;
            color: var(--pc-blue);
            border-radius: 12px;
            font-size: 22px;
        }

        .pc-product-create .pc-part-name {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 9px;
        }

        .pc-product-create .pc-part-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 18px;
            color: var(--pc-muted);
            font-size: 12px;
        }

        .pc-product-create .pc-field {
            margin-bottom: 22px;
        }

        .pc-product-create .pc-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px;
            color: #344256;
        }

        .pc-product-create .pc-required {
            color: #E05252;
        }

        .pc-product-create .pc-input {
            width: 100%;
            height: 48px;
            border: 1px solid #DCE3EC;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 13px;
            color: var(--pc-text);
            background: #fff;
            outline: none;
            transition: .2s;
        }

        .pc-product-create .pc-input:focus {
            border-color: var(--pc-blue);
            box-shadow: 0 0 0 3px rgba(8, 123, 250, .09);
        }

        .pc-product-create .pc-input::placeholder {
            color: #A0AAB8;
        }

        .pc-product-create .pc-hint {
            margin-top: 8px;
            color: var(--pc-muted);
            font-size: 11px;
            line-height: 1.9;
        }

        .pc-product-create .pc-error {
            margin-bottom: 20px;
            padding: 14px 17px;
            border: 1px solid #F4CACA;
            background: #FFF5F5;
            color: #B42318;
            border-radius: 11px;
            font-size: 13px;
        }

        .pc-product-create .pc-error ul {
            margin: 0;
            padding-right: 18px;
        }

        .pc-product-create .pc-error li + li {
            margin-top: 5px;
        }

        .pc-product-create .pc-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 8px;
        }

        .pc-product-create .pc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;
            padding: 0 22px;
            border: 0;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }

        .pc-product-create .pc-btn-primary {
            background: var(--pc-blue);
            color: #fff;
        }

        .pc-product-create .pc-btn-primary:hover {
            background: #0668D5;
        }

        .pc-product-create .pc-btn-light {
            background: #F1F4F8;
            color: #475569;
        }

        .pc-product-create .pc-btn-light:hover {
            background: #E7ECF3;
        }

        .pc-product-create .pc-guide {
            border: 1px solid #DCE8F7;
            background: #F8FBFF;
            border-radius: 14px;
            padding: 19px;
            margin-bottom: 16px;
        }

        .pc-product-create .pc-guide-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 13px;
            color: #1459A6;
            font-size: 14px;
            font-weight: 800;
        }

        .pc-product-create .pc-guide-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E4F0FF;
            font-size: 16px;
        }

        .pc-product-create .pc-guide ul {
            padding-right: 18px;
            margin: 0;
            color: #52647B;
            font-size: 12px;
            line-height: 2.2;
        }

        .pc-product-create .pc-warning {
            border: 1px solid #F3D9A4;
            background: #FFFAEF;
            border-radius: 14px;
            padding: 17px;
        }

        .pc-product-create .pc-warning-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #946200;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 9px;
        }

        .pc-product-create .pc-warning p {
            margin: 0;
            color: #80652D;
            font-size: 12px;
            line-height: 2.1;
        }

        .pc-product-create .pc-score-note {
            margin-top: 15px;
            padding: 14px;
            background: #F3F8FF;
            border-radius: 11px;
            color: #315C8D;
            font-size: 12px;
            line-height: 2;
        }

        @media(max-width: 850px) {
            .pc-product-create .pc-layout {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 600px) {
            .pc-product-create {
                padding: 20px 12px 35px;
            }

            .pc-product-create .pc-title {
                font-size: 20px;
            }

            .pc-product-create .pc-card {
                padding: 17px;
            }

            .pc-product-create .pc-actions {
                flex-direction: column;
            }

            .pc-product-create .pc-btn {
                width: 100%;
            }

            .pc-product-create .pc-part-box {
                align-items: flex-start;
            }
        }
    </style>

    <div class="pc-wrapper">

        <div class="pc-header">
            <div>
                <h1 class="pc-title">ثبت لینک فروش قطعه</h1>
                <p class="pc-subtitle">
                    محصول خود را به کاتالوگ پارت‌کار متصل کنید تا مشتریان بتوانند آن را از فروشگاه شما تهیه کنند.
                </p>
            </div>

            <a href="{{ route('seller.products.catalog') }}" class="pc-back">
                ← بازگشت به کاتالوگ
            </a>
        </div>

        <div class="pc-layout">

            {{-- فرم ثبت محصول --}}
            <div class="pc-card">

                <h2 class="pc-card-title">اطلاعات محصول فروشگاه</h2>

                <div class="pc-part-box">
                    <div class="pc-part-icon">⚙</div>

                    <div>
                        <h3 class="pc-part-name">
                            {{ $part->name_fa }}
                        </h3>

                        <div class="pc-part-meta">
                            <span>
                                کد فنی:
                                <strong dir="ltr">
                                    {{ $part->technical_code ?: '-' }}
                                </strong>
                            </span>

                            <span>
                                دسته‌بندی:
                                <strong>{{ $part->category ?: '-' }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="pc-error">
                        <strong>لطفاً موارد زیر را بررسی کنید:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.products.store') }}">
                    @csrf

                    <input type="hidden" name="part_id" value="{{ $part->id }}">

                    <div class="pc-field">
                        <label class="pc-label">
                            لینک صفحه محصول در سایت شما
                            <span class="pc-required">*</span>
                        </label>

                        <input
                            type="url"
                            name="product_url"
                            value="{{ old('product_url') }}"
                            required
                            placeholder="https://example.com/product/..."
                            class="pc-input"
                            dir="ltr"
                        >

                        <p class="pc-hint">
                            لینک مستقیم صفحه همین قطعه در فروشگاه اینترنتی خود را وارد کنید.
                            لینک باید معتبر و قابل دسترسی باشد.
                        </p>
                    </div>

                    <div class="pc-field">
                        <label class="pc-label">
                            لینک تصویر واقعی محصول
                        </label>

                        <input
                            type="url"
                            name="image_url"
                            value="{{ old('image_url') }}"
                            placeholder="https://example.com/images/product.jpg"
                            class="pc-input"
                            dir="ltr"
                        >

                        <p class="pc-hint">
                            تصویر باید مستقیماً مربوط به همین قطعه باشد. استفاده از تصویر اختصاصی و باکیفیت فروشگاه توصیه می‌شود.
                            وارد کردن تصویر اختیاری است.
                        </p>
                    </div>

                    <div class="pc-actions">
                        <button type="submit" class="pc-btn pc-btn-primary">
                            ثبت محصول در پارت‌کار
                            <span>←</span>
                        </button>

                        <a href="{{ route('seller.products.catalog') }}" class="pc-btn pc-btn-light">
                            انصراف
                        </a>
                    </div>
                </form>

            </div>

            {{-- راهنمای فروشندگان --}}
            <aside>

                <div class="pc-guide">
                    <div class="pc-guide-heading">
                        <span class="pc-guide-icon">📷</span>
                        راهنمای انتخاب تصویر محصول
                    </div>

                    <ul>
                        <li>تصویر باید واقعی و مربوط به همان قطعه انتخاب‌شده باشد.</li>
                        <li>از تصاویر قطعات مشابه یا قطعات خودروهای دیگر استفاده نکنید.</li>
                        <li>تصویر باید واضح و باکیفیت باشد و محصول را به‌درستی نمایش دهد.</li>
                        <li>از تصاویر نامرتبط، جعلی یا تصاویر صرفاً تبلیغاتی استفاده نکنید.</li>
                        <li>در صورت امکان از عکس واقعی موجود در فروشگاه خود استفاده کنید.</li>
                    </ul>
                </div>

                <div class="pc-warning">
                    <div class="pc-warning-title">
                        <span>⚠️</span>
                        قوانین مهم ثبت تصویر
                    </div>

                    <p>
                        تصاویر ثبت‌شده توسط فروشندگان بررسی می‌شوند.
                        در صورت استفاده از تصویر غیرواقعی، نامرتبط یا مغایر با قطعه انتخاب‌شده،
                        امتیاز فروشنده کاهش می‌یابد و در صورت تخلف، امکان تعلیق یا مسدود شدن حساب کاربری وجود دارد.
                    </p>
                </div>

                <div class="pc-score-note">
                    <strong>امتیاز فروشنده چگونه محاسبه می‌شود؟</strong>
                    <br>
                    میزان تطابق تصویر با قطعه واقعی، صحت اطلاعات محصول و کیفیت محتوای ثبت‌شده در ارزیابی فروشنده مؤثر است.
                    تصاویر دقیق و واقعی به افزایش اعتماد مشتریان کمک می‌کنند.
                </div>

            </aside>

        </div>
    </div>
</div>

@endsection
