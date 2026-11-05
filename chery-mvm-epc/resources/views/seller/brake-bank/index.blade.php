@extends('layouts.app')

@section('title', 'بانک لنت و فروش لنت')

@section('content')

<style>
    .brake-page {
        direction: rtl;
        color: #e8eef8;
        padding: 24px 0;
    }

    .brake-hero {
        background: linear-gradient(120deg, #101b30, #19355b, #10434b);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .brake-hero h1 {
        margin: 0 0 10px;
        font-size: 25px;
        color: #fff;
    }

    .brake-hero p {
        margin: 0;
        color: #c5d4e9;
    }

    .brake-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .brake-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        border: 0;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
    }

    .brake-btn-primary {
        background: #55e0d5;
        color: #09202a;
    }

    .brake-btn-secondary {
        background: rgba(255,255,255,.1);
        color: #fff;
    }

    .brake-search {
        display: flex;
        gap: 10px;
        margin-bottom: 22px;
    }

    .brake-search input {
        flex: 1;
        min-width: 0;
        padding: 13px 15px;
        border: 1px solid #293b55;
        border-radius: 12px;
        background: #101b2d;
        color: #fff;
        outline: none;
    }

    .brake-search input:focus,
    .brake-form input:focus,
    .brake-form select:focus {
        border-color: #55e0d5;
    }

    .brake-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 18px;
    }

    .brake-card {
        background: #101b2d;
        border: 1px solid #263951;
        border-radius: 17px;
        overflow: hidden;
    }

    .brake-card-body {
        padding: 18px;
    }

    .brake-code {
        color: #55e0d5;
        font-size: 12px;
        font-weight: 800;
        direction: ltr;
        text-align: right;
    }

    .brake-card h3 {
        color: #fff;
        font-size: 18px;
        margin: 10px 0;
    }

    .brake-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: 12px 0;
    }

    .brake-meta span {
        background: #192b43;
        color: #c7d6e9;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 11px;
    }

    .brake-price {
        color: #55e0d5;
        font-weight: 800;
        margin: 12px 0;
    }

    .brake-divider {
        border: 0;
        border-top: 1px solid #293b55;
        margin: 17px 0;
    }

    .brake-form label {
        display: block;
        color: #c9d6e8;
        font-size: 12px;
        margin: 12px 0 7px;
    }

    .brake-form input,
    .brake-form select {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 11px;
        border: 1px solid #293b55;
        border-radius: 9px;
        background: #0b1524;
        color: #fff;
        outline: none;
    }

    .brake-form select[multiple] {
        min-height: 110px;
    }

    .brake-help {
        color: #91a4bd;
        font-size: 11px;
        margin-top: 6px;
    }

    .brake-status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-pending {
        color: #ffd68a;
        background: rgba(245,158,11,.13);
    }

    .status-approved {
        color: #72e5a5;
        background: rgba(34,197,94,.13);
    }

    .status-rejected,
    .status-suspended {
        color: #ff9292;
        background: rgba(239,68,68,.13);
    }

    .brake-empty {
        text-align: center;
        padding: 45px 15px;
        background: #101b2d;
        border: 1px dashed #40516a;
        border-radius: 15px;
        color: #aebed2;
    }

    .brake-alert {
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 18px;
        background: rgba(34,197,94,.12);
        color: #8ce5ae;
    }

    .brake-error {
        color: #ff9999;
        font-size: 12px;
        margin-top: 5px;
    }

    @media(max-width:600px) {
        .brake-search {
            flex-direction: column;
        }

        .brake-hero {
            padding: 20px;
        }
    }
</style>

<div class="brake-page">

    <div class="brake-hero">
        <h1>بانک لنت و فروش لنت</h1>

        <p>
            لنت موردنظر خود را از بانک انتخاب کنید و لینک فروش آن را ثبت کنید.
            درخواست شما پس از بررسی مدیر در بخش فروش نمایش داده خواهد شد.
        </p>

        <div class="brake-actions">
            <a href="{{ route('seller.brake-bank.offers') }}"
               class="brake-btn brake-btn-secondary">
                درخواست‌های من
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="brake-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="brake-alert" style="background:rgba(239,68,68,.12);color:#ffaaaa">
            لطفاً اطلاعات فرم را بررسی کنید.
        </div>
    @endif

    <form action="{{ route('seller.brake-bank.index') }}"
          method="GET"
          class="brake-search">

        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="جست‌وجو با نام لنت یا کد فنی..."
        >

        <button type="submit" class="brake-btn brake-btn-primary">
            جست‌وجو
        </button>

    </form>

    @if($pads->count())

        <div class="brake-grid">

            @foreach($pads as $pad)

                @php
                    $offer = $pad->sellerOffers->first();
                    $selectedBrandIds = $offer
                        ? $offer->brands->pluck('id')->all()
                        : [];
                @endphp

                <div class="brake-card">

                    <div class="brake-card-body">

                        <div class="brake-code">
                            {{ $pad->technical_code }}
                        </div>

                        <h3>{{ $pad->name }}</h3>

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

                        @if($pad->price !== null)
                            <div class="brake-price">
                                قیمت بانک:
                                {{ number_format((float) $pad->price) }}
                            </div>
                        @endif

                        @if($pad->images->isNotEmpty())
                            <div class="brake-meta">
                                <span>
                                    {{ $pad->images->count() }} تصویر در بانک
                                </span>
                            </div>
                        @endif

                        @if($offer)
                            @php
                                $statusLabels = [
                                    'pending' => 'در انتظار بررسی',
                                    'approved' => 'تأیید شده',
                                    'rejected' => 'رد شده',
                                    'suspended' => 'تعلیق شده',
                                ];
                            @endphp

                            <div style="margin:12px 0">
                                <span class="brake-status status-{{ $offer->status }}">
                                    {{ $statusLabels[$offer->status] ?? $offer->status }}
                                </span>
                            </div>
                        @endif

                        <hr class="brake-divider">

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
                                برای انتخاب چند برند، Ctrl و در مک Command را نگه دارید.
                            </div>

                            @error('brand_ids')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            @error('brand_ids.*')
                                <div class="brake-error">{{ $message }}</div>
                            @enderror

                            <button
                                type="submit"
                                class="brake-btn brake-btn-primary"
                                style="width:100%;margin-top:18px"
                            >
                                {{ $offer ? 'به‌روزرسانی درخواست فروش' : 'ثبت درخواست فروش این لنت' }}
                            </button>

                        </form>

                    </div>
                </div>

            @endforeach

        </div>

        <div style="margin-top:25px">
            {{ $pads->links() }}
        </div>

    @else

        <div class="brake-empty">
            <h3>لنتی پیدا نشد</h3>
            <p>عبارت جست‌وجو را تغییر دهید یا کد فنی دیگری وارد کنید.</p>
        </div>

    @endif

</div>

@endsection