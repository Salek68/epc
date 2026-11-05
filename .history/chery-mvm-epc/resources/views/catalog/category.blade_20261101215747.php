
@extends('layouts.app')

@section('title', 'دسته‌بندی قطعات | CHERY – MVM EPC')

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

    // پشتیبانی از انتخاب چند خودرو و سازگاری با مقدار تکی قبلی
    $vehicleInput = request()->input('vehicle', []);

    if (is_string($vehicleInput)) {
        $vehicleInput = [$vehicleInput];
    }

    $selectedVehicles = array_values(array_filter(
        (array) $vehicleInput,
        fn ($value) => is_string($value) && $value !== ''
    ));

    // آیکون دسته‌بندی‌ها
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    new TomSelect('#vehicle', {
        plugins: ['remove_button'],

        maxOptions: 1000,

        create: false,

        closeAfterSelect: false,

        placeholder: '🔍 نام خودرو را جستجو کنید...',

        searchField: ['text'],

        render: {
            no_results: function(data, escape) {
                return '<div class="no-results">خودرویی با این نام پیدا نشد!</div>';
            }
        }
    });

});
</script>
<style>

    #vehicle + .ts-wrapper {
    width: 100%;
}

.ts-wrapper.multi .ts-control {
    min-height: 52px;
    padding: 10px 12px;
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    background: #fff;
    box-shadow: none;
    cursor: text;
}

.ts-wrapper.focus .ts-control {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
}

.ts-wrapper.multi .ts-control > div {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    padding: 5px 9px;
    margin: 3px;
}

.ts-wrapper.plugin-remove_button .item .remove {
    border-left: 1px solid #006cf0;
    margin-left: 7px;
}

.ts-dropdown {
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    margin-top: 5px;
    overflow: hidden;
}

.ts-dropdown .option {
    padding: 11px 14px;
}

.ts-dropdown .active {
    background: #eff6ff;
    color: #1d4ed8;
}

.ts-dropdown .no-results {
    padding: 14px;
    color: #64748b;
    text-align: center;
}

.epc-hint {
    display: block;
    margin-top: 7px;
    color: #64748b;
    font-size: 12px;
}
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
    border: 1px solid rgba(13, 109, 253, 0.2);
    border-radius: 11px;
    background: #0a1423;
    color: var(--epc-text);
    font: inherit;
    font-size: 12px;
    outline: none;
    transition: border-color .2s,box-shadow .2s;
}

.epc-page .epc-field select[multiple] {
    min-height: 135px;
    padding: 7px;
}

.epc-page .epc-field select[multiple] option {
    padding: 8px 10px;
    margin: 2px 0;
    border-radius: 6px;
}

.epc-page .epc-field select[multiple] option:checked {
    background: #21465a linear-gradient(#21465a,#21465a);
    color: #8ff7e9;
}

.epc-page .epc-field select option {
    background: #101b2d;
    color: #edf4ff;
}

.epc-page .epc-field input::placeholder {
    color: #687b96;
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
    transition: transform .18s,filter .18s;
}

.epc-page .epc-btn:hover {
    color: #07121f;
    filter: brightness(1.08);
    transform: translateY(-1px);
    text-decoration: none;
}

.epc-page .epc-btn-secondary {
    color: #d5e2f5;
    background: #1a2a42;
    border-color: var(--epc-line);
}

.epc-page .epc-btn-secondary:hover {
    color: white;
    background: #243a57;
}

.epc-page .epc-hint {
    margin: 12px 0 0;
    color: var(--epc-muted);
    font-size: 10px;
    line-height: 1.9;
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
    min-width: 0;
    padding: 17px;
    border: 1px solid var(--epc-line);
    border-radius: 16px;
    background: linear-gradient(140deg,#15263e,#0f1b2d);
}

.epc-page .epc-stat-icon {
    display: grid;
    place-items: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: rgba(85,224,213,.1);
    font-size: 21px;
}

.epc-page .epc-stat small {
    display: block;
    margin-bottom: 5px;
    color: var(--epc-muted);
    font-size: 11px;
}

.epc-page .epc-stat strong {
    color: #fff;
    font-size: 23px;
    font-weight: 900;
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
    font-weight: 900;
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

.epc-page .epc-bar-label span:first-child {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.epc-page .epc-bar-label strong {
    color: var(--epc-cyan);
    white-space: nowrap;
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
    transition: width .7s ease;
}

.epc-page .epc-chart-empty {
    padding: 16px;
    border: 1px dashed var(--epc-line);
    border-radius: 12px;
    color: var(--epc-muted);
    font-size: 11px;
    line-height: 2;
}

.epc-page .epc-category-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 11px;
}

.epc-page .epc-category {
    display: block;
    min-width: 0;
    padding: 15px;
    border: 1px solid var(--epc-line);
    border-radius: 15px;
    background: #0c1727;
    color: var(--epc-text);
    text-decoration: none;
    transition: transform .2s,border-color .2s,background .2s;
}

.epc-page .epc-category:hover {
    color: white;
    background: #14263e;
    border-color: rgba(85,224,213,.4);
    transform: translateY(-3px);
    text-decoration: none;
}

.epc-page .epc-category-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 13px;
}

.epc-page .epc-category-icon {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    border: 1px solid rgba(120,180,230,.14);
    background: linear-gradient(145deg,rgba(85,224,213,.15),rgba(107,147,255,.14));
    font-size: 22px;
}

.epc-page .epc-category-arrow {
    color: var(--epc-cyan);
    font-size: 19px;
}

.epc-page .epc-category strong {
    display: block;
    margin-bottom: 5px;
    color: #f3f7ff;
    font-size: 12px;
    line-height: 1.8;
}

.epc-page .epc-category small {
    color: var(--epc-muted);
    font-size: 10px;
}

.epc-page .epc-category-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 7px;
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid var(--epc-line);
    color: #a8bbd4;
    font-size: 10px;
}

.epc-page .epc-category-footer b {
    color: var(--epc-cyan);
}

.epc-page .epc-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--epc-line);
    border-radius: 13px;
}

.epc-page .epc-table {
    width: 100%;
    min-width: 760px;
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

.epc-page .epc-table tbody tr {
    transition: background .15s;
}

.epc-page .epc-table tbody tr:hover {
    background: rgba(107,147,255,.07);
}

.epc-page .epc-table tbody tr:last-child td {
    border-bottom: 0;
}

.epc-page .epc-code {
    display: inline-block;
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

.epc-page .epc-view-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 10px;
    border-radius: 9px;
    background: linear-gradient(135deg,var(--epc-cyan),#8daaff);
    color: #07121f !important;
    font-weight: 900;
    text-decoration: none;
    white-space: nowrap;
}

.epc-page .epc-view-link:hover {
    filter: brightness(1.08);
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

@media (max-width: 950px) {
    .epc-page .epc-filter-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .epc-page .epc-category-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .epc-page .epc-chart-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {
    .epc-page .epc-hero {
        padding: 22px 17px;
        border-radius: 19px;
    }

    .epc-page .epc-hero h1 {
        font-size: 23px;
    }

    .epc-page .epc-filter-grid,
    .epc-page .epc-stat-grid {
        grid-template-columns: 1fr;
    }

    .epc-page .epc-category-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 8px;
    }

    .epc-page .epc-category {
        padding: 11px;
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

    .epc-page .epc-actions .epc-btn {
        flex: 1;
    }

    .epc-page .epc-hero-chip {
        flex: 1;
        min-width: 130px;
    }
}


/* تصویر محصول */
.epc-product-image {
    width: 65px;
    height: 65px;
    object-fit: contain;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    padding: 4px;
    transition: .25s ease;
}

.epc-product-image:hover {
    transform: scale(1.12);
}

/* تصویر ناموجود */
.epc-no-image {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    border-radius: 10px;
    font-size: 23px;
}

/* دکمه خرید */
.epc-buy-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 9px 13px;
    border-radius: 9px;
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    transition: .2s ease;
}

.epc-buy-link:hover {
    background: #16a34a;
    color: #fff;
    transform: translateY(-2px);
}

/* بدون لینک خرید */
.epc-no-buy {
    color: #94a3b8;
    font-size: 12px;
    white-space: nowrap;
}

</style>

<div class="epc-page">

    {{-- هدر صفحه --}}
    <section class="epc-hero">
        <span class="epc-kicker">EPC · SMART PARTS FINDER</span>

        <h1>مرکز هوشمند دسته‌بندی قطعات</h1>

        <p>
            کاتالوگ قطعات چری و ام‌وی‌ام؛ از انتخاب مدل خودرو تا جست‌وجوی
            کد فنی و مشاهده جزئیات قطعه، همه در یک محیط یکپارچه.
        </p>

        <div class="epc-hero-bottom">
            <div class="epc-hero-chip">
                <span class="epc-hero-chip-icon">▤</span>
                <div>
                    <small>تعداد نتایج</small>
                    <strong>{{ number_format($parts->total()) }}</strong>
                </div>
            </div>

            <div class="epc-hero-chip">
                <span class="epc-hero-chip-icon">⚙</span>
                <div>
                    <small>گروه‌های موجود در صفحه</small>
                    <strong>{{ number_format($categoryCount) }}</strong>
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

    {{-- نمودارها --}}
    <div class="epc-chart-grid">

        <section class="epc-chart-card">
            <div class="epc-chart-title">
                <h2>ترکیب دسته‌بندی قطعات</h2>
                <p>سهم هر گروه از قطعات بارگذاری‌شده در صفحه فعلی</p>
            </div>

            @if ($loadedCount > 0 && $categoryStats->isNotEmpty())
                <div class="epc-donut-layout">
                    <div
                        class="epc-donut has-data"
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
                                <span
                                    class="epc-legend-dot"
                                    style="--legend-color: {{ $legendColor }}">
                                </span>

                                <span class="epc-legend-name" title="{{ $name }}">
                                    {{ $name }}
                                </span>

                                <span class="epc-legend-number">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        @endforeach

                        @if ($loadedCount > $topCategories->sum())
                            <div class="epc-legend-row">
                                <span class="epc-legend-dot"
                                      style="--legend-color:#34465f"></span>
                                <span class="epc-legend-name">سایر گروه‌ها</span>
                                <span class="epc-legend-number">
                                    {{ round(
                                        (($loadedCount - $topCategories->sum())
                                        / $loadedCount) * 100,
                                        1
                                    ) }}%
                                </span>
                            </div>
                        @endif
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
                <p>مقایسه تعداد قطعات در گروه‌های برتر صفحه فعلی</p>
            </div>

            @if ($topCategories->isNotEmpty())
                <div class="epc-bars">
                    @foreach ($topCategories as $name => $count)
                        @php
                            $barWidth = ($count / $maxCategoryCount) * 100;
                        @endphp

                        <div>
                            <div class="epc-bar-label">
                                <span title="{{ $name }}">{{ $name }}</span>
                                <strong>{{ number_format($count) }} قطعه</strong>
                            </div>

                            <div class="epc-bar-track">
                                <span
                                    class="epc-bar-fill"
                                    style="--bar-width: {{ $barWidth }}%">
                                </span>
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
                <p>می‌توانی یک یا چند خودرو را انتخاب کنی.</p>
            </div>

            <span class="epc-badge">⌕ جست‌وجوی پیشرفته</span>
        </div>

        <form
            action="{{ route('catalog.category') }}"
            method="GET"
            id="catalog-filter-form">

            <div class="epc-filter-grid">

                {{-- انتخاب چند خودرو --}}
             <div class="epc-field">
    <label for="vehicle">🚘 انتخاب خودروها</label>

    <select
        name="vehicle[]"
        id="vehicle"
        multiple
        placeholder="نام خودرو را جستجو کنید...">

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

    <small class="epc-hint">
        می‌توانید چند خودرو را هم‌زمان انتخاب کنید.
    </small>
</div>

                <div class="epc-field">
                    <label for="category">▦ گروه اصلی</label>

                    <select name="category" id="category">
                        <option value="">همه گروه‌ها</option>

                        @foreach ($categories as $categoryName)
                            <option
                                value="{{ $categoryName }}"
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
                            <option
                                value="{{ $item }}"
                                @selected(($subcategory ?? '') === $item)>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="epc-field">
                    <label for="q">⌕ نام قطعه یا کد فنی</label>

                    <input
                        type="search"
                        name="q"
                        id="q"
                        value="{{ $q ?? '' }}"
                        placeholder="مثلاً سنسور میل سوپاپ"
                        autocomplete="off">
                </div>
            </div>

            <div class="epc-actions">
                <button type="submit" class="epc-btn">
                    ⌕ نمایش قطعات
                </button>

                <a
                    href="{{ route('catalog.category') }}"
                    class="epc-btn epc-btn-secondary">
                    ↺ پاک‌کردن فیلترها
                </a>
            </div>
        </form>
    </section>


{{-- فهرست قطعات --}}
<section class="epc-card">

    <div class="epc-heading">
        <div>
            <h2>
                {{ $category ?: 'تمام گروه‌ها' }}

                @if (!empty($subcategory))
                    <span style="color:#55e0d5">
                        / {{ $subcategory }}
                    </span>
                @endif
            </h2>

            <p>
                @if (count($selectedVehicles))
                    قطعات خودروهای:
                    {{ implode('، ', $selectedVehicles) }}
                @else
                    فهرست قطعات بر اساس فیلترهای انتخاب‌شده
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
                    <th>تصویر</th>
                    <th>کد فنی</th>
                    <th>نام قطعه</th>
                    <th>زیرگروه</th>
                    <th>خودرو</th>
                    <th>قیمت شرکت</th>
                    <th>جزئیات</th>
                    <th>خرید</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($parts as $part)

                    <tr>

                        {{-- تصویر قطعه --}}
                        <td>
                                @if (!empty($part->activeSellerProducts->))
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
                        </td>

                        {{-- کد فنی --}}
                        <td>
                            <span class="epc-code">
                                {{ $part->technical_code ?: '—' }}
                            </span>
                        </td>

                        {{-- نام قطعه --}}
                        <td>
                            <div class="epc-part-name">
                                {{ $part->name_fa ?: 'بدون نام' }}
                            </div>
                        </td>

                        {{-- زیرگروه --}}
                        <td>
                            <span class="epc-subcategory">
                                {{ $part->subcategory ?: 'نامشخص' }}
                            </span>
                        </td>

                        {{-- خودرو --}}
                        <td>
                            {{ $part->car_model_raw ?: 'ثبت نشده' }}
                        </td>

                        {{-- قیمت شرکت --}}
                        <td class="epc-price">
                            {{ $part->price !== null
                                ? number_format((float) $part->price)
                                : 'ثبت نشده' }}
                        </td>

                        {{-- جزئیات --}}
                        <td>
                            <a
                                class="epc-view-link"
                                href="{{ route('catalog.show', $part->id) }}">
                                مشاهده ↗
                            </a>
                        </td>

                        {{-- خرید از فروشندگان یا لینک اصلی --}}
                        <td>
@if ($part->activeSellerProducts->isNotEmpty())

                                <a
                                    class="epc-buy-link epc-seller-buy-link"
                                    href="{{ route('catalog.show', $part->id) }}#seller-offers">

                                    🛒 خرید از فروشندگان

                                    <span class="epc-seller-count">
                                        {{ $part->activeSellerProducts->count() }}
                                    </span>

                                </a>

                            @elseif (!empty($part->p_link))

                                <a
                                    class="epc-buy-link"
                                    href="{{ $part->p_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    🛒 خرید محصول
                                </a>

                            @else

                                <span class="epc-no-buy">
                                    لینک خرید ندارد
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="epc-empty">

                            <div style="font-size:30px;margin-bottom:8px">
                                ⌕
                            </div>

                            قطعه‌ای با این مشخصات پیدا نشد.

                            <br>

                            فیلترها را تغییر بده و دوباره امتحان کن.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="epc-pagination">
        {{ $parts->withQueryString()->links() }}
    </div>

</section>


<style>
.epc-seller-buy-link {
    background: #087f83 !important;
    color: #fff !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    white-space: nowrap;
}

.epc-seller-buy-link:hover {
    background: #09a6a0 !important;
    color: #fff !important;
}

.epc-seller-count {
    background: rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 2px 7px;
    font-size: 11px;
}

.epc-no-buy {
    color: #8799a9;
    font-size: 12px;
    white-space: nowrap;
}
</style>

</div>



@endsection
