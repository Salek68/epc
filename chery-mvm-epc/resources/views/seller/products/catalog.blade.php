
@extends('layouts.app')

@section('title', 'انتخاب قطعه برای فروش | پنل فروشنده')

@section('content')

@php
    $loadedParts = $parts->getCollection();

    $categoryStats = $loadedParts
        ->filter(fn ($part) => !empty($part->category))
        ->groupBy('category')
        ->map(fn ($items) => $items->count())
        ->sortDesc();

    $topCategories = $categoryStats->take(6);

    $chartColors = [
        '#55e0d5',
        '#6b93ff',
        '#b18aff',
        '#ffbd69',
        '#ff7d9c',
        '#72d69b',
    ];

    $loadedCount = $loadedParts->count();
    $categoryCount = $categoryStats->count();

    $donutGradient = '';
    $degrees = 0;
    $colorIndex = 0;

    if ($loadedCount > 0) {
        foreach ($topCategories as $name => $count) {
            $segment = ($count / $loadedCount) * 360;
            $color = $chartColors[$colorIndex % count($chartColors)];

            $donutGradient .= $color . ' ' . $degrees . 'deg '
                . ($degrees + $segment) . 'deg, ';

            $degrees += $segment;
            $colorIndex++;
        }

        $otherCount = max(0, $loadedCount - $topCategories->sum());

        if ($otherCount > 0) {
            $donutGradient .= '#34465f ' . $degrees . 'deg 360deg';
        } else {
            $donutGradient = rtrim($donutGradient, ', ');
        }
    }

    $maxCategoryCount = max(1, (int) $topCategories->max());

    $vehicleInput = request()->input('vehicle', []);

    if (is_string($vehicleInput)) {
        $vehicleInput = [$vehicleInput];
    }

    $selectedVehicles = array_values(array_filter(
        (array) $vehicleInput,
        fn ($value) => is_string($value) && $value !== ''
    ));

    $registeredPartIds = $registeredPartIds ?? [];

    $categoryIcon = function ($name) {
        $name = mb_strtolower((string) $name);

        $icons = [
            'موتور' => '⚙️',
            'موتوری' => '⚙️',
            'بدنه' => '🚘',
            'برق' => '⚡',
            'سنسور' => '◉',
            'ترمز' => '🛞',
            'گیربکس' => '🔩',
            'تعلیق' => '🔧',
            'جلوبندی' => '🔧',
            'چراغ' => '💡',
            'خنک' => '❄️',
            'داخلی' => '🪑',
            'کابین' => '🪑',
            'فیلتر' => '▤',
            'سوخت' => '⛽',
            'اگزوز' => '♨️',
        ];

        foreach ($icons as $keyword => $icon) {
            if (mb_strpos($name, $keyword) !== false) {
                return $icon;
            }
        }

        return '🔩';
    };
@endphp

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css">

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const vehicleSelect = document.querySelector('#vehicle');

    if (vehicleSelect && !vehicleSelect.tomselect) {
        new TomSelect('#vehicle', {
            plugins: ['remove_button'],
            maxOptions: 1000,
            create: false,
            closeAfterSelect: false,
            placeholder: '🔍 نام خودرو را جستجو کنید...',
            searchField: ['text']
        });
    }
});
</script>

<style>
.epc-page {
    --epc-bg: #080f1c;
    --epc-panel: #101b2d;
    --epc-line: rgba(167,190,225,.15);
    --epc-text: #0062ff;
    --epc-muted: #91a2bc;
    --epc-blue: #6b93ff;
    --epc-cyan: #55e0d5;

    direction: rtl;
    color: var(--epc-text);
    padding-bottom: 15px;
}

.epc-page *,
.epc-page *::before,
.epc-page *::after {
    box-sizing: border-box;
}

.epc-page .epc-hero {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding: clamp(23px, 4vw, 40px);
    margin-bottom: 20px;
    border: 1px solid rgba(130,165,230,.24);
    border-radius: 25px;
    background:
        radial-gradient(circle at 8% 20%, rgba(85,224,213,.15), transparent 27%),
        linear-gradient(120deg,#101b30 0%,#19355b 58%,#10434b 100%);
    box-shadow: 0 20px 55px rgba(0,0,0,.18);
}

.epc-page .epc-hero::after {
    content: "EPC";
    position: absolute;
    z-index: -1;
    left: 20px;
    bottom: -43px;
    font-size: clamp(100px,17vw,170px);
    line-height: 1;
    font-weight: 1000;
    color: rgba(255,255,255,.035);
    pointer-events: none;
}

.epc-page .epc-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    padding: 6px 11px;
    border: 1px solid rgba(85,224,213,.25);
    border-radius: 30px;
    background: rgba(85,224,213,.08);
    color: var(--epc-cyan);
    font-size: 11px;
    font-weight: 800;
}

.epc-page .epc-kicker::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 12px currentColor;
}

.epc-page .epc-hero h1 {
    margin: 0 0 8px;
    color: #fff;
    font-size: clamp(24px,3vw,35px);
    line-height: 1.7;
    font-weight: 900;
}

.epc-page .epc-hero p {
    max-width: 720px;
    margin: 0;
    color: #c2d3eb;
    font-size: 13px;
    line-height: 2.1;
}

.epc-page .epc-hero-bottom {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 23px;
}

.epc-page .epc-hero-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 13px;
    background: rgba(5,15,30,.25);
    min-width: 0;
}

.epc-page .epc-hero-chip-icon {
    display: grid;
    place-items: center;
    flex: 0 0 37px;
    width: 37px;
    height: 37px;
    border-radius: 11px;
    background: rgba(85,224,213,.1);
    font-size: 19px;
}

.epc-page .epc-hero-chip small {
    display: block;
    color: #a8bbd4;
    font-size: 10px;
    margin-bottom: 3px;
}

.epc-page .epc-hero-chip strong {
    color: white;
    font-size: 15px;
    overflow-wrap: anywhere;
}

.epc-page .epc-card {
    min-width: 0;
    padding: clamp(15px,2.4vw,23px);
    margin-bottom: 20px;
    border: 1px solid var(--epc-line);
    border-radius: 19px;
    background: linear-gradient(145deg,rgba(17,29,47,.98),rgba(12,22,37,.98));
    box-shadow: 0 12px 35px rgba(0,0,0,.1);
}

.epc-page .epc-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}

.epc-page .epc-heading h2 {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0;
    color: #f3f7ff;
    font-size: 17px;
    font-weight: 800;
}

.epc-page .epc-heading h2::before {
    content: "";
    width: 4px;
    height: 20px;
    border-radius: 5px;
    background: linear-gradient(var(--epc-cyan),var(--epc-blue));
}

.epc-page .epc-heading p {
    margin: 6px 0 0;
    color: var(--epc-muted);
    font-size: 11px;
    line-height: 1.9;
}

.epc-page .epc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border: 1px solid rgba(85,224,213,.17);
    border-radius: 9px;
    background: rgba(85,224,213,.07);
    color: var(--epc-cyan);
    font-size: 10px;
    font-weight: 800;
}

.epc-page .epc-filter-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 13px;
}

.epc-page .epc-field {
    min-width: 0;
}

.epc-page .epc-field label {
    display: block;
    margin-bottom: 7px;
    color: #cbd8eb;
    font-size: 12px;
    font-weight: 700;
}

.epc-page .epc-field input,
.epc-page .epc-field select {
    width: 100%;
    min-width: 0;
    min-height: 45px;
    padding: 10px 12px;
    border: 1px solid rgba(13,109,253,.2);
    border-radius: 11px;
    background: #0a1423;
    color: #dceaff;
    font: inherit;
    font-size: 12px;
    outline: none;
}

.epc-page .epc-field select option {
    background: #101b2d;
    color: #edf4ff;
}

.epc-page .epc-field input:focus,
.epc-page .epc-field select:focus {
    border-color: var(--epc-cyan);
    box-shadow: 0 0 0 3px rgba(85,224,213,.1);
}

.epc-page .epc-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 17px;
}

.epc-page .epc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 9px 16px;
    border: 1px solid transparent;
    border-radius: 11px;
    background: linear-gradient(135deg,var(--epc-cyan),#8daaff);
    color: #07121f;
    text-decoration: none;
    font: inherit;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
}

.epc-page .epc-btn:hover {
    filter: brightness(1.08);
    color: #07121f;
}

.epc-page .epc-btn-secondary {
    color: #d5e2f5;
    background: #1a2a42;
    border-color: var(--epc-line);
}

.epc-page .epc-stat-grid {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 13px;
    margin-bottom: 20px;
}

.epc-page .epc-stat {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px;
    border: 1px solid var(--epc-line);
    border-radius: 16px;
    background: linear-gradient(140deg,#15263e,#0f1b2d);
}

.epc-page .epc-stat-icon {
    display: grid;
    place-items: center;
    flex: 0 0 44px;
    height: 44px;
    border-radius: 14px;
    background: rgba(85,224,213,.1);
    font-size: 21px;
}

.epc-page .epc-chart-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.epc-page .epc-chart-card {
    min-width: 0;
    padding: 20px;
    border: 1px solid var(--epc-line);
    border-radius: 19px;
    background: linear-gradient(145deg,#111e32,#0b1525);
}

.epc-page .epc-chart-title {
    margin-bottom: 18px;
}

.epc-page .epc-chart-title h2 {
    margin: 0 0 5px;
    color: #f3f7ff;
    font-size: 15px;
}

.epc-page .epc-chart-title p {
    margin: 0;
    color: var(--epc-muted);
    font-size: 10px;
    line-height: 1.9;
}

.epc-page .epc-donut-layout {
    display: grid;
    grid-template-columns: 145px minmax(0,1fr);
    gap: 20px;
    align-items: center;
}

.epc-page .epc-donut {
    display: grid;
    place-items: center;
    position: relative;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: conic-gradient(#24354d 0deg 360deg);
}

.epc-page .epc-donut.has-data {
    background: conic-gradient(var(--epc-chart-gradient));
}

.epc-page .epc-donut::after {
    content: "";
    position: absolute;
    inset: 16px;
    border-radius: 50%;
    background: #101b2d;
    border: 1px solid var(--epc-line);
}

.epc-page .epc-donut-center {
    position: relative;
    z-index: 1;
    text-align: center;
}

.epc-page .epc-donut-center strong {
    display: block;
    color: white;
    font-size: 24px;
}

.epc-page .epc-donut-center span {
    display: block;
    margin-top: 3px;
    color: var(--epc-muted);
    font-size: 10px;
}

.epc-page .epc-legend {
    display: grid;
    gap: 10px;
    min-width: 0;
}

.epc-page .epc-legend-row {
    display: grid;
    grid-template-columns: 8px minmax(0,1fr) auto;
    align-items: center;
    gap: 8px;
    font-size: 11px;
}

.epc-page .epc-legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 3px;
    background: var(--legend-color);
}

.epc-page .epc-legend-name {
    overflow: hidden;
    color: #c9d7ea;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.epc-page .epc-legend-number {
    color: white;
    font-weight: 800;
}

.epc-page .epc-bars {
    display: grid;
    gap: 15px;
}

.epc-page .epc-bar-label {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 7px;
    color: #d7e3f4;
    font-size: 11px;
}

.epc-page .epc-bar-track {
    height: 8px;
    overflow: hidden;
    border-radius: 20px;
    background: #1d2b41;
}

.epc-page .epc-bar-fill {
    display: block;
    height: 100%;
    width: var(--bar-width);
    border-radius: 20px;
    background: linear-gradient(90deg,#6b93ff,#55e0d5);
}

.epc-page .epc-chart-empty {
    padding: 16px;
    border: 1px dashed var(--epc-line);
    border-radius: 12px;
    color: var(--epc-muted);
    font-size: 11px;
    line-height: 2;
}

.epc-page .epc-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--epc-line);
    border-radius: 13px;
}

.epc-page .epc-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    background: #0d1727;
}

.epc-page .epc-table th,
.epc-page .epc-table td {
    padding: 13px 14px;
    text-align: right;
    border-bottom: 1px solid var(--epc-line);
    font-size: 12px;
    vertical-align: middle;
}

.epc-page .epc-table th {
    color: #aebfda;
    background: #16263d;
    white-space: nowrap;
    font-size: 11px;
}

.epc-page .epc-table tbody tr:hover {
    background: rgba(107,147,255,.07);
}

.epc-page .epc-code {
    direction: ltr;
    unicode-bidi: plaintext;
    font-family: Consolas,monospace;
    white-space: nowrap;
    font-weight: 800;
    color: var(--epc-cyan);
}

.epc-page .epc-part-name {
    min-width: 170px;
    color: #f3f7ff;
    font-weight: 800;
}

.epc-page .epc-subcategory {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 8px;
    background: rgba(107,147,255,.08);
    color: #a9caff;
    white-space: nowrap;
}

.epc-page .epc-price {
    color: #67dfaa;
    white-space: nowrap;
    font-weight: 800;
}

.epc-page .epc-product-image,
.epc-page .epc-no-image {
    width: 65px;
    height: 65px;
    object-fit: contain;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    padding: 4px;
}

.epc-page .epc-no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.epc-page .epc-seller-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 9px 13px;
    border-radius: 9px;
    background: linear-gradient(135deg,#55e0d5,#8daaff);
    color: #07121f !important;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    white-space: nowrap;
}

.epc-page .epc-seller-btn:hover {
    filter: brightness(1.1);
}

.epc-page .epc-registered {
    display: inline-block;
    padding: 8px 11px;
    border-radius: 9px;
    background: rgba(114,214,155,.12);
    color: #72d69b;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

.epc-page .epc-empty {
    padding: 35px !important;
    text-align: center !important;
    color: var(--epc-muted);
    line-height: 2;
}

.epc-page .epc-pagination {
    margin-top: 18px;
    overflow-x: auto;
}

.epc-page .epc-pagination nav {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.epc-page .epc-pagination svg {
    width: 18px;
    height: 18px;
}

.epc-page .epc-pagination a,
.epc-page .epc-pagination span {
    color: #dce6f5;
}

#vehicle + .ts-wrapper {
    width: 100%;
}

.epc-page .ts-wrapper.multi .ts-control {
    min-height: 52px;
    padding: 10px 12px;
    border: 1px solid #34465f;
    border-radius: 12px;
    background: #0a1423;
    color: white;
    box-shadow: none;
}

.epc-page .ts-wrapper.multi .ts-control > div {
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 7px;
    padding: 5px 9px;
    margin: 3px;
}

.epc-page .ts-dropdown {
    background: #101b2d;
    color: white;
    border: 1px solid #34465f;
    border-radius: 12px;
}

.epc-page .ts-dropdown .option {
    color: white;
}

.epc-page .ts-dropdown .active {
    background: #21465a;
    color: #8ff7e9;
}

@media (max-width: 950px) {
    .epc-page .epc-filter-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .epc-page .epc-chart-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {
    .epc-page .epc-filter-grid,
    .epc-page .epc-stat-grid {
        grid-template-columns: 1fr;
    }

    .epc-page .epc-donut-layout {
        grid-template-columns: 105px minmax(0,1fr);
        gap: 13px;
    }

    .epc-page .epc-donut {
        width: 104px;
        height: 104px;
    }

    .epc-page .epc-donut::after {
        inset: 12px;
    }

    .epc-page .epc-donut-center strong {
        font-size: 19px;
    }

    .epc-page .epc-chart-card {
        padding: 15px;
    }

    .epc-page .epc-hero-chip {
        flex: 1;
        min-width: 130px;
    }
}
</style>

<div class="epc-page">

    <section class="epc-hero">
        <span class="epc-kicker">EPC · SELLER PANEL</span>

        <h1>انتخاب قطعه برای فروشگاه شما</h1>

        <p>
            از کاتالوگ تخصصی چری و ام‌وی‌ام قطعه موردنظر را انتخاب کنید،
            لینک فروشگاه و تصویر اختصاصی خود را ثبت کنید و محصولاتتان را مدیریت کنید.
        </p>

        <div class="epc-hero-bottom">

            <div class="epc-hero-chip">
                <span class="epc-hero-chip-icon">▤</span>
                <div>
                    <small>کل قطعات</small>
                    <strong>{{ number_format($parts->total()) }}</strong>
                </div>
            </div>

            <div class="epc-hero-chip">
                <span class="epc-hero-chip-icon">✓</span>
                <div>
                    <small>قطعات ثبت‌شده شما</small>
                    <strong>{{ count($registeredPartIds) }}</strong>
                </div>
            </div>

            <div class="epc-hero-chip">
                <span class="epc-hero-chip-icon">🚘</span>
                <div>
                    <small>خودروهای انتخاب‌شده</small>
                    <strong style="font-size:13px">
                        {{ count($selectedVehicles)
                            ? implode('، ', $selectedVehicles)
                            : 'همه خودروها' }}
                    </strong>
                </div>
            </div>

        </div>
    </section>

    {{-- نمودار دسته‌بندی --}}
    <div class="epc-chart-grid">

        <section class="epc-chart-card">
            <div class="epc-chart-title">
                <h2>ترکیب دسته‌بندی قطعات</h2>
                <p>سهم گروه‌های قطعات در نتایج صفحه فعلی</p>
            </div>

            @if ($loadedCount > 0 && $categoryStats->isNotEmpty())

                <div class="epc-donut-layout">

                    <div class="epc-donut has-data"
                         style="--epc-chart-gradient: {{ $donutGradient }}">
                        <div class="epc-donut-center">
                            <strong>{{ number_format($loadedCount) }}</strong>
                            <span>قطعه در صفحه</span>
                        </div>
                    </div>

                    <div class="epc-legend">
                        @foreach ($topCategories as $name => $count)
                            @php
                                $legendColor = $chartColors[
                                    $loop->index % count($chartColors)
                                ];

                                $percentage = round(
                                    ($count / $loadedCount) * 100,
                                    1
                                );
                            @endphp

                            <div class="epc-legend-row">
                                <span class="epc-legend-dot"
                                      style="--legend-color: {{ $legendColor }}"></span>

                                <span class="epc-legend-name">{{ $name }}</span>

                                <span class="epc-legend-number">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        @endforeach
                    </div>

                </div>

            @else
                <div class="epc-chart-empty">
                    داده‌ای برای نمایش نمودار وجود ندارد.
                </div>
            @endif
        </section>

        <section class="epc-chart-card">
            <div class="epc-chart-title">
                <h2>پرتکرارترین گروه‌ها</h2>
                <p>مقایسه تعداد قطعات در گروه‌های برتر</p>
            </div>

            @if ($topCategories->isNotEmpty())

                <div class="epc-bars">
                    @foreach ($topCategories as $name => $count)
                        @php
                            $barWidth = ($count / $maxCategoryCount) * 100;
                        @endphp

                        <div>
                            <div class="epc-bar-label">
                                <span>{{ $name }}</span>
                                <strong>{{ number_format($count) }} قطعه</strong>
                            </div>

                            <div class="epc-bar-track">
                                <span class="epc-bar-fill"
                                      style="--bar-width: {{ $barWidth }}%"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="epc-chart-empty">
                    داده‌ای برای مقایسه وجود ندارد.
                </div>
            @endif
        </section>

    </div>

    {{-- فیلترها --}}
    <section class="epc-card">

        <div class="epc-heading">
            <div>
                <h2>جست‌وجو و فیلتر قطعات</h2>
                <p>یک یا چند خودرو را انتخاب کنید.</p>
            </div>

            <span class="epc-badge">⌕ جست‌وجوی پیشرفته</span>
        </div>

        <form action="{{ route('seller.products.catalog') }}"
              method="GET"
              id="catalog-filter-form">

            <div class="epc-filter-grid">

                <div class="epc-field">
                    <label for="vehicle">🚘 انتخاب خودروها</label>

                    <select name="vehicle[]" id="vehicle" multiple>
                        @foreach ($vehicles as $vehicleName)
                            <option value="{{ $vehicleName }}"
                                @selected(in_array($vehicleName, $selectedVehicles, true))>
                                {{ $vehicleName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="epc-field">
                    <label for="category">▦ گروه اصلی</label>

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

                <div class="epc-field">
                    <label for="subcategory">◈ زیرگروه</label>

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

                <div class="epc-field">
                    <label for="q">⌕ نام قطعه یا کد فنی</label>

                    <input type="search"
                           name="q"
                           id="q"
                           value="{{ $q ?? '' }}"
                           placeholder="مثلاً سنسور میل سوپاپ">
                </div>

            </div>

            <div class="epc-actions">
                <button type="submit" class="epc-btn">
                    ⌕ نمایش قطعات
                </button>

                <a href="{{ route('seller.products.catalog') }}"
                   class="epc-btn epc-btn-secondary">
                    ↺ پاک‌کردن فیلترها
                </a>
            </div>

        </form>
    </section>

    {{-- جدول قطعات --}}
    <section class="epc-card">

        <div class="epc-heading">
            <div>
                <h2>{{ $category ?: 'تمام گروه‌ها' }}</h2>
                <p>
                    @if (count($selectedVehicles))
                        خودروهای: {{ implode('، ', $selectedVehicles) }}
                    @else
                        فهرست قطعات کاتالوگ EPC
                    @endif
                </p>
            </div>

            <span class="epc-badge">
                {{ number_format($parts->total()) }} نتیجه
            </span>
        </div>

        <div class="epc-table-wrap">

            <table class="epc-table">

                <thead>
                    <tr>
                        <th>تصویر کاتالوگ</th>
                        <th>کد فنی</th>
                        <th>نام قطعه</th>
                        <th>زیرگروه</th>
                        <th>خودرو</th>
                        <th>قیمت شرکت</th>
                        <th>وضعیت ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($parts as $part)

                        <tr>

                            <td>
                                @if (!empty($part->img_src))
                                    <img src="{{ $part->img_src }}"
                                         alt="{{ $part->name_fa }}"
                                         class="epc-product-image"
                                         loading="lazy">
                                @else
                                    <div class="epc-no-image">📷</div>
                                @endif
                            </td>

                            <td>
                                <span class="epc-code">
                                    {{ $part->technical_code ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <div class="epc-part-name">
                                    {{ $part->name_fa ?: 'بدون نام' }}
                                </div>
                            </td>

                            <td>
                                <span class="epc-subcategory">
                                    {{ $part->subcategory ?: 'نامشخص' }}
                                </span>
                            </td>

                            <td>
                                {{ $part->car_model_raw ?: 'ثبت نشده' }}
                            </td>

                            <td class="epc-price">
                                {{ $part->price !== null
                                    ? number_format((float) $part->price)
                                    : 'ثبت نشده' }}
                            </td>

                            <td>
                                @if (in_array($part->id, $registeredPartIds))
                                    <span class="epc-registered">
                                        ✓ ثبت شده
                                    </span>
                                @else
                                    <span style="color:#91a2bc;font-size:11px">
                                        ثبت نشده
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if (in_array($part->id, $registeredPartIds))
                                    <a href="{{ route('seller.products.index') }}"
                                       class="epc-registered">
                                        مدیریت محصول
                                    </a>
                                @else
                                    <a href="{{ route('seller.products.create', ['part_id' => $part->id]) }}"
                                       class="epc-seller-btn">
                                        + ثبت لینک فروش
                                    </a>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="epc-empty">
                                قطعه‌ای با این مشخصات پیدا نشد.
                                <br>
                                فیلترها را تغییر دهید و دوباره امتحان کنید.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="epc-pagination">
            {{ $parts->withQueryString()->links() }}
        </div>

    </section>

</div>

@endsection
