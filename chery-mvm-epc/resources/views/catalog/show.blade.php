
@extends('layouts.app')

@section('title', ($part->name_fa ?? 'جزئیات قطعه') . ' | PartCar')

@section('content')

@php
    $firstSellerProduct = $part->activeSellerProducts->first();

    $sellerOfferCount = isset($sellerOffers)
        ? $sellerOffers->count()
        : 0;

    $technicalFields = [
        ['label' => 'کد فنی', 'value' => $part->technical_code],
        ['label' => 'کد لجستیکی', 'value' => $part->logistics_code],
        ['label' => 'دسته‌بندی', 'value' => $part->category],
        ['label' => 'زیردسته', 'value' => $part->subcategory],
        ['label' => 'مدل خودرو', 'value' => $part->car_model_raw],
        ['label' => 'قیمت شرکت', 'value' => $part->price !== null ? number_format((float) $part->price) . ' تومان' : null],
        ['label' => 'قیمت با مالیات', 'value' => $part->price_tax !== null ? number_format((float) $part->price_tax) . ' تومان' : null],
    ];
@endphp

<style>
.pc-part-page {
    --pc-navy: #0B1730;
    --pc-blue: #087BFA;
    --pc-blue-light: #EFF6FF;
    --pc-bg: #F4F7FB;
    --pc-border: #E5EAF1;
    --pc-muted: #748196;
    --pc-text: #344256;

    direction: rtl;
    color: var(--pc-text);
    padding-bottom: 35px;
    font-family: inherit;
}

.pc-part-page *,
.pc-part-page *::before,
.pc-part-page *::after {
    box-sizing: border-box;
}

.pc-part-page a {
    text-decoration: none;
}

/* Navigation */

.pc-part-page .pc-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 19px;
}

.pc-part-page .pc-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    color: #8b98a9;
    font-size: 11px;
}

.pc-part-page .pc-breadcrumb a {
    color: var(--pc-blue);
}

.pc-part-page .pc-navigation {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.pc-part-page .pc-nav-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 38px;
    padding: 8px 13px;
    border: 1px solid var(--pc-border);
    border-radius: 9px;
    background: #fff;
    color: #536176;
    font-size: 11px;
    font-weight: 800;
    transition: .2s;
}

.pc-part-page .pc-nav-button:hover {
    border-color: #bfdbfe;
    background: #f8fbff;
    color: var(--pc-blue);
}

.pc-part-page .pc-nav-button.primary {
    border-color: var(--pc-blue);
    background: var(--pc-blue);
    color: #fff;
}

.pc-part-page .pc-nav-button.primary:hover {
    background: #0665d1;
    color: #fff;
}

/* Main showcase */

.pc-part-page .pc-product-hero {
    display: grid;
    grid-template-columns: minmax(260px, 390px) minmax(0, 1fr);
    gap: 27px;
    padding: 24px;
    margin-bottom: 19px;
    border: 1px solid var(--pc-border);
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 4px 18px rgba(15,35,65,.035);
}

.pc-part-page .pc-image-area {
    min-width: 0;
}

.pc-part-page .pc-main-image {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    overflow: hidden;
    padding: 15px;
    border: 1px solid #edf0f5;
    border-radius: 13px;
    background: #fff;
}

.pc-part-page .pc-main-image img {
    display: block;
    width: 100%;
    height: 300px;
    object-fit: contain;
    transition: transform .25s ease;
}

.pc-part-page .pc-main-image img:hover {
    transform: scale(1.035);
}

.pc-part-page .pc-image-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    min-height: 270px;
    border: 1px dashed #dce3ec;
    border-radius: 12px;
    background: #f8fafc;
    color: #9aa7b7;
    font-size: 12px;
}

.pc-part-page .pc-image-empty span {
    font-size: 38px;
}

.pc-part-page .pc-image-caption {
    margin-top: 9px;
    color: #9aa5b4;
    font-size: 10px;
    text-align: center;
}

.pc-part-page .pc-product-info {
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.pc-part-page .pc-product-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    width: fit-content;
    padding: 7px 10px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 10px;
    font-weight: 900;
}

.pc-part-page .pc-product-title {
    margin: 15px 0 8px;
    color: var(--pc-navy);
    font-size: clamp(21px, 2.4vw, 29px);
    font-weight: 900;
    line-height: 1.8;
}

.pc-part-page .pc-product-subtitle {
    margin: 0 0 20px;
    color: var(--pc-muted);
    font-size: 12px;
    line-height: 2;
}

.pc-part-page .pc-code-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 13px 15px;
    border: 1px solid #dbeafe;
    border-radius: 11px;
    background: #f8fbff;
}

.pc-part-page .pc-code-box small {
    display: block;
    margin-bottom: 5px;
    color: #8190a4;
    font-size: 10px;
}

.pc-part-page .pc-code-box strong {
    display: block;
    direction: ltr;
    unicode-bidi: plaintext;
    color: #1765c1;
    font-family: Consolas, monospace;
    font-size: 17px;
    font-weight: 900;
}

.pc-part-page .pc-code-copy {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 33px;
    padding: 7px 11px;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    background: #fff;
    color: #1765c1;
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
}

.pc-part-page .pc-code-copy:hover {
    background: #eff6ff;
}

.pc-part-page .pc-quick-info {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 10px;
    margin-top: 14px;
}

.pc-part-page .pc-quick-item {
    min-width: 0;
    padding: 13px;
    border: 1px solid var(--pc-border);
    border-radius: 10px;
    background: #fff;
}

.pc-part-page .pc-quick-item small {
    display: block;
    margin-bottom: 7px;
    color: #8b98a9;
    font-size: 10px;
}

.pc-part-page .pc-quick-item strong {
    display: block;
    color: var(--pc-navy);
    font-size: 12px;
    font-weight: 900;
    overflow-wrap: anywhere;
}

.pc-part-page .pc-quick-item.price strong {
    color: #168052;
}

.pc-part-page .pc-source-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: fit-content;
    min-height: 40px;
    margin-top: 16px;
    padding: 9px 14px;
    border: 1px solid var(--pc-border);
    border-radius: 9px;
    background: #f8fafc;
    color: #526176;
    font-size: 11px;
    font-weight: 800;
}

.pc-part-page .pc-source-link:hover {
    border-color: #bfdbfe;
    background: #eff6ff;
    color: var(--pc-blue);
}

/* Section common */

.pc-part-page .pc-section {
    margin-bottom: 19px;
    padding: 21px;
    border: 1px solid var(--pc-border);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(15,35,65,.03);
}

.pc-part-page .pc-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 19px;
}

.pc-part-page .pc-section-heading {
    display: flex;
    align-items: flex-start;
    gap: 11px;
}

.pc-part-page .pc-section-icon {
    display: grid;
    place-items: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border-radius: 11px;
    background: #eff6ff;
    color: var(--pc-blue);
    font-size: 18px;
}

.pc-part-page .pc-section-heading h2 {
    margin: 0;
    color: var(--pc-navy);
    font-size: 15px;
    font-weight: 900;
    line-height: 1.8;
}

.pc-part-page .pc-section-heading p {
    margin: 4px 0 0;
    color: var(--pc-muted);
    font-size: 10px;
    line-height: 1.9;
}

.pc-part-page .pc-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 35px;
    min-height: 29px;
    padding: 5px 10px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 11px;
    font-weight: 900;
}

/* Seller offers */

.pc-part-page .pc-seller-section {
    border-color: #cfe9e6;
}

.pc-part-page .pc-seller-section .pc-section-icon {
    background: #e8f7f4;
    color: #087f83;
}

.pc-part-page .pc-seller-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 14px;
}

.pc-part-page .pc-seller-card {
    display: grid;
    grid-template-columns: 125px minmax(0,1fr);
    gap: 15px;
    min-width: 0;
    padding: 13px;
    border: 1px solid #e5edf0;
    border-radius: 13px;
    background: #fff;
    transition: .2s ease;
}

.pc-part-page .pc-seller-card:hover {
    border-color: #9dd9d3;
    box-shadow: 0 6px 20px rgba(8,127,131,.07);
    transform: translateY(-2px);
}

.pc-part-page .pc-seller-image {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 0;
    min-height: 150px;
    overflow: hidden;
    border: 1px solid #edf0f5;
    border-radius: 10px;
    background: #f8fafc;
}

.pc-part-page .pc-seller-image img {
    width: 100%;
    height: 150px;
    padding: 7px;
    object-fit: contain;
}

.pc-part-page .pc-seller-image-empty {
    color: #9aa5b4;
    font-size: 10px;
    text-align: center;
    line-height: 2;
}

.pc-part-page .pc-seller-content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.pc-part-page .pc-seller-title-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

.pc-part-page .pc-seller-title {
    margin: 0;
    color: var(--pc-navy);
    font-size: 13px;
    font-weight: 900;
    line-height: 1.8;
}

.pc-part-page .pc-verified {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 7px;
    border-radius: 7px;
    background: #eaf8f0;
    color: #168052;
    font-size: 9px;
    font-weight: 900;
    white-space: nowrap;
}

.pc-part-page .pc-seller-product {
    margin: 9px 0;
    color: #77859a;
    font-size: 10px;
    line-height: 1.9;
}

.pc-part-page .pc-seller-price {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: auto;
    margin-bottom: 11px;
    padding: 9px 10px;
    border-radius: 8px;
    background: #f8fafc;
}

.pc-part-page .pc-seller-price span {
    color: #8794a5;
    font-size: 9px;
}

.pc-part-page .pc-seller-price strong {
    color: #9a6b0b;
    font-size: 11px;
    font-weight: 900;
}

.pc-part-page .pc-seller-buy {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 10px;
    border-radius: 8px;
    background: #087f83;
    color: #fff;
    font-size: 10px;
    font-weight: 900;
    transition: .2s;
}

.pc-part-page .pc-seller-buy:hover {
    background: #06686b;
    color: #fff;
}

.pc-part-page .pc-empty-state {
    padding: 35px 15px;
    border: 1px dashed #dce3ec;
    border-radius: 12px;
    background: #f8fafc;
    text-align: center;
}

.pc-part-page .pc-empty-state-icon {
    margin-bottom: 10px;
    color: #9aa7b7;
    font-size: 30px;
}

.pc-part-page .pc-empty-state strong {
    display: block;
    margin-bottom: 7px;
    color: var(--pc-navy);
    font-size: 13px;
}

.pc-part-page .pc-empty-state p {
    margin: 0;
    color: var(--pc-muted);
    font-size: 11px;
    line-height: 2;
}

/* Technical specifications */

.pc-part-page .pc-spec-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 11px;
}

.pc-part-page .pc-spec-item {
    min-width: 0;
    padding: 13px 14px;
    border: 1px solid var(--pc-border);
    border-radius: 10px;
    background: #fbfcfe;
}

.pc-part-page .pc-spec-item small {
    display: block;
    margin-bottom: 7px;
    color: #8491a3;
    font-size: 10px;
}

.pc-part-page .pc-spec-item strong {
    display: block;
    color: #29384d;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

.pc-part-page .pc-spec-item.code strong {
    direction: ltr;
    unicode-bidi: plaintext;
    font-family: Consolas, monospace;
    color: #1765c1;
}

/* Compatibility */

.pc-part-page .pc-vehicle-list,
.pc-part-page .pc-replacement-list {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.pc-part-page .pc-vehicle-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 13px;
    border: 1px solid #dbeafe;
    border-radius: 9px;
    background: #f4f8ff;
    color: #315b91;
    font-size: 11px;
    font-weight: 800;
}

.pc-part-page .pc-vehicle-chip::before {
    content: "🚘";
    font-size: 13px;
}

.pc-part-page .pc-replacement-chip {
    display: inline-flex;
    align-items: center;
    padding: 10px 13px;
    border: 1px solid #dce3ec;
    border-radius: 9px;
    background: #f8fafc;
    color: #344256;
    direction: ltr;
    font-family: Consolas, monospace;
    font-size: 12px;
    font-weight: 900;
}

/* Related parts */

.pc-part-page .pc-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--pc-border);
    border-radius: 11px;
}

.pc-part-page .pc-related-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
}

.pc-part-page .pc-related-table th {
    padding: 13px;
    background: #f8fafc;
    color: #748196;
    font-size: 10px;
    font-weight: 900;
    text-align: right;
    white-space: nowrap;
    border-bottom: 1px solid var(--pc-border);
}

.pc-part-page .pc-related-table td {
    padding: 13px;
    color: #465469;
    font-size: 11px;
    border-bottom: 1px solid #edf0f5;
}

.pc-part-page .pc-related-table tbody tr:last-child td {
    border-bottom: 0;
}

.pc-part-page .pc-related-name {
    color: var(--pc-navy);
    font-weight: 900;
}

.pc-part-page .pc-related-code {
    direction: ltr;
    unicode-bidi: plaintext;
    color: #1765c1;
    font-family: Consolas, monospace;
    font-weight: 800;
}

.pc-part-page .pc-related-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 32px;
    padding: 7px 10px;
    border-radius: 8px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 10px;
    font-weight: 900;
}

.pc-part-page .pc-related-link:hover {
    background: #dbeafe;
    color: #0756b5;
}

/* Responsive */

@media(max-width: 1050px) {
    .pc-part-page .pc-seller-grid {
        grid-template-columns: 1fr;
    }

    .pc-part-page .pc-spec-grid {
        grid-template-columns: repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 700px) {
    .pc-part-page .pc-topbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .pc-part-page .pc-product-hero {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 19px;
    }

    .pc-part-page .pc-main-image {
        min-height: 220px;
    }

    .pc-part-page .pc-main-image img {
        height: 220px;
    }

    .pc-part-page .pc-section {
        padding: 15px;
    }

    .pc-part-page .pc-spec-grid {
        grid-template-columns: 1fr;
    }

    .pc-part-page .pc-seller-card {
        grid-template-columns: 95px minmax(0,1fr);
        gap: 10px;
        padding: 10px;
    }

    .pc-part-page .pc-seller-image,
    .pc-part-page .pc-seller-image img {
        min-height: 120px;
        height: 120px;
    }

    .pc-part-page .pc-seller-title-row {
        flex-direction: column;
        gap: 5px;
    }

    .pc-part-page .pc-quick-info {
        grid-template-columns: 1fr 1fr;
    }
}

@media(max-width: 380px) {
    .pc-part-page .pc-seller-card {
        grid-template-columns: 1fr;
    }

    .pc-part-page .pc-seller-image,
    .pc-part-page .pc-seller-image img {
        height: 170px;
    }

    .pc-part-page .pc-navigation {
        width: 100%;
    }

    .pc-part-page .pc-nav-button {
        flex: 1;
    }
}
</style>

<div class="pc-part-page">

    {{-- Navigation --}}
    <div class="pc-topbar">

        <div class="pc-breadcrumb">
            <a href="{{ url('/') }}">داشبورد</a>
            <span>←</span>
            <a href="{{ route('catalog.index') }}">کاتالوگ قطعات</a>
            <span>←</span>
            <strong>{{ $part->name_fa }}</strong>
        </div>

        <div class="pc-navigation">

            <a href="{{ url()->previous() }}" class="pc-nav-button">
                ← بازگشت
            </a>

            <a href="{{ route('catalog.index') }}" class="pc-nav-button primary">
                فهرست قطعات
            </a>

        </div>

    </div>

    {{-- Product Overview --}}
    <section class="pc-product-hero">

        <div class="pc-image-area">

            <div class="pc-main-image">

                @if ($firstSellerProduct && !empty($firstSellerProduct->image_url))

                    <a
                        href="{{ $firstSellerProduct->image_url }}"
                        target="_blank"
                        rel="noopener noreferrer">

                        <img
                            src="{{ $firstSellerProduct->image_url }}"
                            alt="{{ $part->name_fa }}"
                            loading="lazy">
                    </a>

                @elseif (!empty($part->img_src))

                    <a
                        href="{{ $part->img_src }}"
                        target="_blank"
                        rel="noopener noreferrer">

                        <img
                            src="{{ $part->img_src }}"
                            alt="{{ $part->name_fa }}"
                            loading="lazy">
                    </a>

                @else

                    <div class="pc-image-empty">
                        <span>▧</span>
                        تصویر این قطعه ثبت نشده است
                    </div>

                @endif

            </div>

            <div class="pc-image-caption">
                تصویر قطعه از اطلاعات EPC یا فروشندگان ثبت‌شده
            </div>

        </div>

        <div class="pc-product-info">

            <span class="pc-product-label">
                <span>●</span>
                شناسنامه قطعه در PartCar
            </span>

            <h1 class="pc-product-title">
                {{ $part->name_fa ?: 'قطعه بدون نام' }}
            </h1>

            <p class="pc-product-subtitle">
                اطلاعات فنی، خودروهای سازگار و فروشگاه‌های عرضه‌کننده این قطعه
                را در یک صفحه مشاهده کن.
            </p>

            <div class="pc-code-box">

                <div>
                    <small>کد فنی قطعه</small>

                    <strong id="part-technical-code">
                        {{ $part->technical_code ?: 'ثبت نشده' }}
                    </strong>
                </div>

                @if(!empty($part->technical_code))
                    <button
                        type="button"
                        class="pc-code-copy"
                        onclick="copyPartCode()">
                        کپی کد
                    </button>
                @endif

            </div>

            <div class="pc-quick-info">

                <div class="pc-quick-item">
                    <small>کد لجستیکی</small>
                    <strong>
                        {{ $part->logistics_code ?: 'ثبت نشده' }}
                    </strong>
                </div>

                <div class="pc-quick-item price">
                    <small>قیمت شرکت</small>
                    <strong>
                        {{ $part->price !== null
                            ? number_format((float) $part->price) . ' تومان'
                            : 'ثبت نشده' }}
                    </strong>
                </div>

                <div class="pc-quick-item">
                    <small>گروه قطعه</small>
                    <strong>{{ $part->category ?: 'نامشخص' }}</strong>
                </div>

                <div class="pc-quick-item">
                    <small>خودرو</small>
                    <strong>{{ $part->car_model_raw ?: 'ثبت نشده' }}</strong>
                </div>

            </div>

            @if(!empty($part->p_link))
                <a
                    href="{{ $part->p_link }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="pc-source-link">
                    مشاهده منبع اصلی قطعه
                    ↗
                </a>
            @endif

        </div>

    </section>

    {{-- Seller Offers --}}
    <section class="pc-section pc-seller-section" id="seller-offers">

        <div class="pc-section-header">

            <div class="pc-section-heading">
                <div class="pc-section-icon">🛒</div>

                <div>
                    <h2>فروشگاه‌های عرضه‌کننده این قطعه</h2>
                    <p>
                        پیشنهادهای ثبت‌شده توسط فروشندگان تأییدشده PartCar
                    </p>
                </div>
            </div>

            <span class="pc-count-badge">
                {{ number_format($sellerOfferCount) }} فروشگاه
            </span>

        </div>

        @if(isset($sellerOffers) && $sellerOffers->count() > 0)

            <div class="pc-seller-grid">

                @foreach($sellerOffers as $offer)

                    <article class="pc-seller-card">

                        <div class="pc-seller-image">

                            @if(!empty($offer->image_url))

                                <img
                                    src="{{ $offer->image_url }}"
                                    alt="{{ $part->name_fa }}"
                                    loading="lazy">

                            @else

                                <div class="pc-seller-image-empty">
                                    ▧
                                    <br>
                                    تصویر موجود نیست
                                </div>

                            @endif

                        </div>

                        <div class="pc-seller-content">

                            <div class="pc-seller-title-row">

                                <h3 class="pc-seller-title">
                                    {{ $offer->store->store_name ?? 'فروشگاه' }}
                                </h3>

                                <span class="pc-verified">
                                    ✓ تأییدشده
                                </span>

                            </div>

                            <p class="pc-seller-product">
                                {{ $part->name_fa }}
                            </p>

                            <div class="pc-seller-price">
                                <span>قیمت و موجودی</span>
                                <strong>استعلام از فروشگاه</strong>
                            </div>

                            <a
                                href="{{ route('seller.products.visit', $offer->id) }}"
                                class="pc-seller-buy"
                                target="_blank"
                                rel="noopener noreferrer">

                                مشاهده محصول و خرید
                                ←

                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="pc-empty-state">

                <div class="pc-empty-state-icon">🛒</div>

                <strong>هنوز فروشگاهی این قطعه را ثبت نکرده است</strong>

                <p>
                    در حال حاضر پیشنهاد خریدی برای این قطعه وجود ندارد.
                </p>

            </div>

        @endif

    </section>

    {{-- Technical Specifications --}}
    <section class="pc-section">

        <div class="pc-section-header">

            <div class="pc-section-heading">
                <div class="pc-section-icon">⚙</div>

                <div>
                    <h2>مشخصات فنی قطعه</h2>
                    <p>اطلاعات ثبت‌شده در بانک اطلاعات EPC</p>
                </div>
            </div>

        </div>

        <div class="pc-spec-grid">

            @foreach($technicalFields as $field)

                <div class="pc-spec-item {{ in_array($field['label'], ['کد فنی', 'کد لجستیکی']) ? 'code' : '' }}">

                    <small>{{ $field['label'] }}</small>

                    <strong>
                        {{ $field['value'] ?: 'ثبت نشده' }}
                    </strong>

                </div>

            @endforeach

        </div>

    </section>

    {{-- Compatible Vehicles --}}
    <section class="pc-section">

        <div class="pc-section-header">

            <div class="pc-section-heading">
                <div class="pc-section-icon">🚘</div>

                <div>
                    <h2>خودروهای سازگار با این قطعه</h2>
                    <p>مدل‌هایی که این قطعه برای آن‌ها ثبت شده است</p>
                </div>
            </div>

            @if(isset($vehicleModels) && $vehicleModels->count() > 0)
                <span class="pc-count-badge">
                    {{ number_format($vehicleModels->count()) }} خودرو
                </span>
            @endif

        </div>

        @if(isset($vehicleModels) && $vehicleModels->count() > 0)

            <div class="pc-vehicle-list">

                @foreach($vehicleModels as $vehicle)

                    <span class="pc-vehicle-chip">
                        {{ $vehicle->name }}
                    </span>

                @endforeach

            </div>

        @else

            <div class="pc-empty-state">
                <div class="pc-empty-state-icon">🚘</div>
                <strong>اطلاعات سازگاری ثبت نشده است</strong>
                <p>خودروهای سازگار با این قطعه در بانک اطلاعاتی موجود نیستند.</p>
            </div>

        @endif

    </section>

    {{-- Replacement Codes --}}
    <section class="pc-section">

        <div class="pc-section-header">

            <div class="pc-section-heading">
                <div class="pc-section-icon">⇄</div>

                <div>
                    <h2>کدهای جایگزین قطعه</h2>
                    <p>کدهای فنی جایگزین یا معادل ثبت‌شده برای این قطعه</p>
                </div>
            </div>

            @if(isset($replacementCodes) && count($replacementCodes) > 0)
                <span class="pc-count-badge">
                    {{ number_format(count($replacementCodes)) }} کد
                </span>
            @endif

        </div>

        @if(isset($replacementCodes) && count($replacementCodes) > 0)

            <div class="pc-replacement-list">

                @foreach($replacementCodes as $code)

                    <span class="pc-replacement-chip">
                        {{ $code }}
                    </span>

                @endforeach

            </div>

        @else

            <div class="pc-empty-state">
                <div class="pc-empty-state-icon">⇄</div>
                <strong>کد جایگزینی ثبت نشده است</strong>
                <p>برای این قطعه کد فنی جایگزین در دسترس نیست.</p>
            </div>

        @endif

    </section>

    {{-- Replacement Parts --}}
    <section class="pc-section">

        <div class="pc-section-header">

            <div class="pc-section-heading">
                <div class="pc-section-icon">▤</div>

                <div>
                    <h2>قطعات مرتبط با کدهای جایگزین</h2>
                    <p>قطعات دیگری که با کدهای جایگزین مرتبط هستند</p>
                </div>
            </div>

            @if(isset($replacementParts) && $replacementParts->count() > 0)
                <span class="pc-count-badge">
                    {{ number_format($replacementParts->count()) }} قطعه
                </span>
            @endif

        </div>

        @if(isset($replacementParts) && $replacementParts->count() > 0)

            <div class="pc-table-wrap">

                <table class="pc-related-table">

                    <thead>
                        <tr>
                            <th>نام قطعه</th>
                            <th>کد فنی</th>
                            <th>کد لجستیکی</th>
                            <th>مشاهده</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($replacementParts as $replacement)

                            <tr>

                                <td>
                                    <span class="pc-related-name">
                                        {{ $replacement->name_fa ?: 'بدون نام' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="pc-related-code">
                                        {{ $replacement->technical_code ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="pc-related-code">
                                        {{ $replacement->logistics_code ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="{{ route('catalog.show', $replacement->id) }}"
                                        class="pc-related-link">
                                        مشاهده قطعه ↗
                                    </a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="pc-empty-state">
                <div class="pc-empty-state-icon">▤</div>
                <strong>قطعه مرتبطی پیدا نشد</strong>
                <p>در حال حاضر قطعه دیگری با کدهای جایگزین این محصول ثبت نشده است.</p>
            </div>

        @endif

    </section>

</div>

<script>
function copyPartCode() {
    const codeElement = document.getElementById('part-technical-code');

    if (!codeElement) return;

    const code = codeElement.innerText.trim();

    if (!code || code === 'ثبت نشده') return;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(function () {
            showCopyFeedback();
        });
    } else {
        const input = document.createElement('textarea');
        input.value = code;
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();

        try {
            document.execCommand('copy');
            showCopyFeedback();
        } catch (error) {
            alert('کپی کد انجام نشد.');
        }

        input.remove();
    }
}

function showCopyFeedback() {
    const button = document.querySelector('.pc-code-copy');

    if (!button) return;

    const oldText = button.innerText;
    button.innerText = '✓ کپی شد';

    setTimeout(function () {
        button.innerText = oldText;
    }, 1500);
}
</script>

@endsection
