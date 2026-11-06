
@extends('layouts.app')

@section('title', 'بانک لنت و فروش لنت | PartCar')

@section('content')

<div class="pc-brake-page" dir="rtl">

    <style>
        .pc-brake-page {
            --navy: #0B1730;
            --blue: #087BFA;
            --text: #17243A;
            --muted: #718096;
            --border: #E7ECF3;
            --surface: #FFFFFF;
            --background: #F4F7FB;
            color: var(--text);
            padding: 10px 0 40px;
        }

        .pc-brake-page * {
            box-sizing: border-box;
        }

        .pc-brake-page .brake-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .pc-brake-page .brake-heading h1 {
            margin: 0;
            font-size: 25px;
            font-weight: 850;
            color: var(--navy);
        }

        .pc-brake-page .brake-heading p {
            color: var(--muted);
            font-size: 13px;
            line-height: 2;
            margin: 9px 0 0;
            max-width: 650px;
        }

        .pc-brake-page .brake-header-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .pc-brake-page .brake-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 10px;
            border: 1px solid transparent;
            font-family: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none !important;
            cursor: pointer;
            transition: .2s ease;
        }

        .pc-brake-page .brake-btn-primary {
            background: var(--blue);
            color: #fff !important;
        }

        .pc-brake-page .brake-btn-primary:hover {
            background: #0666D1;
        }

        .pc-brake-page .brake-btn-light {
            background: #fff;
            color: var(--navy) !important;
            border-color: var(--border);
        }

        .pc-brake-page .brake-btn-light:hover {
            border-color: #A9CEFA;
            background: #F8FBFF;
        }

        .pc-brake-page .brake-alert {
            padding: 13px 16px;
            border-radius: 11px;
            margin-bottom: 17px;
            font-size: 13px;
            line-height: 1.9;
            background: #EFFAF4;
            border: 1px solid #C8EBD6;
            color: #167346;
        }

        .pc-brake-page .brake-alert-error {
            background: #FFF3F2;
            border-color: #F6D0CC;
            color: #B42318;
        }

        .pc-brake-page .brake-search-panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 17px;
            margin-bottom: 23px;
        }

        .pc-brake-page .search-label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .pc-brake-page .brake-search {
            display: flex;
            gap: 10px;
        }

        .pc-brake-page .brake-search input {
            flex: 1;
            min-width: 0;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 0 14px;
            background: #FAFBFD;
            color: var(--text);
            font-family: inherit;
            font-size: 13px;
            outline: none;
        }

        .pc-brake-page input:focus,
        .pc-brake-page select:focus {
            border-color: var(--blue) !important;
            box-shadow: 0 0 0 3px rgba(8,123,250,.09);
            outline: none;
        }

        .pc-brake-page .results-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 0 0 14px;
        }

        .pc-brake-page .results-heading h2 {
            color: var(--navy);
            font-size: 16px;
            font-weight: 850;
            margin: 0;
        }

        .pc-brake-page .results-count {
            color: var(--muted);
            font-size: 11px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 6px 11px;
        }

        .pc-brake-page .brake-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 16px;
        }

        .pc-brake-page .brake-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: .2s ease;
        }

        .pc-brake-page .brake-card:hover {
            border-color: #B8D6FA;
            box-shadow: 0 9px 25px rgba(15,45,85,.06);
        }

        .pc-brake-page .brake-card-body {
            padding: 19px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pc-brake-page .brake-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .pc-brake-page .brake-code {
            color: #087BFA;
            background: #EEF6FF;
            border-radius: 7px;
            padding: 6px 9px;
            font-size: 11px;
            font-weight: 850;
            direction: ltr;
            text-align: left;
            overflow-wrap: anywhere;
        }

        .pc-brake-page .brake-card h3 {
            color: var(--navy);
            font-size: 17px;
            font-weight: 850;
            line-height: 1.7;
            margin: 0 0 12px;
        }

        .pc-brake-page .brake-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 13px;
        }

        .pc-brake-page .brake-meta span {
            background: #F3F6FA;
            border: 1px solid #E9EEF4;
            color: #52647A;
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 600;
        }

        .pc-brake-page .brake-price-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 11px 12px;
            background: #F7FAFE;
            border: 1px solid #EAF0F7;
            border-radius: 9px;
            margin-bottom: 12px;
        }

        .pc-brake-page .brake-price-label {
            font-size: 11px;
            color: var(--muted);
        }

        .pc-brake-page .brake-price {
            color: #087BFA;
            font-size: 14px;
            font-weight: 900;
        }

        .pc-brake-page .brake-image-count {
            color: #718096;
            font-size: 11px;
            margin: 0 0 10px;
        }

        .pc-brake-page .brake-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 30px;
            padding: 7px 10px;
            font-size: 10px;
            font-weight: 800;
        }

        .pc-brake-page .status-pending {
            color: #9A6700;
            background: #FFF5D9;
        }

        .pc-brake-page .status-approved {
            color: #167346;
            background: #EAF8EF;
        }

        .pc-brake-page .status-rejected,
        .pc-brake-page .status-suspended {
            color: #B42318;
            background: #FFF0EF;
        }

        .pc-brake-page .brake-status-area {
            margin: 0 0 12px;
        }

        .pc-brake-page .brake-divider {
            border: 0;
            border-top: 1px solid #EDF0F4;
            margin: 4px 0 17px;
        }

        .pc-brake-page .form-heading {
            color: var(--navy);
            font-size: 13px;
            font-weight: 850;
            margin: 0 0 13px;
        }

        .pc-brake-page .brake-form {
            margin-top: auto;
        }

        .pc-brake-page .brake-form label {
            display: block;
            color: #52647A;
            font-size: 11px;
            font-weight: 750;
            margin: 12px 0 7px;
        }

        .pc-brake-page .brake-form input,
        .pc-brake-page .brake-form select {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 11px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #FAFBFD;
            color: var(--text);
            font-family: inherit;
            font-size: 12px;
            outline: none;
        }

        .pc-brake-page .brake-form input {
            direction: ltr;
            text-align: left;
        }

        .pc-brake-page .brake-form select[multiple] {
            min-height: 115px;
            direction: rtl;
            text-align: right;
            padding: 7px;
        }

        .pc-brake-page .brake-form select option {
            padding: 7px;
        }

        .pc-brake-page .brake-help {
            color: #8996A8;
            font-size: 10px;
            line-height: 1.8;
            margin-top: 6px;
        }

        .pc-brake-page .brake-error {
            color: #C62828;
            font-size: 11px;
            margin-top: 6px;
        }

        .pc-brake-page .brake-submit {
            width: 100%;
            margin-top: 17px;
            min-height: 42px;
        }

        .pc-brake-page .brake-empty {
            text-align: center;
            padding: 55px 20px;
            background: #fff;
            border: 1px dashed #CCD6E2;
            border-radius: 15px;
        }

        .pc-brake-page .empty-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            background: #F1F5FA;
            border-radius: 15px;
            font-size: 24px;
        }

        .pc-brake-page .brake-empty h3 {
            color: var(--navy);
            font-size: 16px;
            margin: 0 0 8px;
        }

        .pc-brake-page .brake-empty p {
            color: var(--muted);
            font-size: 12px;
            margin: 0;
            line-height: 1.9;
        }

        .pc-brake-page .brake-pagination {
            margin-top: 23px;
        }

        .pc-brake-page .brake-pagination nav {
            display: flex;
            justify-content: center;
        }

        @media(max-width: 600px) {
            .pc-brake-page .brake-heading h1 {
                font-size: 21px;
            }

            .pc-brake-page .brake-header {
                flex-direction: column;
            }

            .pc-brake-page .brake-header-actions,
            .pc-brake-page .brake-header-actions .brake-btn {
                width: 100%;
            }

            .pc-brake-page .brake-search {
                flex-direction: column;
            }

            .pc-brake-page .brake-search .brake-btn {
                width: 100%;
            }

            .pc-brake-page .brake-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- سربرگ صفحه --}}
    <div class="brake-header">

        <div class="brake-heading">
            <h1>بانک لنت و فروش لنت</h1>
            <p>
                بانک تخصصی اطلاعات لنت‌های خودرو؛ محصول موردنظر را پیدا کنید،
                لینک فروشگاه خود را ثبت کنید و پس از تأیید مدیریت در بخش فروش نمایش دهید.
            </p>
        </div>

        <div class="brake-header-actions">
            <a href="{{ route('seller.brake-bank.offers') }}"
               class="brake-btn brake-btn-light">
                <span>▤</span>
                درخواست‌های فروش من
            </a>
        </div>

    </div>

    {{-- پیام موفقیت --}}
    @if(session('success'))
        <div class="brake-alert">
            <strong>✓</strong>
            {{ session('success') }}
        </div>
    @endif

    {{-- خطاهای فرم --}}
    @if($errors->any())
        <div class="brake-alert brake-alert-error">
            <strong>!</strong>
            لطفاً اطلاعات فرم را بررسی کنید.
        </div>
    @endif

    {{-- جست‌وجو --}}
    <div class="brake-search-panel">

        <label class="search-label">
            جست‌وجو در بانک لنت
        </label>

        <form action="{{ route('seller.brake-bank.index') }}"
              method="GET"
              class="brake-search">

            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="نام لنت یا کد فنی را وارد کنید..."
            >

            <button type="submit" class="brake-btn brake-btn-primary">
                <span>⌕</span>
                جست‌وجو
            </button>

        </form>

    </div>

    {{-- عنوان نتایج --}}
    <div class="results-heading">
        <h2>لنت‌های موجود در بانک</h2>
        <span class="results-count">
            {{ number_format($pads->total()) }} نتیجه
        </span>
    </div>

    @if($pads->count())

        <div class="brake-grid">

            @foreach($pads as $pad)

                @php
                    $offer = $pad->sellerOffers->first();

                    $selectedBrandIds = $offer
                        ? $offer->brands->pluck('id')->all()
                        : [];

                    $statusLabels = [
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تأیید شده',
                        'rejected' => 'رد شده',
                        'suspended' => 'تعلیق شده',
                    ];
                @endphp

                <article class="brake-card">

                    <div class="brake-card-body">

                        {{-- اطلاعات اصلی لنت --}}
                        <div class="brake-card-top">

                            <span class="brake-code">
                                {{ $pad->technical_code }}
                            </span>

                            @if($offer)
                                <span class="brake-status status-{{ $offer->status }}">
                                    {{ $statusLabels[$offer->status] ?? $offer->status }}
                                </span>
                            @endif

                        </div>

                        <h3>{{ $pad->name }}</h3>

                        {{-- مشخصات --}}
                        <div class="brake-meta">

                            @if($pad->position)
                                <span>
                                    موقعیت:
                                    {{ $pad->position === 'front' ? 'جلو' : ($pad->position === 'rear' ? 'عقب' : $pad->position) }}
                                </span>
                            @endif

                            @if($pad->pad_type)
                                <span>
                                    نوع: {{ $pad->pad_type }}
                                </span>
                            @endif

                            @if($pad->carton_quantity)
                                <span>
                                    تعداد کارتن: {{ $pad->carton_quantity }}
                                </span>
                            @endif

                        </div>

                        {{-- قیمت بانک --}}
                        @if($pad->price !== null)
                            <div class="brake-price-box">
                                <span class="brake-price-label">قیمت ثبت‌شده در بانک</span>
                                <span class="brake-price">
                                    {{ number_format((float) $pad->price) }}
                                    <small>تومان</small>
                                </span>
                            </div>
                        @endif

                        {{-- تصاویر --}}
                        @if($pad->images->isNotEmpty())
                            <div class="brake-image-count">
                                ▧ {{ $pad->images->count() }} تصویر ثبت‌شده در بانک
                            </div>
                        @endif

                        <hr class="brake-divider">

                        {{-- فرم ثبت یا ویرایش پیشنهاد فروش --}}
                        <h4 class="form-heading">
                            {{ $offer ? 'ویرایش اطلاعات فروش شما' : 'ثبت پیشنهاد فروش این لنت' }}
                        </h4>

                        <form
                            action="{{ route('seller.brake-bank.store', $pad->id) }}"
                            method="POST"
                            class="brake-form"
                        >

                            @csrf

                            <label>لینک خرید محصول در سایت شما</label>

                            <input
                                type="url"
                                name="product_url"
                                required
                                maxlength="2048"
                                placeholder="https://example.com/product/..."
                                value="{{ old('product_url', $offer?->product_url) }}"
                            >

                            @error('product_url')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            <label>لینک تصویر محصول در سایت شما</label>

                            <input
                                type="url"
                                name="image_url"
                                maxlength="2048"
                                placeholder="https://example.com/image.jpg"
                                value="{{ old('image_url', $offer?->image_url) }}"
                            >

                            @error('image_url')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            <label>برندهای قابل فروش</label>

                            <select name="brand_ids[]" multiple required>

                                @foreach($brands as $brand)

                                    <option
                                        value="{{ $brand->id }}"
                                        @selected(
                                            in_array(
                                                (int) $brand->id,
                                                old('brand_ids', $selectedBrandIds)
                                            )
                                        )
                                    >
                                        {{ $brand->name }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="brake-help">
                                برای انتخاب چند برند، کلید Ctrl و در مک Command را نگه دارید.
                            </div>

                            @error('brand_ids')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            @error('brand_ids.*')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            <button
                                type="submit"
                                class="brake-btn brake-btn-primary brake-submit"
                            >
                                {{ $offer ? 'ذخیره تغییرات درخواست' : 'ثبت درخواست فروش' }}
                            </button>

                        </form>

                    </div>

                </article>

            @endforeach

        </div>

        {{-- صفحه‌بندی --}}
        <div class="brake-pagination">
            {{ $pads->links() }}
        </div>

    @else

        {{-- نتیجه خالی --}}
        <div class="brake-empty">

            <div class="empty-icon">⌕</div>

            <h3>نتیجه‌ای پیدا نشد</h3>

            <p>
                لنتی با این نام یا کد فنی در بانک پیدا نشد.
                عبارت جست‌وجو را تغییر دهید و دوباره امتحان کنید.
            </p>

        </div>

    @endif

</div>

@endsection
