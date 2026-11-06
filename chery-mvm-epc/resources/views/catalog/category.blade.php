
@extends('layouts.app')

@section('title', 'دسته‌بندی قطعات | PartCar')

@section('content')

@php
    $vehicleInput = request()->input('vehicle', []);

    if (is_string($vehicleInput)) {
        $vehicleInput = [$vehicleInput];
    }

    $selectedVehicles = array_values(array_filter(
        (array) $vehicleInput,
        fn ($value) => is_string($value) && $value !== ''
    ));

    $loadedParts = $parts->getCollection();

    $categoryCount = $loadedParts
        ->filter(fn ($part) => !empty($part->category))
        ->pluck('category')
        ->unique()
        ->count();

    $currentCategory = $category ?: 'تمام قطعات';

    $formatPrice = function ($price) {
        return $price !== null
            ? number_format((float) $price)
            : 'ثبت نشده';
    };
@endphp

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<style>
.pc-category-page {
    --pc-navy: #0B1730;
    --pc-blue: #087BFA;
    --pc-blue-dark: #0665d1;
    --pc-bg: #F4F7FB;
    --pc-border: #E5EAF1;
    --pc-muted: #718096;
    --pc-text: #253247;
    direction: rtl;
    color: var(--pc-text);
    padding-bottom: 30px;
    font-family: inherit;
}

.pc-category-page *,
.pc-category-page *::before,
.pc-category-page *::after {
    box-sizing: border-box;
}

.pc-category-page a {
    text-decoration: none;
}

.pc-category-page .pc-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
}

.pc-category-page .pc-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 9px;
    color: #8995a7;
    font-size: 11px;
}

.pc-category-page .pc-breadcrumb a {
    color: var(--pc-blue);
}

.pc-category-page .pc-page-title {
    margin: 0;
    color: var(--pc-navy);
    font-size: clamp(21px, 2.5vw, 27px);
    font-weight: 900;
    line-height: 1.7;
}

.pc-category-page .pc-page-description {
    margin: 5px 0 0;
    color: var(--pc-muted);
    font-size: 12px;
    line-height: 2;
}

.pc-category-page .pc-header-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border: 1px solid #dbeafe;
    border-radius: 11px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.pc-category-page .pc-header-tag span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #16a36a;
}

/* Summary */

.pc-category-page .pc-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.pc-category-page .pc-summary-item {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 15px 17px;
    border: 1px solid var(--pc-border);
    border-radius: 14px;
    background: #fff;
}

.pc-category-page .pc-summary-icon {
    display: grid;
    place-items: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #edf5ff;
    color: var(--pc-blue);
    font-size: 19px;
}

.pc-category-page .pc-summary-item small {
    display: block;
    margin-bottom: 4px;
    color: var(--pc-muted);
    font-size: 10px;
}

.pc-category-page .pc-summary-item strong {
    display: block;
    color: var(--pc-navy);
    font-size: 17px;
    font-weight: 900;
    overflow-wrap: anywhere;
}

/* Filter panel */

.pc-category-page .pc-filter-panel {
    margin-bottom: 20px;
    padding: 21px;
    border: 1px solid var(--pc-border);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(15, 35, 65, .035);
}

.pc-category-page .pc-section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}

.pc-category-page .pc-section-heading h2 {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0;
    color: var(--pc-navy);
    font-size: 15px;
    font-weight: 900;
}

.pc-category-page .pc-section-heading h2::before {
    content: "";
    width: 4px;
    height: 19px;
    border-radius: 5px;
    background: var(--pc-blue);
}

.pc-category-page .pc-section-heading p {
    margin: 5px 0 0;
    color: var(--pc-muted);
    font-size: 11px;
}

.pc-category-page .pc-filter-label {
    display: block;
    margin-bottom: 8px;
    color: #344256;
    font-size: 11px;
    font-weight: 800;
}

.pc-category-page .pc-filter-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr 1fr 1.35fr;
    gap: 13px;
}

.pc-category-page .pc-field {
    min-width: 0;
}

.pc-category-page .pc-field input,
.pc-category-page .pc-field select {
    width: 100%;
    min-width: 0;
    height: 45px;
    padding: 10px 12px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    outline: none;
    background: #fff;
    color: #26364b;
    font-family: inherit;
    font-size: 12px;
    transition: .2s ease;
}

.pc-category-page .pc-field input::placeholder {
    color: #a1adbc;
}

.pc-category-page .pc-field input:focus,
.pc-category-page .pc-field select:focus {
    border-color: var(--pc-blue);
    box-shadow: 0 0 0 3px rgba(8,123,250,.09);
}

.pc-category-page .pc-filter-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 17px;
}

.pc-category-page .pc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 9px 17px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.pc-category-page .pc-btn-primary {
    background: var(--pc-blue);
    color: #fff;
}

.pc-category-page .pc-btn-primary:hover {
    background: var(--pc-blue-dark);
    color: #fff;
    transform: translateY(-1px);
}

.pc-category-page .pc-btn-light {
    border-color: var(--pc-border);
    background: #fff;
    color: #59677b;
}

.pc-category-page .pc-btn-light:hover {
    border-color: #b8c7d9;
    background: #f8fafc;
    color: var(--pc-navy);
}

.pc-category-page .pc-filter-hint {
    margin: 11px 0 0;
    color: #8b98a9;
    font-size: 10px;
    line-height: 1.9;
}

/* Tom Select */

.pc-category-page #vehicle + .ts-wrapper {
    width: 100%;
}

.pc-category-page .ts-wrapper.multi .ts-control {
    min-height: 45px;
    padding: 5px 9px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    background: #fff;
    box-shadow: none;
    cursor: text;
}

.pc-category-page .ts-wrapper.focus .ts-control {
    border-color: var(--pc-blue);
    box-shadow: 0 0 0 3px rgba(8,123,250,.09);
}

.pc-category-page .ts-wrapper.multi .ts-control > div {
    margin: 2px 3px;
    padding: 4px 8px;
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 10px;
}

.pc-category-page .ts-wrapper.plugin-remove_button .item .remove {
    border-left: 1px solid #bfdbfe;
    margin-left: 6px;
}

.pc-category-page .ts-dropdown {
    margin-top: 5px;
    overflow: hidden;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(15,35,65,.1);
}

.pc-category-page .ts-dropdown .option {
    padding: 10px 13px;
    font-size: 11px;
}

.pc-category-page .ts-dropdown .active {
    background: #eff6ff;
    color: #1765c1;
}

.pc-category-page .ts-dropdown .no-results {
    padding: 13px;
    color: var(--pc-muted);
    text-align: center;
    font-size: 11px;
}

/* Results */

.pc-category-page .pc-results-panel {
    overflow: hidden;
    border: 1px solid var(--pc-border);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(15,35,65,.035);
}

.pc-category-page .pc-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 19px 21px;
    border-bottom: 1px solid var(--pc-border);
}

.pc-category-page .pc-results-title {
    margin: 0;
    color: var(--pc-navy);
    font-size: 16px;
    font-weight: 900;
}

.pc-category-page .pc-results-subtitle {
    margin: 5px 0 0;
    color: var(--pc-muted);
    font-size: 11px;
    line-height: 1.9;
}

.pc-category-page .pc-result-count {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 12px;
    border: 1px solid #dbeafe;
    border-radius: 9px;
    background: #eff6ff;
    color: #1765c1;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.pc-category-page .pc-table-container {
    width: 100%;
    overflow-x: auto;
}

.pc-category-page .pc-parts-table {
    width: 100%;
    min-width: 940px;
    border-collapse: collapse;
}

.pc-category-page .pc-parts-table th {
    padding: 13px 12px;
    background: #f8fafc;
    color: #738196;
    font-size: 10px;
    font-weight: 800;
    text-align: right;
    white-space: nowrap;
    border-bottom: 1px solid var(--pc-border);
}

.pc-category-page .pc-parts-table td {
    padding: 13px 12px;
    border-bottom: 1px solid #edf0f5;
    color: #465469;
    font-size: 11px;
    vertical-align: middle;
}

.pc-category-page .pc-parts-table tbody tr {
    transition: background .15s ease;
}

.pc-category-page .pc-parts-table tbody tr:hover {
    background: #f8fbff;
}

.pc-category-page .pc-parts-table tbody tr:last-child td {
    border-bottom: 0;
}

.pc-category-page .pc-product-image {
    display: block;
    width: 57px;
    height: 57px;
    padding: 4px;
    border: 1px solid #e8edf3;
    border-radius: 9px;
    background: #fff;
    object-fit: contain;
    transition: .2s ease;
}

.pc-category-page .pc-product-image:hover {
    border-color: #93c5fd;
    transform: scale(1.06);
}

.pc-category-page .pc-no-image {
    display: grid;
    place-items: center;
    width: 57px;
    height: 57px;
    border: 1px dashed #dce3ec;
    border-radius: 9px;
    background: #f8fafc;
    color: #a7b2c0;
    font-size: 19px;
}

.pc-category-page .pc-code {
    display: inline-block;
    direction: ltr;
    unicode-bidi: plaintext;
    color: #1765c1;
    font-family: Consolas, monospace;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.pc-category-page .pc-part-name {
    min-width: 160px;
    color: var(--pc-navy);
    font-size: 12px;
    font-weight: 900;
    line-height: 1.9;
}

.pc-category-page .pc-subcategory {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #5c6b7e;
    font-size: 10px;
    white-space: nowrap;
}

.pc-category-page .pc-car-name {
    max-width: 170px;
    color: #536176;
    line-height: 1.8;
}

.pc-category-page .pc-price {
    color: #168052;
    font-weight: 900;
    white-space: nowrap;
}

.pc-category-page .pc-price-unit {
    display: block;
    margin-top: 3px;
    color: #9aa5b4;
    font-size: 9px;
    font-weight: 500;
}

.pc-category-page .pc-actions-cell {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pc-category-page .pc-detail-link,
.pc-category-page .pc-buy-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-height: 33px;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 900;
    white-space: nowrap;
    transition: .2s ease;
}

.pc-category-page .pc-detail-link {
    background: #eff6ff;
    color: #1765c1;
}

.pc-category-page .pc-detail-link:hover {
    background: #dbeafe;
    color: #0756b5;
}

.pc-category-page .pc-buy-link {
    background: #eaf8f0;
    color: #168052;
}

.pc-category-page .pc-buy-link:hover {
    background: #168052;
    color: #fff;
}

.pc-category-page .pc-seller-buy-link {
    background: #e7f7f6;
    color: #087f83;
}

.pc-category-page .pc-seller-buy-link:hover {
    background: #087f83;
    color: #fff;
}

.pc-category-page .pc-seller-count {
    display: inline-grid;
    place-items: center;
    min-width: 19px;
    height: 19px;
    padding: 0 5px;
    border-radius: 20px;
    background: rgba(8,127,131,.12);
    font-size: 9px;
}

.pc-category-page .pc-seller-buy-link:hover .pc-seller-count {
    background: rgba(255,255,255,.2);
}

.pc-category-page .pc-no-buy {
    color: #a1acba;
    font-size: 10px;
    white-space: nowrap;
}

.pc-category-page .pc-empty-state {
    padding: 55px 20px !important;
    text-align: center;
}

.pc-category-page .pc-empty-icon {
    display: grid;
    place-items: center;
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #8b98a9;
    font-size: 25px;
}

.pc-category-page .pc-empty-state strong {
    display: block;
    margin-bottom: 7px;
    color: var(--pc-navy);
    font-size: 14px;
}

.pc-category-page .pc-empty-state p {
    margin: 0;
    color: var(--pc-muted);
    font-size: 11px;
    line-height: 2;
}

/* Pagination */

.pc-category-page .pc-pagination {
    padding: 16px 20px;
    border-top: 1px solid var(--pc-border);
    overflow-x: auto;
}

.pc-category-page .pc-pagination nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.pc-category-page .pc-pagination svg {
    width: 17px;
    height: 17px;
}

.pc-category-page .pc-pagination a,
.pc-category-page .pc-pagination span {
    color: #536176;
}

.pc-category-page .pc-pagination a:hover {
    color: var(--pc-blue);
}

/* Responsive */

@media(max-width: 1050px) {
    .pc-category-page .pc-filter-grid {
        grid-template-columns: repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 700px) {
    .pc-category-page .pc-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .pc-category-page .pc-summary {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .pc-category-page .pc-summary-item {
        padding: 11px 13px;
    }

    .pc-category-page .pc-summary-icon {
        width: 37px;
        height: 37px;
        flex-basis: 37px;
    }

    .pc-category-page .pc-summary-item strong {
        font-size: 15px;
    }

    .pc-category-page .pc-filter-panel {
        padding: 15px;
    }

    .pc-category-page .pc-filter-grid {
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .pc-category-page .pc-filter-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .pc-category-page .pc-btn {
        padding: 9px 10px;
    }

    .pc-category-page .pc-results-header {
        padding: 15px;
    }

    .pc-category-page .pc-pagination {
        padding: 13px;
    }
}

@media(max-width: 390px) {
    .pc-category-page .pc-filter-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="pc-category-page">

    {{-- Page Header --}}
    <header class="pc-page-header">
        <div>
            <div class="pc-breadcrumb">
                <a href="{{ url('/') }}">داشبورد</a>
                <span>←</span>
                <span>کاتالوگ قطعات</span>
                <span>←</span>
                <strong>{{ $currentCategory }}</strong>
            </div>

            <h1 class="pc-page-title">جست‌وجو در بانک قطعات</h1>

            <p class="pc-page-description">
                قطعه موردنظر را با انتخاب خودرو، گروه قطعه، زیرگروه یا کد فنی پیدا کن.
            </p>
        </div>

        <div class="pc-header-tag">
            <span></span>
            بانک اطلاعات قطعات فعال
        </div>
    </header>

    {{-- Summary --}}
    <section class="pc-summary">

        <div class="pc-summary-item">
            <div class="pc-summary-icon">▤</div>
            <div>
                <small>تعداد کل نتایج</small>
                <strong>{{ number_format($parts->total()) }}</strong>
            </div>
        </div>

        <div class="pc-summary-item">
            <div class="pc-summary-icon">▦</div>
            <div>
                <small>گروه‌های قطعه در نتایج فعلی</small>
                <strong>{{ number_format($categoryCount) }}</strong>
            </div>
        </div>

        <div class="pc-summary-item">
            <div class="pc-summary-icon">🚘</div>
            <div>
                <small>خودروهای انتخاب‌شده</small>
                <strong>
                    {{ count($selectedVehicles)
                        ? number_format(count($selectedVehicles)) . ' خودرو'
                        : 'همه خودروها' }}
                </strong>
            </div>
        </div>

    </section>

    {{-- Search & Filters --}}
    <section class="pc-filter-panel">

        <div class="pc-section-heading">
            <div>
                <h2>فیلتر و جست‌وجوی قطعات</h2>
                <p>برای رسیدن به نتیجه دقیق‌تر، فیلترهای موردنظر را انتخاب کن.</p>
            </div>
        </div>

        <form
            action="{{ route('catalog.category') }}"
            method="GET"
            id="catalog-filter-form">

            <div class="pc-filter-grid">

                <div class="pc-field">
                    <label class="pc-filter-label" for="vehicle">
                        خودرو
                    </label>

                    <select
                        name="vehicle[]"
                        id="vehicle"
                        multiple
                        placeholder="نام خودرو را جست‌وجو کن...">

                        @foreach ($vehicles as $vehicleName)
                            <option
                                value="{{ $vehicleName }}"
                                @selected(in_array(
                                    $vehicleName,
                                    $selectedVehicles,
                                    true
                                ))>
                                {{ $vehicleName }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="pc-field">
                    <label class="pc-filter-label" for="category">
                        گروه اصلی قطعه
                    </label>

                    <select name="category" id="category">
                        <option value="">تمام گروه‌ها</option>

                        @foreach ($categories as $categoryName)
                            <option
                                value="{{ $categoryName }}"
                                @selected(($category ?? '') === $categoryName)>
                                {{ $categoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pc-field">
                    <label class="pc-filter-label" for="subcategory">
                        زیرگروه قطعه
                    </label>

                    <select name="subcategory" id="subcategory">
                        <option value="">تمام زیرگروه‌ها</option>

                        @foreach ($subcategories as $item)
                            <option
                                value="{{ $item }}"
                                @selected(($subcategory ?? '') === $item)>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pc-field">
                    <label class="pc-filter-label" for="q">
                        نام قطعه یا کد فنی
                    </label>

                    <input
                        type="search"
                        name="q"
                        id="q"
                        value="{{ $q ?? '' }}"
                        placeholder="مثلاً سنسور میل سوپاپ"
                        autocomplete="off">
                </div>

            </div>

            <div class="pc-filter-actions">
                <button type="submit" class="pc-btn pc-btn-primary">
                    <span>⌕</span>
                    جست‌وجوی قطعات
                </button>

                <a
                    href="{{ route('catalog.category') }}"
                    class="pc-btn pc-btn-light">
                    ↺ پاک‌کردن فیلترها
                </a>
            </div>

            <p class="pc-filter-hint">
                امکان انتخاب هم‌زمان چند خودرو وجود دارد. برای حذف یک خودرو،
                روی علامت × کنار نام آن کلیک کن.
            </p>

        </form>

    </section>

    {{-- Parts Results --}}
    <section class="pc-results-panel">

        <div class="pc-results-header">

            <div>
                <h2 class="pc-results-title">
                    {{ $category ?: 'فهرست تمام قطعات' }}

                    @if (!empty($subcategory))
                        / {{ $subcategory }}
                    @endif
                </h2>

                <p class="pc-results-subtitle">
                    @if (count($selectedVehicles))
                        نتایج مربوط به:
                        {{ implode('، ', $selectedVehicles) }}
                    @else
                        نمایش قطعات مطابق فیلترهای انتخاب‌شده
                    @endif
                </p>
            </div>

            <div class="pc-result-count">
                <span>▤</span>
                {{ number_format($parts->total()) }} قطعه
            </div>

        </div>

        <div class="pc-table-container">

            <table class="pc-parts-table">

                <thead>
                    <tr>
                        <th>تصویر</th>
                        <th>کد فنی</th>
                        <th>نام قطعه</th>
                        <th>زیرگروه</th>
                        <th>خودرو</th>
                        <th>قیمت شرکت</th>
                        <th>دسترسی</th>
                        <th>خرید</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($parts as $part)

                        @php
                            $firstSellerProduct = $part->activeSellerProducts->first();
                        @endphp

                        <tr>

                            {{-- Product Image --}}
                            <td>
                                @if ($firstSellerProduct && !empty($firstSellerProduct->image_url))

                                    <a
                                        href="{{ $firstSellerProduct->image_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer">

                                        <img
                                            src="{{ $firstSellerProduct->image_url }}"
                                            alt="{{ $part->name_fa }}"
                                            class="pc-product-image"
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
                                            class="pc-product-image"
                                            loading="lazy">
                                    </a>

                                @else

                                    <div class="pc-no-image">▧</div>

                                @endif
                            </td>

                            {{-- Technical Code --}}
                            <td>
                                <span class="pc-code">
                                    {{ $part->technical_code ?: '—' }}
                                </span>
                            </td>

                            {{-- Part Name --}}
                            <td>
                                <div class="pc-part-name">
                                    {{ $part->name_fa ?: 'بدون نام' }}
                                </div>
                            </td>

                            {{-- Subcategory --}}
                            <td>
                                <span class="pc-subcategory">
                                    {{ $part->subcategory ?: 'نامشخص' }}
                                </span>
                            </td>

                            {{-- Vehicle --}}
                            <td>
                                <div class="pc-car-name">
                                    {{ $part->car_model_raw ?: 'ثبت نشده' }}
                                </div>
                            </td>

                            {{-- Company Price --}}
                            <td>
                                <span class="pc-price">
                                    {{ $formatPrice($part->price) }}
                                </span>

                                @if ($part->price !== null)
                                    <span class="pc-price-unit">ریال</span>
                                @endif
                            </td>

                            {{-- Detail --}}
                            <td>
                                <a
                                    class="pc-detail-link"
                                    href="{{ route('catalog.show', $part->id) }}">
                                    جزئیات قطعه
                                    ↗
                                </a>
                            </td>

                            {{-- Buy --}}
                            <td>

                                @if ($part->activeSellerProducts->isNotEmpty())

                                    <a
                                        class="pc-buy-link pc-seller-buy-link"
                                        href="{{ route('catalog.show', $part->id) }}#seller-offers">

                                        🛒 فروشندگان

                                        <span class="pc-seller-count">
                                            {{ $part->activeSellerProducts->count() }}
                                        </span>

                                    </a>

                                @elseif (!empty($part->p_link))

                                    <a
                                        class="pc-buy-link"
                                        href="{{ $part->p_link }}"
                                        target="_blank"
                                        rel="noopener noreferrer">
                                        🛒 خرید محصول
                                    </a>

                                @else

                                    <span class="pc-no-buy">
                                        بدون لینک خرید
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="pc-empty-state">

                                <div class="pc-empty-icon">⌕</div>

                                <strong>قطعه‌ای پیدا نشد</strong>

                                <p>
                                    با فیلترهای فعلی نتیجه‌ای ثبت نشده است.
                                    <br>
                                    خودرو یا گروه قطعه را تغییر بده و دوباره جست‌وجو کن.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="pc-pagination">
            {{ $parts->withQueryString()->links() }}
        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const vehicleSelect = document.querySelector('#vehicle');

    if (vehicleSelect && typeof TomSelect !== 'undefined') {

        new TomSelect(vehicleSelect, {
            plugins: ['remove_button'],
            maxOptions: 1000,
            create: false,
            closeAfterSelect: false,
            placeholder: 'نام خودرو را جست‌وجو کن...',
            searchField: ['text'],
            render: {
                no_results: function () {
                    return '<div class="no-results">خودرویی با این نام پیدا نشد.</div>';
                }
            }
        });

    }

});
</script>

@endsection
