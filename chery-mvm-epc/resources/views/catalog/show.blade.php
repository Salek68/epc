
@extends('layouts.app')

@section('title', ($part->name_fa ?? 'جزئیات قطعه') . ' | کاتالوگ قطعات')

@section('content')

<div class="epc-page">

    {{-- دکمه بازگشت --}}
    <div class="page-actions">
        <a href="{{ url()->previous() }}" class="epc-btn epc-btn-light">
            ← بازگشت
        </a>

        <a href="{{ route('catalog.index') }}" class="epc-btn epc-btn-primary">
            صفحه اصلی کاتالوگ
        </a>
    </div>

    {{-- عنوان قطعه --}}
    <section class="epc-hero">
        <div>
            <span class="epc-label">اطلاعات قطعه</span>

            <h1>{{ $part->name_fa }}</h1>

            <p>
                کد فنی:
                <strong>{{ $part->technical_code ?: 'ثبت نشده' }}</strong>
            </p>
        </div>
    </section>

    {{-- تصویر و اطلاعات اولیه قطعه --}}
    <section class="epc-card">
        <div class="product-showcase">

            <div class="product-image">
                @if ($part->activeSellerProducts->isNotEmpty() &&
     !empty($part->activeSellerProducts->first()->image_url))

    {{-- تصویر ثبت‌شده توسط فروشنده --}}
    <a href="{{ $part->activeSellerProducts->first()->image_url }}"
       target="_blank"
       rel="noopener noreferrer">

        <img
            src="{{ $part->activeSellerProducts->first()->image_url }}"
            alt="{{ $part->name_fa }}"
            class="epc-product-image"
            loading="lazy">
    </a>

@elseif (!empty($part->img_src))

    {{-- تصویر اصلی EPC --}}
    <a href="{{ $part->img_src }}"
       target="_blank"
       rel="noopener noreferrer">

        <img
            src="{{ $part->img_src }}"
            alt="{{ $part->name_fa }}"
            class="epc-product-image"
            loading="lazy">
    </a>

@else

    <div class="epc-no-image">
        📷
    </div>

@endif
            </div>

            <div class="product-summary">
                <h2>{{ $part->name_fa }}</h2>

                <div class="summary-item">
                    <span>کد فنی</span>
                    <strong>{{ $part->technical_code ?: '-' }}</strong>
                </div>

                <div class="summary-item">
                    <span>کد لجستیکی</span>
                    <strong>{{ $part->logistics_code ?: '-' }}</strong>
                </div>

                <div class="summary-item">
                    <span>قیمت شرکت</span>
                    <strong>
                        {{ $part->price ? number_format($part->price) . ' تومان' : 'ثبت نشده' }}
                    </strong>
                </div>

                @if(!empty($part->p_link))
                    <a href="{{ $part->p_link }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="epc-btn epc-btn-primary">
                        مشاهده منبع قطعه
                    </a>
                @endif
            </div>

        </div>
    </section>


    {{-- پیشنهادهای فروشندگان --}}
    <section class="epc-card seller-offers-section">

        <div class="section-heading">
            <div>
                <span class="epc-label">خرید قطعه</span>
                <h2>فروشگاه‌های عرضه‌کننده این قطعه</h2>
                <p>
                    محصولات ثبت‌شده توسط فروشندگان تأییدشده
                </p>
            </div>

            <span class="seller-count">
                {{ isset($sellerOffers) ? $sellerOffers->count() : 0 }}
                فروشگاه
            </span>
        </div>

        @if(isset($sellerOffers) && $sellerOffers->count() > 0)

            <div class="seller-offers-grid">

                @foreach($sellerOffers as $offer)

                    <article class="seller-offer-card">

                        <div class="seller-offer-image">

                            @if(!empty($offer->image_url))
                                <img
                                    src="{{ $offer->image_url }}"
                                    alt="{{ $part->name_fa }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="seller-image-placeholder">
                                    <span>تصویر موجود نیست</span>
                                </div>
                            @endif

                        </div>

                        <div class="seller-offer-info">

                            <div class="seller-title-row">
                                <h3>
                                    {{ $offer->store->store_name ?? 'فروشگاه' }}
                                </h3>

                                <span class="seller-verified">
                                    <span>✓</span>
                                    تأییدشده
                                </span>
                            </div>

                            <p class="seller-product-name">
                                {{ $part->name_fa }}
                            </p>

                            <div class="seller-price-info">
                                <span>قیمت و موجودی</span>
                                <strong>استعلام از فروشگاه</strong>
                            </div>

                            <a
                                href="{{ route('seller.products.visit', $offer->id) }}"
                                class="seller-buy-button"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                مشاهده و خرید
                                <span>←</span>
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="seller-offers-empty">
                <div class="empty-icon">🛒</div>
                <h3>هنوز فروشگاهی این قطعه را ثبت نکرده است</h3>
                <p>
                    در حال حاضر پیشنهاد خریدی برای این قطعه وجود ندارد.
                </p>
            </div>

        @endif

    </section>


    {{-- مشخصات فنی --}}
    <section class="epc-card">

        <div class="section-heading">
            <div>
                <span class="epc-label">Technical Information</span>
                <h2>مشخصات فنی قطعه</h2>
            </div>
        </div>

        <div class="technical-grid">

            <div class="technical-item">
                <span>نام قطعه</span>
                <strong>{{ $part->name_fa ?: '-' }}</strong>
            </div>

            <div class="technical-item">
                <span>کد فنی</span>
                <strong>{{ $part->technical_code ?: '-' }}</strong>
            </div>

            <div class="technical-item">
                <span>کد لجستیکی</span>
                <strong>{{ $part->logistics_code ?: '-' }}</strong>
            </div>

            <div class="technical-item">
                <span>دسته‌بندی</span>
                <strong>{{ $part->category ?: '-' }}</strong>
            </div>

            <div class="technical-item">
                <span>زیردسته</span>
                <strong>{{ $part->subcategory ?: '-' }}</strong>
            </div>

            <div class="technical-item">
                <span>قیمت شرکت</span>
                <strong>
                    {{ $part->price ? number_format($part->price) . ' تومان' : '-' }}
                </strong>
            </div>

            <div class="technical-item">
                <span>قیمت با مالیات</span>
                <strong>
                    {{ $part->price_tax ? number_format($part->price_tax) . ' تومان' : '-' }}
                </strong>
            </div>

            <div class="technical-item">
                <span>مدل خودرو</span>
                <strong>{{ $part->car_model_raw ?: '-' }}</strong>
            </div>

        </div>

    </section>


    {{-- خودروهای سازگار --}}
    <section class="epc-card">

        <div class="section-heading">
            <div>
                <span class="epc-label">Compatibility</span>
                <h2>خودروهای سازگار با این قطعه</h2>
            </div>
        </div>

        @if(isset($vehicleModels) && $vehicleModels->count() > 0)

            <div class="vehicle-models">

                @foreach($vehicleModels as $vehicle)
                    <span class="vehicle-chip">
                        {{ $vehicle->name }}
                    </span>
                @endforeach

            </div>

        @else

            <p class="empty-message">
                اطلاعات خودروهای سازگار ثبت نشده است.
            </p>

        @endif

    </section>


    {{-- کدهای جایگزین --}}
    <section class="epc-card">

        <div class="section-heading">
            <div>
                <span class="epc-label">Replacement Codes</span>
                <h2>کدهای جایگزین قطعه</h2>
            </div>
        </div>

        @if(isset($replacementCodes) && count($replacementCodes) > 0)

            <div class="replacement-codes">

                @foreach($replacementCodes as $code)
                    <span class="code-chip">
                        {{ $code }}
                    </span>
                @endforeach

            </div>

        @else

            <p class="empty-message">
                کد جایگزینی برای این قطعه ثبت نشده است.
            </p>

        @endif

    </section>


    {{-- قطعات دارای کد جایگزین --}}
    <section class="epc-card">

        <div class="section-heading">
            <div>
                <span class="epc-label">Related Parts</span>
                <h2>قطعات مرتبط با کدهای جایگزین</h2>
            </div>
        </div>

        @if(isset($replacementParts) && $replacementParts->count() > 0)

            <div class="table-wrapper">

                <table class="epc-table">

                    <thead>
                        <tr>
                            <th>نام قطعه</th>
                            <th>کد فنی</th>
                            <th>کد لجستیکی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($replacementParts as $replacement)

                            <tr>
                                <td>{{ $replacement->name_fa }}</td>
                                <td>{{ $replacement->technical_code ?: '-' }}</td>
                                <td>{{ $replacement->logistics_code ?: '-' }}</td>
                                <td>
                                    <a
                                        href="{{ route('catalog.show', $replacement->id) }}"
                                        class="table-link"
                                    >
                                        مشاهده قطعه
                                    </a>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p class="empty-message">
                قطعه مرتبطی پیدا نشد.
            </p>

        @endif

    </section>

</div>


<style>

.epc-page {
    direction: rtl;
    color: #eaf2ff;
    max-width: 1250px;
    margin: 0 auto;
    padding: 25px 16px 60px;
}

.epc-page * {
    box-sizing: border-box;
}

.page-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.epc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: .2s;
}

.epc-btn-primary {
    background: #08b6d5;
    color: #04131d;
}

.epc-btn-primary:hover {
    background: #35d5ed;
    color: #04131d;
}

.epc-btn-light {
    background: #172c43;
    color: #dcecff;
    border: 1px solid #29445f;
}

.epc-hero {
    background: linear-gradient(120deg, #102b46, #071827);
    border: 1px solid #1d405d;
    border-radius: 18px;
    padding: 30px;
    margin-bottom: 20px;
}

.epc-hero h1 {
    color: #fff;
    font-size: 27px;
    margin: 12px 0;
}

.epc-hero p {
    color: #a9c2d8;
    margin: 0;
}

.epc-label {
    color: #12c6e2;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
}

.epc-card {
    background: #0b1d30;
    border: 1px solid #1b3852;
    border-radius: 17px;
    padding: 24px;
    margin-bottom: 20px;
    overflow: hidden;
}

.product-showcase {
    display: grid;
    grid-template-columns: minmax(220px, 350px) 1fr;
    gap: 30px;
    align-items: center;
}

.product-image {
    background: #fff;
    border-radius: 14px;
    min-height: 250px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px;
}

.product-image img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
}

.image-placeholder,
.seller-image-placeholder {
    color: #71869a;
    text-align: center;
    padding: 30px 10px;
}

.product-summary h2 {
    color: #fff;
    font-size: 22px;
    margin: 0 0 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #1b354c;
}

.summary-item span,
.technical-item span {
    color: #91a9bd;
    font-size: 13px;
}

.summary-item strong,
.technical-item strong {
    color: #f0f6ff;
    font-size: 13px;
    overflow-wrap: anywhere;
}

.product-summary .epc-btn {
    margin-top: 20px;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.section-heading h2 {
    color: #fff;
    font-size: 19px;
    margin: 7px 0;
}

.section-heading p {
    color: #91a9bd;
    font-size: 12px;
    margin: 0;
}

.technical-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.technical-item {
    background: #10263c;
    border: 1px solid #1c3a55;
    border-radius: 11px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.vehicle-models,
.replacement-codes {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.vehicle-chip,
.code-chip {
    background: #102c43;
    border: 1px solid #1c536e;
    color: #61d9ed;
    border-radius: 9px;
    padding: 9px 13px;
    font-size: 13px;
    font-weight: 700;
}

.code-chip {
    direction: ltr;
    color: #d6eaff;
}

.empty-message {
    color: #91a9bd;
    font-size: 13px;
    margin: 0;
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.epc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.epc-table th,
.epc-table td {
    text-align: right;
    padding: 14px;
    border-bottom: 1px solid #1b354c;
    font-size: 13px;
}

.epc-table th {
    color: #65d7eb;
    background: #10263c;
}

.epc-table td {
    color: #dce9f7;
}

.table-link {
    color: #19c6e1;
    text-decoration: none;
    font-weight: 700;
}


/* فروشگاه‌های عرضه‌کننده */

.seller-offers-section {
    border-color: #17617a;
    background: linear-gradient(145deg, #0b2033, #0a1b2b);
}

.seller-count {
    background: #12364b;
    color: #42d5ec;
    border: 1px solid #1c6178;
    border-radius: 30px;
    padding: 8px 13px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.seller-offers-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.seller-offer-card {
    background: #10263a;
    border: 1px solid #24465f;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: .2s;
}

.seller-offer-card:hover {
    border-color: #14bdd9;
    transform: translateY(-3px);
}

.seller-offer-image {
    height: 210px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.seller-offer-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 12px;
}

.seller-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #e8eef3;
}

.seller-offer-info {
    padding: 17px;
}

.seller-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.seller-title-row h3 {
    color: #fff;
    font-size: 16px;
    margin: 0;
}

.seller-verified {
    color: #47d9b0;
    font-size: 11px;
    background: #123a39;
    border: 1px solid #24665b;
    border-radius: 20px;
    padding: 5px 9px;
}

.seller-product-name {
    color: #a9bfd2;
    font-size: 12px;
    margin: 14px 0;
}

.seller-price-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    background: #0b1c2c;
    border-radius: 9px;
    padding: 12px;
    margin-bottom: 14px;
}

.seller-price-info span {
    color: #8da8bd;
    font-size: 12px;
}

.seller-price-info strong {
    color: #f4d47c;
    font-size: 12px;
}

.seller-buy-button {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    width: 100%;
    background: #08b6d5;
    color: #041722;
    text-decoration: none;
    font-weight: 800;
    font-size: 13px;
    border-radius: 9px;
    padding: 12px;
    transition: .2s;
}

.seller-buy-button:hover {
    background: #39d9ed;
    color: #041722;
}

.seller-offers-empty {
    text-align: center;
    padding: 35px 15px;
    background: #10263a;
    border: 1px dashed #31536b;
    border-radius: 12px;
}

.empty-icon {
    font-size: 35px;
    margin-bottom: 12px;
}

.seller-offers-empty h3 {
    color: #eaf5ff;
    font-size: 16px;
    margin: 0 0 10px;
}

.seller-offers-empty p {
    color: #91a9bd;
    font-size: 13px;
    margin: 0;
}


/* نمایش در موبایل */

@media (max-width: 768px) {

    .epc-page {
        padding: 15px 10px 40px;
    }

    .epc-card {
        padding: 16px;
    }

    .epc-hero {
        padding: 22px;
    }

    .epc-hero h1 {
        font-size: 21px;
    }

    .product-showcase {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .product-image {
        min-height: 200px;
    }

    .technical-grid {
        grid-template-columns: 1fr;
    }

    .seller-offers-grid {
        grid-template-columns: 1fr;
    }

    .section-heading {
        align-items: flex-start;
    }

}

</style>

@endsection
