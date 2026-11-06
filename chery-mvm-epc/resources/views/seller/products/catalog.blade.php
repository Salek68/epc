
@extends('layouts.app')

@section('title', 'انتخاب قطعه برای فروش | پنل فروشنده')

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

    $registeredPartIds = $registeredPartIds ?? [];
@endphp

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css">

<div class="pc-seller-catalog" dir="rtl">

    <style>
        .pc-seller-catalog {
            --pc-navy: #0B1730;
            --pc-blue: #087BFA;
            --pc-text: #17243A;
            --pc-muted: #718096;
            --pc-border: #E7ECF3;
            --pc-bg: #F4F7FB;
            --pc-white: #FFFFFF;
            color: var(--pc-text);
            padding: 10px 0 40px;
        }

        .pc-seller-catalog * {
            box-sizing: border-box;
        }

        /* Header */

        .pc-seller-catalog .catalog-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .pc-seller-catalog .header-title h1 {
            margin: 0;
            color: var(--pc-navy);
            font-size: 25px;
            font-weight: 900;
            line-height: 1.7;
        }

        .pc-seller-catalog .header-title p {
            margin: 7px 0 0;
            color: var(--pc-muted);
            font-size: 13px;
            line-height: 2;
            max-width: 650px;
        }

        .pc-seller-catalog .header-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 13px;
            border: 1px solid #DCEAFF;
            border-radius: 30px;
            background: #EEF6FF;
            color: #087BFA;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* Overview */

        .pc-seller-catalog .overview-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 23px;
        }

        .pc-seller-catalog .overview-card {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
            padding: 17px 19px;
            background: #fff;
            border: 1px solid var(--pc-border);
            border-radius: 14px;
        }

        .pc-seller-catalog .overview-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            border-radius: 12px;
            background: #EAF3FF;
            color: var(--pc-blue);
            font-size: 20px;
        }

        .pc-seller-catalog .overview-card:nth-child(2) .overview-icon {
            background: #EAF8F0;
            color: #15945A;
        }

        .pc-seller-catalog .overview-info small {
            display: block;
            color: var(--pc-muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .pc-seller-catalog .overview-info strong {
            display: block;
            color: var(--pc-navy);
            font-size: 21px;
            font-weight: 900;
        }

        /* Main panel */

        .pc-seller-catalog .catalog-panel {
            background: #fff;
            border: 1px solid var(--pc-border);
            border-radius: 16px;
            padding: 21px;
            margin-bottom: 22px;
        }

        .pc-seller-catalog .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .pc-seller-catalog .panel-heading h2 {
            color: var(--pc-navy);
            font-size: 16px;
            font-weight: 850;
            margin: 0;
        }

        .pc-seller-catalog .panel-heading p {
            color: var(--pc-muted);
            font-size: 11px;
            margin: 6px 0 0;
            line-height: 1.8;
        }

        .pc-seller-catalog .panel-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #F1F6FC;
            color: #52647A;
            border: 1px solid #E5ECF4;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 10px;
            font-weight: 800;
        }

        /* Filters */

        .pc-seller-catalog .filter-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .pc-seller-catalog .field {
            min-width: 0;
        }

        .pc-seller-catalog .field label {
            display: block;
            color: #43536A;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .pc-seller-catalog .field input,
        .pc-seller-catalog .field select {
            display: block;
            width: 100%;
            min-width: 0;
            height: 45px;
            padding: 0 12px;
            border: 1px solid var(--pc-border);
            border-radius: 9px;
            background: #FAFBFD;
            color: var(--pc-text);
            font-family: inherit;
            font-size: 12px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .pc-seller-catalog .field input:focus,
        .pc-seller-catalog .field select:focus {
            border-color: var(--pc-blue);
            box-shadow: 0 0 0 3px rgba(8,123,250,.08);
        }

        .pc-seller-catalog .field input::placeholder {
            color: #A1ADBC;
        }

        .pc-seller-catalog .filter-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 19px;
        }

        .pc-seller-catalog .pc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 41px;
            padding: 9px 16px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none !important;
            cursor: pointer;
            transition: .2s ease;
        }

        .pc-seller-catalog .pc-btn-primary {
            color: #fff !important;
            background: var(--pc-blue);
        }

        .pc-seller-catalog .pc-btn-primary:hover {
            background: #0666D1;
        }

        .pc-seller-catalog .pc-btn-light {
            color: #52647A !important;
            background: #fff;
            border-color: var(--pc-border);
        }

        .pc-seller-catalog .pc-btn-light:hover {
            background: #F5F8FC;
            border-color: #C9D8E9;
        }

        /* Selected vehicles */

        .pc-seller-catalog .selected-vehicles {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #F0F2F6;
        }

        .pc-seller-catalog .selected-vehicles-label {
            color: var(--pc-muted);
            font-size: 11px;
            padding: 6px 0;
        }

        .pc-seller-catalog .vehicle-chip {
            display: inline-flex;
            align-items: center;
            background: #EEF6FF;
            border: 1px solid #DCEAFF;
            color: #2466AD;
            border-radius: 7px;
            padding: 5px 9px;
            font-size: 10px;
            font-weight: 750;
        }

        /* Results */

        .pc-seller-catalog .results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .pc-seller-catalog .results-header h2 {
            margin: 0;
            color: var(--pc-navy);
            font-size: 17px;
            font-weight: 900;
        }

        .pc-seller-catalog .results-header p {
            margin: 5px 0 0;
            color: var(--pc-muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .pc-seller-catalog .results-count {
            background: #F1F6FC;
            border: 1px solid #E5ECF4;
            color: #435D7C;
            border-radius: 30px;
            padding: 7px 12px;
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        /* Table */

        .pc-seller-catalog .table-container {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--pc-border);
            border-radius: 12px;
            background: #fff;
        }

        .pc-seller-catalog .parts-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .pc-seller-catalog .parts-table th {
            padding: 14px 13px;
            background: #F7F9FC;
            color: #63748A;
            text-align: right;
            white-space: nowrap;
            font-size: 11px;
            font-weight: 850;
            border-bottom: 1px solid var(--pc-border);
        }

        .pc-seller-catalog .parts-table td {
            padding: 12px 13px;
            color: #52647A;
            font-size: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #EEF1F5;
        }

        .pc-seller-catalog .parts-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .pc-seller-catalog .parts-table tbody tr:hover {
            background: #FAFCFF;
        }

        .pc-seller-catalog .part-image {
            width: 54px;
            height: 54px;
            display: block;
            object-fit: contain;
            border: 1px solid #E9EDF2;
            border-radius: 9px;
            background: #fff;
            padding: 3px;
        }

        .pc-seller-catalog .no-image {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F4F6F9;
            border: 1px solid #E9EDF2;
            border-radius: 9px;
            color: #A0ACBA;
            font-size: 19px;
        }

        .pc-seller-catalog .part-code {
            direction: ltr;
            text-align: right;
            unicode-bidi: plaintext;
            font-family: Consolas, monospace;
            color: #087BFA;
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        .pc-seller-catalog .part-name {
            color: var(--pc-navy);
            font-size: 12px;
            font-weight: 850;
            min-width: 150px;
            line-height: 1.8;
        }

        .pc-seller-catalog .subcategory-chip {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #F1F6FC;
            color: #526A87;
            font-size: 10px;
            white-space: nowrap;
        }

        .pc-seller-catalog .part-price {
            color: #168253;
            font-weight: 850;
            white-space: nowrap;
        }

        .pc-seller-catalog .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 30px;
            padding: 7px 9px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .pc-seller-catalog .status-registered {
            color: #167346;
            background: #EAF8EF;
        }

        .pc-seller-catalog .status-unregistered {
            color: #718096;
            background: #F1F3F6;
        }

        .pc-seller-catalog .manage-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 11px;
            border-radius: 8px;
            background: #EAF8EF;
            color: #167346 !important;
            text-decoration: none !important;
            font-size: 10px;
            font-weight: 850;
            white-space: nowrap;
        }

        .pc-seller-catalog .add-product-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 9px 12px;
            border-radius: 8px;
            background: var(--pc-blue);
            color: #fff !important;
            text-decoration: none !important;
            font-size: 10px;
            font-weight: 850;
            white-space: nowrap;
            transition: background .2s;
        }

        .pc-seller-catalog .add-product-link:hover {
            background: #0666D1;
        }

        .pc-seller-catalog .empty-results {
            padding: 45px 20px !important;
            text-align: center !important;
            color: var(--pc-muted) !important;
            line-height: 2;
        }

        .pc-seller-catalog .empty-results strong {
            display: block;
            color: var(--pc-navy);
            font-size: 15px;
            margin-bottom: 5px;
        }

        /* Pagination */

        .pc-seller-catalog .pagination-area {
            margin-top: 20px;
            overflow-x: auto;
        }

        .pc-seller-catalog .pagination-area nav {
            display: flex;
            justify-content: center;
        }

        .pc-seller-catalog .pagination-area svg {
            width: 18px;
            height: 18px;
        }

        /* Tom Select */

        .pc-seller-catalog .ts-wrapper {
            width: 100%;
        }

        .pc-seller-catalog .ts-wrapper.multi .ts-control {
            min-height: 45px;
            padding: 7px 10px;
            border: 1px solid var(--pc-border);
            border-radius: 9px;
            background: #FAFBFD;
            color: var(--pc-text);
            box-shadow: none;
            font-family: inherit;
            font-size: 12px;
        }

        .pc-seller-catalog .ts-wrapper.multi .ts-control > div {
            background: #EAF3FF;
            color: #1764B0;
            border-radius: 6px;
            padding: 4px 7px;
            margin: 2px 3px;
            font-size: 10px;
        }

        .pc-seller-catalog .ts-wrapper .ts-control input {
            color: var(--pc-text);
            font-family: inherit;
            font-size: 12px;
        }

        .pc-seller-catalog .ts-dropdown {
            border: 1px solid var(--pc-border);
            border-radius: 10px;
            background: #fff;
            color: var(--pc-text);
            box-shadow: 0 10px 25px rgba(15,45,85,.1);
            overflow: hidden;
        }

        .pc-seller-catalog .ts-dropdown .option {
            padding: 9px 12px;
            color: var(--pc-text);
            font-size: 12px;
        }

        .pc-seller-catalog .ts-dropdown .active {
            background: #EEF6FF;
            color: #087BFA;
        }

        @media(max-width: 700px) {
            .pc-seller-catalog .catalog-header {
                flex-direction: column;
            }

            .pc-seller-catalog .header-title h1 {
                font-size: 21px;
            }

            .pc-seller-catalog .overview-grid {
                grid-template-columns: 1fr 1fr;
                gap: 9px;
            }

            .pc-seller-catalog .overview-card {
                padding: 13px 10px;
                gap: 9px;
            }

            .pc-seller-catalog .overview-icon {
                width: 36px;
                height: 36px;
                flex-basis: 36px;
                font-size: 16px;
            }

            .pc-seller-catalog .overview-info strong {
                font-size: 17px;
            }

            .pc-seller-catalog .catalog-panel {
                padding: 15px;
            }

            .pc-seller-catalog .filter-grid {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .pc-seller-catalog .filter-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .pc-seller-catalog .filter-actions .pc-btn {
                padding: 9px 7px;
                font-size: 11px;
            }

            .pc-seller-catalog .results-header {
                align-items: flex-start;
            }
        }

        @media(max-width: 380px) {
            .pc-seller-catalog .overview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- بارگذاری Tom Select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleSelect = document.querySelector(
                '.pc-seller-catalog #vehicle'
            );

            if (
                vehicleSelect &&
                typeof TomSelect !== 'undefined' &&
                !vehicleSelect.tomselect
            ) {
                new TomSelect(vehicleSelect, {
                    plugins: ['remove_button'],
                    maxOptions: 1000,
                    create: false,
                    closeAfterSelect: false,
                    placeholder: 'نام خودرو را جست‌وجو کنید...',
                    searchField: ['text'],
                    render: {
                        no_results: function () {
                            return '<div class="no-results">خودرویی پیدا نشد</div>';
                        }
                    }
                });
            }
        });
    </script>

    {{-- سربرگ --}}
    <div class="catalog-header">

        <div class="header-title">
            <h1>انتخاب قطعه برای فروشگاه</h1>
            <p>
                قطعه موردنظر را از کاتالوگ انتخاب کنید و لینک محصول خود را ثبت کنید.
                محصولات ثبت‌شده در پنل فروشندگی شما قابل مدیریت خواهند بود.
            </p>
        </div>

        <span class="header-label">
            <span>▤</span>
            کاتالوگ تخصصی قطعات
        </span>

    </div>

    {{-- خلاصه وضعیت --}}
    <div class="overview-grid">

        <div class="overview-card">
            <div class="overview-icon">▤</div>
            <div class="overview-info">
                <small>تعداد قطعات کاتالوگ</small>
                <strong>{{ number_format($parts->total()) }}</strong>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon">✓</div>
            <div class="overview-info">
                <small>قطعات ثبت‌شده شما</small>
                <strong>{{ number_format(count($registeredPartIds)) }}</strong>
            </div>
        </div>

    </div>

    {{-- فیلتر قطعات --}}
    <section class="catalog-panel">

        <div class="panel-heading">
            <div>
                <h2>جست‌وجو و فیلتر قطعات</h2>
                <p>برای رسیدن به قطعه موردنظر، خودرو و گروه قطعه را انتخاب کنید.</p>
            </div>

            <span class="panel-tag">⌕ فیلتر پیشرفته</span>
        </div>

        <form action="{{ route('seller.products.catalog') }}"
              method="GET"
              id="catalog-filter-form">

            <div class="filter-grid">

                <div class="field">
                    <label for="vehicle">خودروهای موردنظر</label>

                    <select name="vehicle[]" id="vehicle" multiple>
                        @foreach ($vehicles as $vehicleName)
                            <option value="{{ $vehicleName }}"
                                @selected(in_array($vehicleName, $selectedVehicles, true))>
                                {{ $vehicleName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="category">گروه اصلی قطعه</label>

                    <select name="category" id="category">
                        <option value="">همه گروه‌ها</option>

                        @foreach ($categories as $categoryName)
                            <option value="{{ $categoryName }}"
                                @selected(($category ?? '') === $categoryName)>
                                {{ $categoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="subcategory">زیرگروه قطعه</label>

                    <select name="subcategory" id="subcategory">
                        <option value="">همه زیرگروه‌ها</option>

                        @foreach ($subcategories as $item)
                            <option value="{{ $item }}"
                                @selected(($subcategory ?? '') === $item)>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="q">نام قطعه یا کد فنی</label>

                    <input
                        type="search"
                        name="q"
                        id="q"
                        value="{{ $q ?? '' }}"
                        placeholder="مثلاً سنسور میل سوپاپ"
                    >
                </div>

            </div>

            <div class="filter-actions">

                <button type="submit" class="pc-btn pc-btn-primary">
                    <span>⌕</span>
                    نمایش قطعات
                </button>

                <a href="{{ route('seller.products.catalog') }}"
                   class="pc-btn pc-btn-light">
                    <span>↺</span>
                    پاک‌کردن فیلترها
                </a>

            </div>

            @if(count($selectedVehicles))
                <div class="selected-vehicles">
                    <span class="selected-vehicles-label">خودروهای انتخاب‌شده:</span>

                    @foreach($selectedVehicles as $vehicleName)
                        <span class="vehicle-chip">{{ $vehicleName }}</span>
                    @endforeach
                </div>
            @endif

        </form>

    </section>

    {{-- نتایج --}}
    <section>

        <div class="results-header">

            <div>
                <h2>{{ $category ?: 'تمام قطعات کاتالوگ' }}</h2>

                <p>
                    @if(count($selectedVehicles))
                        فیلترشده بر اساس {{ implode('، ', $selectedVehicles) }}
                    @else
                        فهرست قطعات قابل ثبت در فروشگاه شما
                    @endif
                </p>
            </div>

            <span class="results-count">
                {{ number_format($parts->total()) }} قطعه
            </span>

        </div>

        <div class="table-container">

            <table class="parts-table">

                <thead>
                    <tr>
                        <th>تصویر</th>
                        <th>کد فنی</th>
                        <th>نام قطعه</th>
                        <th>زیرگروه</th>
                        <th>خودرو</th>
                        <th>قیمت شرکت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($parts as $part)

                        <tr>

                            {{-- تصویر --}}
                            <td>
                                @if (!empty($part->img_src))
                                    <img
                                        src="{{ $part->img_src }}"
                                        alt="{{ $part->name_fa }}"
                                        class="part-image"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="no-image">▧</div>
                                @endif
                            </td>

                            {{-- کد فنی --}}
                            <td>
                                <span class="part-code">
                                    {{ $part->technical_code ?: '—' }}
                                </span>
                            </td>

                            {{-- نام --}}
                            <td>
                                <div class="part-name">
                                    {{ $part->name_fa ?: 'بدون نام' }}
                                </div>
                            </td>

                            {{-- زیرگروه --}}
                            <td>
                                <span class="subcategory-chip">
                                    {{ $part->subcategory ?: 'نامشخص' }}
                                </span>
                            </td>

                            {{-- خودرو --}}
                            <td>
                                {{ $part->car_model_raw ?: 'ثبت نشده' }}
                            </td>

                            {{-- قیمت --}}
                            <td>
                                <span class="part-price">
                                    {{ $part->price !== null
                                        ? number_format((float) $part->price)
                                        : 'ثبت نشده' }}
                                </span>
                            </td>

                            {{-- وضعیت ثبت --}}
                            <td>
                                @if (in_array($part->id, $registeredPartIds))
                                    <span class="status-pill status-registered">
                                        ✓ ثبت شده
                                    </span>
                                @else
                                    <span class="status-pill status-unregistered">
                                        ثبت نشده
                                    </span>
                                @endif
                            </td>

                            {{-- عملیات --}}
                            <td>
                                @if (in_array($part->id, $registeredPartIds))

                                    <a href="{{ route('seller.products.index') }}"
                                       class="manage-link">
                                        مدیریت محصول
                                    </a>

                                @else

                                    <a href="{{ route('seller.products.create', ['part_id' => $part->id]) }}"
                                       class="add-product-link">
                                        <span>+</span>
                                        ثبت لینک فروش
                                    </a>

                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="empty-results">
                                <strong>قطعه‌ای پیدا نشد</strong>
                                فیلترها یا عبارت جست‌وجو را تغییر دهید و دوباره امتحان کنید.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- صفحه‌بندی --}}
        <div class="pagination-area">
            {{ $parts->withQueryString()->links() }}
        </div>

    </section>

</div>

@endsection
