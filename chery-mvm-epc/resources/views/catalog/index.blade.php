@extends('layouts.app')

@section('title', 'کاتالوگ قطعات CHERY – MVM')

@section('content')
<style>
    .epc-page {
        --epc-bg: #080f1c;
        --epc-panel: #101b2d;
        --epc-panel2: #14243a;
        --epc-line: rgba(167,190,225,.15);
        --epc-text: #edf4ff;
        --epc-muted: #91a2bc;
        --epc-blue: #6b93ff;
        --epc-cyan: #55e0d5;
        direction: rtl;
        color: var(--epc-text);
        padding-bottom: 12px;
    }
    .epc-page * { box-sizing: border-box; }
    .epc-page > a, .epc-page .controls, .epc-page .titlebar, .epc-page .watermark { display:none !important; }
    .epc-page .epc-hero {
        position: relative; isolation: isolate; overflow: hidden;
        padding: clamp(24px,4vw,44px); margin-bottom: 22px;
        color: white; border: 1px solid rgba(130,165,230,.24); border-radius: 25px;
        background:
            radial-gradient(circle at 8% 20%, rgba(85,224,213,.15), transparent 27%),
            linear-gradient(120deg,#101b30 0%,#19355b 58%,#10434b 100%);
        box-shadow: 0 20px 55px rgba(0,0,0,.18);
    }
    .epc-page .epc-hero:after {
        content:"EPC"; position:absolute; z-index:-1; left:22px; bottom:-55px;
        font-size:clamp(100px,17vw,180px); line-height:1; font-weight:1000;
        letter-spacing:-8px; color:rgba(255,255,255,.035);
    }
    .epc-page .epc-kicker {
        display:inline-flex; align-items:center; gap:8px; margin-bottom:10px;
        padding:5px 11px; border-radius:30px; border:1px solid rgba(85,224,213,.25);
        background:rgba(85,224,213,.08); color:var(--epc-cyan); font-size:11px; font-weight:800;
    }
    .epc-page .epc-kicker:before { content:""; width:6px;height:6px;border-radius:50%;background:currentColor;box-shadow:0 0 12px currentColor; }
    .epc-page .epc-hero h1 { margin:0 0 8px; font-size:clamp(23px,3vw,35px); line-height:1.6; font-weight:900; }
    .epc-page .epc-hero p { max-width:720px; margin:0; color:#c2d3eb; line-height:2; font-size:13px; }
    .epc-page .epc-card {
        min-width:0; padding:clamp(15px,2.4vw,24px); margin-bottom:20px;
        border:1px solid var(--epc-line); border-radius:19px;
        background:linear-gradient(145deg,rgba(17,29,47,.98),rgba(12,22,37,.98));
        box-shadow:0 12px 35px rgba(0,0,0,.1);
    }
    .epc-page .epc-card h2 {
        display:flex; align-items:center; gap:10px; margin:0 0 10px;
        color:#f3f7ff; font-size:17px; font-weight:800;
    }
    .epc-page .epc-card h2:before { content:""; width:4px;height:20px;border-radius:5px;background:linear-gradient(var(--epc-cyan),var(--epc-blue)); }
    .epc-page .epc-muted { color:var(--epc-muted); font-size:12px; line-height:2; margin:0 0 15px; }
    .epc-page .epc-viewer {
        display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px;
        min-width:0; overflow:hidden; margin-top:15px;
    }
    .epc-page .epc-model {
        min-width:0; overflow:hidden; border:1px solid var(--epc-line);
        border-radius:15px; background:#080f1b;
    }
    .epc-page .epc-model iframe { display:block; width:100%; height:390px; border:0; }
    .epc-page .epc-model-label {
        padding:10px 13px; color:#c8d8ee; font-size:12px; font-weight:700;
        background:#132239; border-top:1px solid var(--epc-line);
    }
    .epc-page .epc-status {
        margin-top:13px; padding:11px 14px; color:#bfe9ef;
        background:rgba(85,224,213,.07); border:1px solid rgba(85,224,213,.15);
        border-radius:11px; overflow-wrap:anywhere; line-height:1.9; font-size:12px;
    }
    .epc-page .epc-search-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
    .epc-page .epc-field { min-width:0; }
    .epc-page .epc-field label { display:block; margin-bottom:7px; color:#cbd8eb; font-size:12px; font-weight:700; }
    .epc-page .epc-field input, .epc-page .epc-field select {
        width:100%; min-width:0; min-height:45px; padding:10px 12px;
        border:1px solid rgba(167,190,225,.2); border-radius:11px;
        background:#0a1423; color:var(--epc-text); font:inherit; font-size:12px; outline:none;
        transition:border-color .2s,box-shadow .2s;
    }
    .epc-page .epc-field select option { background:#101b2d; }
    .epc-page .epc-field input::placeholder { color:#687b96; }
    .epc-page .epc-field input:focus, .epc-page .epc-field select:focus {
        border-color:var(--epc-cyan); box-shadow:0 0 0 3px rgba(85,224,213,.1);
    }
    .epc-page .epc-actions { display:flex; flex-wrap:wrap; gap:9px; margin-top:17px; }
    .epc-page .epc-btn {
        display:inline-flex; align-items:center; justify-content:center; gap:7px;
        min-height:42px; padding:9px 16px; border:1px solid transparent; border-radius:11px;
        background:linear-gradient(135deg,var(--epc-cyan),#8daaff); color:#07121f;
        text-decoration:none; font:inherit; font-size:12px; font-weight:900; cursor:pointer;
        transition:transform .18s,filter .18s;
    }
    .epc-page .epc-btn:hover { color:#07121f; filter:brightness(1.08); transform:translateY(-1px); text-decoration:none; }
    .epc-page .epc-btn-secondary { color:#d5e2f5; background:#1a2a42; border-color:var(--epc-line); }
    .epc-page .epc-btn-secondary:hover { color:white; background:#243a57; }
    .epc-page .epc-stat-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:13px; margin-bottom:20px; }
    .epc-page .epc-stat {
        position:relative; overflow:hidden; padding:17px 19px; border:1px solid var(--epc-line);
        border-radius:16px; background:linear-gradient(140deg,#15263e,#0f1b2d);
    }
    .epc-page .epc-stat:after { content:""; position:absolute; width:85px;height:85px;left:-28px;bottom:-50px;border:1px solid rgba(85,224,213,.12);border-radius:50%; }
    .epc-page .epc-stat small { display:block; margin-bottom:4px; color:var(--epc-muted); font-size:11px; }
    .epc-page .epc-stat strong { color:#fff; font-size:24px; font-weight:900; }
    .epc-page .epc-section-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:11px; }
    .epc-page .epc-section-link {
        display:block; min-width:0; padding:15px; color:var(--epc-text);
        background:#0c1727; border:1px solid var(--epc-line); border-radius:14px;
        text-decoration:none; transition:transform .18s,border-color .18s,background .18s;
    }
    .epc-page .epc-section-link:before {
        content:"↙"; display:grid; place-items:center; float:left; width:27px;height:27px;
        color:var(--epc-cyan); background:rgba(85,224,213,.09); border-radius:9px;
    }
    .epc-page .epc-section-link:hover { color:white; background:#14263e; border-color:rgba(85,224,213,.4); transform:translateY(-2px); text-decoration:none; }
    .epc-page .epc-section-link strong { display:block; margin-bottom:4px; font-size:12px; }
    .epc-page .epc-section-link small { display:block; color:var(--epc-muted); font-size:10px; }
    .epc-page .epc-table-wrap { width:100%; overflow-x:auto; border:1px solid var(--epc-line); border-radius:13px; }
    .epc-page .epc-table { width:100%; min-width:760px; border-collapse:collapse; background:#0d1727; }
    .epc-page .epc-table th,.epc-page .epc-table td { text-align:right; padding:13px 14px; border-bottom:1px solid var(--epc-line); font-size:12px; vertical-align:middle; }
    .epc-page .epc-table th { color:#aebfda; background:#16263d; white-space:nowrap; font-size:11px; }
    .epc-page .epc-table tbody tr { transition:background .15s; }
    .epc-page .epc-table tbody tr:hover { background:rgba(107,147,255,.07); }
    .epc-page .epc-table tbody tr:last-child td { border-bottom:0; }
    .epc-page .epc-code { direction:ltr; unicode-bidi:plaintext; font-family:Consolas,monospace; white-space:nowrap; font-weight:800; color:var(--epc-cyan); }
    .epc-page .epc-price { color:#67dfaa; white-space:nowrap; font-weight:800; }
    .epc-page .epc-table td a { color:#8fb0ff; font-weight:700; }
    .epc-page .epc-table td a:hover { color:var(--epc-cyan); }
    .epc-page .epc-empty { padding:25px!important; text-align:center!important; color:var(--epc-muted); }
    .epc-page .epc-note { margin-top:12px; color:var(--epc-muted); font-size:11px; line-height:1.9; }

    .epc-page .epc-stat { display:flex; align-items:center; gap:13px; }
    .epc-page .epc-stat-icon { flex:0 0 44px; width:44px;height:44px;display:grid;place-items:center;border-radius:14px;background:rgba(85,224,213,.1);font-size:22px; }
    .epc-page .epc-stat-copy { min-width:0; }
    .epc-page .epc-stat-copy small { margin-bottom:2px; }
    .epc-page .epc-dashboard-grid { display:grid;grid-template-columns:minmax(0,1.25fr) minmax(260px,.75fr);gap:15px;margin-bottom:20px; }
    .epc-page .epc-chart-card { min-width:0;padding:20px;border:1px solid var(--epc-line);border-radius:19px;background:linear-gradient(145deg,#111e32,#0b1525); }
    .epc-page .epc-chart-head { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:17px; }
    .epc-page .epc-chart-head h2 { margin:0 0 4px;font-size:16px;color:#f3f7ff; }
    .epc-page .epc-chart-head p { margin:0;color:var(--epc-muted);font-size:11px;line-height:1.8; }
    .epc-page .epc-chart-badge { flex-shrink:0;padding:6px 9px;border-radius:9px;color:#8ee9df;background:rgba(85,224,213,.09);font-size:10px;font-weight:800; }
    .epc-page .epc-chart-layout { display:grid;grid-template-columns:150px minmax(0,1fr);align-items:center;gap:20px; }
    .epc-page .epc-donut { position:relative;width:145px;height:145px;border-radius:50%;background:conic-gradient(#55e0d5 0deg 360deg);display:grid;place-items:center; }
    .epc-page .epc-donut:after { content:"";position:absolute;inset:17px;border-radius:50%;background:#101b2d;border:1px solid var(--epc-line); }
    .epc-page .epc-donut-center { position:relative;z-index:1;text-align:center; }
    .epc-page .epc-donut-center strong { display:block;font-size:23px;color:#fff;font-weight:900; }
    .epc-page .epc-donut-center span { display:block;color:var(--epc-muted);font-size:10px; }
    .epc-page .epc-chart-legend { display:grid;gap:10px;min-width:0; }
    .epc-page .epc-legend-row { display:grid;grid-template-columns:9px minmax(0,1fr) auto;gap:8px;align-items:center;font-size:11px; }
    .epc-page .epc-legend-dot { width:8px;height:8px;border-radius:3px;background:var(--dot,#55e0d5); }
    .epc-page .epc-legend-name { color:#c9d7ea;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
    .epc-page .epc-legend-count { color:#fff;font-weight:800; }
    .epc-page .epc-bar-list { display:grid;gap:13px; }
    .epc-page .epc-bar-item { min-width:0; }
    .epc-page .epc-bar-label { display:flex;justify-content:space-between;gap:10px;margin-bottom:6px;color:#d7e3f4;font-size:11px; }
    .epc-page .epc-bar-label span:first-child { overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
    .epc-page .epc-bar-label strong { color:var(--epc-cyan); }
    .epc-page .epc-bar-track { height:7px;overflow:hidden;border-radius:20px;background:#1d2b41; }
    .epc-page .epc-bar-fill { display:block;width:0;height:100%;border-radius:20px;background:linear-gradient(90deg,#6b93ff,#55e0d5);transition:width .7s ease; }
    .epc-page .epc-chart-empty { padding:15px;border:1px dashed var(--epc-line);border-radius:12px;color:var(--epc-muted);font-size:12px;line-height:2; }
    .epc-page .epc-section-link { position:relative;overflow:hidden; }
    .epc-page .epc-section-link:before { content:none; }
    .epc-page .epc-category-top { display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:13px; }
    .epc-page .epc-category-icon { display:grid;place-items:center;width:43px;height:43px;border-radius:14px;background:linear-gradient(145deg,rgba(85,224,213,.15),rgba(107,147,255,.14));border:1px solid rgba(120,180,230,.14);font-size:22px; }
    .epc-page .epc-category-arrow { color:var(--epc-cyan);font-size:19px; }
    .epc-page .epc-category-footer { display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:12px;padding-top:10px;border-top:1px solid var(--epc-line); }
    .epc-page .epc-category-footer small { color:var(--epc-muted);font-size:10px; }
    .epc-page .epc-category-footer span { color:#9bb8ff;font-size:10px;font-weight:800; }
    .epc-page .epc-quick-tip { display:flex;gap:12px;align-items:flex-start;padding:16px;border-radius:15px;border:1px solid rgba(107,147,255,.2);background:linear-gradient(130deg,rgba(107,147,255,.09),rgba(85,224,213,.05)); }
    .epc-page .epc-quick-tip-icon { display:grid;place-items:center;flex:0 0 38px;width:38px;height:38px;border-radius:12px;background:rgba(107,147,255,.13);font-size:19px; }
    .epc-page .epc-quick-tip strong { display:block;margin-bottom:4px;color:#f2f6ff;font-size:12px; }
    .epc-page .epc-quick-tip p { margin:0;color:var(--epc-muted);font-size:11px;line-height:2; }

    @media(max-width:900px) {
        .epc-page .epc-section-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .epc-page .epc-dashboard-grid { grid-template-columns:1fr; }
        .epc-page .epc-search-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .epc-page .epc-model iframe { height:340px; }
    }
    @media(max-width:600px) {
        .epc-page .epc-hero { padding:22px 18px; border-radius:19px; }
        .epc-page .epc-hero h1 { font-size:22px; }
        .epc-page .epc-search-grid,.epc-page .epc-stat-grid { grid-template-columns:1fr; }
        .epc-page .epc-viewer { grid-template-columns:1fr; }
        .epc-page .epc-chart-layout { grid-template-columns:110px minmax(0,1fr);gap:14px; }
        .epc-page .epc-donut { width:108px;height:108px; }
        .epc-page .epc-donut:after { inset:13px; }
        .epc-page .epc-chart-card { padding:15px; }
        .epc-page .epc-model iframe { height:320px; }
        .epc-page .epc-card { padding:15px; }
        .epc-page .epc-section-grid { gap:8px; }
        .epc-page .epc-section-link { padding:11px; }
        .epc-page .epc-stat { padding:13px 16px; }
        .epc-page .epc-stat strong { font-size:21px; }
        .epc-page .epc-actions .epc-btn { flex:1 1 auto; }
    }
</style>

<div class="epc-page">
    <section class="epc-hero">
        <span class="epc-kicker">EPC · SMART PARTS FINDER</span>
        <h1>مرکز هوشمند قطعات چری و ام‌وی‌ام</h1>
        <p>از نمای سه‌بعدی تا جست‌وجوی کد فنی؛ قطعه مناسب را سریع‌تر پیدا کن، دسته‌بندی‌ها را بررسی کن و به اطلاعات هر قطعه دسترسی داشته باش.</p>
    </section>

    <section class="epc-card">
        <h2>نمای تعاملی قطعات</h2>
        <p class="epc-muted">با ماوس یا لمس صفحه، مدل را بچرخان و بزرگ‌نمایی کن. برای جست‌وجوی مستقیم، روی بخش‌های قابل انتخاب مدل کلیک کن.</p>

        <div class="epc-viewer">
            <div class="epc-model">
                <iframe id="chery-3d-viewer" title="مدل سه‌بعدی قطعات موتوری" frameborder="0"
                    allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true"
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    src="https://sketchfab.com/models/3026bd87ca3945d6829d24c86ce695f0/embed?autostart=1&preload=1&ui_infos=0&ui_controls=0&ui_general_controls=0&ui_help=0&ui_settings=0&ui_inspector=0&ui_annotations=0&ui_stop=0&ui_vr=0&ui_ar=0&ui_hint=0&ui_watermark=0&ui_watermark_link=0&ui_loading=0"></iframe>
                <div class="epc-model-label">قطعات موتوری</div>
            </div>
            <div class="epc-model">
                <iframe id="chery-3d-viewer1" title="مدل سه‌بعدی قطعات بدنه" frameborder="0"
                    allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true"
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    src="https://sketchfab.com/models/c4bd63d950cb49a59b3807e82ebd4178/embed?autostart=1&preload=1&ui_infos=0&ui_controls=0&ui_general_controls=0&ui_help=0&ui_settings=0&ui_inspector=0&ui_annotations=0&ui_stop=0&ui_vr=0&ui_ar=0&ui_hint=0&ui_watermark=0&ui_watermark_link=0&ui_loading=0"></iframe>
                <div class="epc-model-label">قطعات بدنه</div>
            </div>
        </div>
        <div id="epc-selection-status" class="epc-status" role="status" aria-live="polite">در حال آماده‌سازی مدل سه‌بعدی...</div>
    </section>

    <section class="epc-card">
        <h2>جست‌وجوی قطعات</h2>
        <p class="epc-muted">با کد فنی، نام قطعه، مدل خودرو یا گروه قطعات جست‌وجو کن.</p>
        <form action="{{ route('catalog.index') }}" method="GET">
            <div class="epc-search-grid">
                <div class="epc-field">
                    <label for="q">نام یا کد فنی</label>
                    <input id="q" name="q" type="search" value="{{ $q ?? request('q', '') }}" placeholder="مثلاً میل سوپاپ یا کد فنی">
                </div>
                <div class="epc-field">
                    <label for="vehicle">مدل خودرو</label>
                    <select id="vehicle" name="vehicle">
                        <option value="">همه خودروها</option>
                        @foreach ($vehicles as $vehicleName)
                            <option value="{{ $vehicleName }}" @selected(request('vehicle') == $vehicleName)>{{ $vehicleName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="epc-field">
                    <label for="category">گروه قطعه</label>
                    <select id="category" name="category">
                        <option value="">همه گروه‌ها</option>
                        @foreach ($categories as $categoryName)
                            <option value="{{ $categoryName }}" @selected(request('category') == $categoryName)>{{ $categoryName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="epc-actions">
                <button type="submit" class="epc-btn">⌕ جست‌وجوی قطعه</button>
                <a href="{{ route('catalog.index') }}" class="epc-btn epc-btn-secondary">پاک‌کردن فیلترها</a>
            </div>
        </form>
    </section>

    <section class="epc-stat-grid">
        <div class="epc-stat"><span class="epc-stat-icon">🧩</span><div class="epc-stat-copy"><small>کل قطعات مطابق فیلتر</small><strong>{{ number_format($parts->total()) }}</strong></div></div>
        <div class="epc-stat"><span class="epc-stat-icon">🗂️</span><div class="epc-stat-copy"><small>دسته‌بندی‌های موجود</small><strong>{{ number_format($categories->count()) }}</strong></div></div>
        <div class="epc-stat"><span class="epc-stat-icon">🚘</span><div class="epc-stat-copy"><small>مدل‌های خودرو</small><strong>{{ number_format($vehicles->count()) }}</strong></div></div>
    </section>

    @php
        $epcPageCollection = $parts->getCollection();
        $epcCategoryStats = collect($categories)->map(function ($categoryName) use ($epcPageCollection) {
            return [
                'name' => $categoryName,
                'count' => $epcPageCollection->where('category', $categoryName)->count(),
            ];
        })->sortByDesc('count')->values();
        $epcCategoryColors = ['#55e0d5', '#6b93ff', '#f6b85b', '#c792ea', '#ff7b8b', '#78dba9', '#7dc8ff'];
        $epcTopCategories = $epcCategoryStats->take(5);
        $epcMaxCategoryCount = max(1, (int) $epcTopCategories->max('count'));
        $epcPageCount = $epcPageCollection->count();
        $epcChartCount = (int) $epcTopCategories->sum('count');
    @endphp

    <section class="epc-dashboard-grid" aria-label="آمار و نمودار دسته‌بندی‌ها">
        <div class="epc-chart-card">
            <div class="epc-chart-head">
                <div><h2>ترکیب دسته‌بندی‌ها</h2><p>سهم ۵ دسته پرتکرار در همین صفحه</p></div>
                <span class="epc-chart-badge">LIVE · صفحه جاری</span>
            </div>
            @if ($epcPageCount > 0)
                <div class="epc-chart-layout">
                    <div class="epc-donut" id="epc-category-donut" aria-label="نمودار سهم دسته‌بندی‌ها">
                        <div class="epc-donut-center"><strong>{{ number_format($epcChartCount) }}</strong><span>قطعه در ۵ دسته برتر</span></div>
                    </div>
                    <div class="epc-chart-legend" id="epc-category-legend">
                        @foreach ($epcTopCategories as $index => $stat)
                            <div class="epc-legend-row" data-count="{{ $stat['count'] }}" data-color="{{ $epcCategoryColors[$index % count($epcCategoryColors)] }}">
                                <span class="epc-legend-dot" style="--dot:{{ $epcCategoryColors[$index % count($epcCategoryColors)] }}"></span>
                                <span class="epc-legend-name" title="{{ $stat['name'] }}">{{ $stat['name'] }}</span>
                                <span class="epc-legend-count">{{ number_format($stat['count']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="epc-chart-empty">برای نمایش نمودار، ابتدا قطعه‌ای پیدا کن یا فیلترها را تغییر بده.</div>
            @endif
        </div>
        <div class="epc-chart-card">
            <div class="epc-chart-head">
                <div><h2>دسته‌های پرتکرار</h2><p>بر اساس قطعات موجود در صفحه فعلی</p></div>
                <span class="epc-chart-badge">TOP 5</span>
            </div>
            @if ($epcPageCount > 0)
                <div class="epc-bar-list" id="epc-category-bars">
                    @foreach ($epcTopCategories as $stat)
                        <div class="epc-bar-item" data-count="{{ $stat['count'] }}">
                            <div class="epc-bar-label"><span title="{{ $stat['name'] }}">{{ $stat['name'] }}</span><strong>{{ number_format($stat['count']) }}</strong></div>
                            <div class="epc-bar-track"><span class="epc-bar-fill" style="width:{{ round(($stat['count'] / $epcMaxCategoryCount) * 100) }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="epc-chart-empty">هنوز داده‌ای برای تحلیل این صفحه وجود ندارد.</div>
            @endif
            <div class="epc-note">نکته: این نمودارها فقط داده‌های صفحه فعلی را تحلیل می‌کنند، نه کل دیتابیس.</div>
        </div>
    </section>

    <div class="epc-quick-tip" style="margin-bottom:20px">
        <span class="epc-quick-tip-icon">💡</span>
        <div><strong>جست‌وجوی سریع‌تر</strong><p>اگر کد فنی قطعه را داری، آن را در کادر جست‌وجو وارد کن. برای جست‌وجوی عمومی‌تر، بخشی از نام قطعه یا مدل خودرو را بنویس.</p></div>
    </div>

    <section class="epc-card">
        <h2>انتخاب گروه قطعات</h2>
        <p class="epc-muted">برای مشاهده قطعات یک گروه، دسته‌بندی موردنظر را انتخاب کن.</p>
        <div class="epc-section-grid">
            @forelse ($categories as $categoryName)
              @php
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

    $epcCatLower = mb_strtolower((string) $categoryName);
    $epcCatIcon = '🔩';

    foreach ($icons as $keyword => $icon) {
        if (str_contains($epcCatLower, $keyword)) {
            $epcCatIcon = $icon;
            break;
        }
    }

    $epcCatCount = $epcPageCollection
        ->where('category', $categoryName)
        ->count();
@endphp
                <a class="epc-section-link" href="{{ route('catalog.category', ['category' => $categoryName, 'vehicle' => request('vehicle', '')]) }}">
                    <div class="epc-category-top"><span class="epc-category-icon">{{ $epcCatIcon }}</span><span class="epc-category-arrow">↗</span></div>
                    <strong>{{ $categoryName }}</strong>
                    <div class="epc-category-footer"><small>{{ number_format($epcCatCount) }} قطعه در این صفحه</small><span>مشاهده گروه ←</span></div>
                </a>
            @empty
                <p class="epc-muted">هنوز دسته‌بندی‌ای در دیتابیس ثبت نشده است.</p>
            @endforelse
        </div>
    </section>

    <section class="epc-card">
        <h2>فهرست قطعات</h2>
        <p class="epc-muted">تعداد قطعات پیدا شده: {{ number_format($parts->total()) }}</p>
        <div class="epc-table-wrap">
            <table class="epc-table">
                <thead>
                    <tr>
                        <th>کد فنی</th><th>نام قطعه</th><th>گروه</th><th>مدل خودرو</th><th>قیمت شرکت</th><th>جزئیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parts as $part)
                        <tr>
                            <td class="epc-code">{{ $part->technical_code ?: '—' }}</td>
                            <td>{{ $part->name_fa ?: 'بدون نام' }}</td>
                            <td>{{ $part->category ?: 'دسته‌بندی نشده' }}</td>
                            <td>{{ $part->car_model_raw ?: 'ثبت نشده' }}</td>
                            <td class="epc-price">
                                @if ($part->price !== null)
                                    {{ number_format((float) $part->price) }}
                                @else
                                    ثبت نشده
                                @endif
                            </td>
                            <td><a href="{{ route('catalog.show', $part->id) }}">مشاهده قطعه ←</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="epc-empty">قطعه‌ای پیدا نشد. فیلترها یا عبارت جست‌وجو را تغییر بده.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:18px">{{ $parts->withQueryString()->links() }}</div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const donut = document.getElementById('epc-category-donut');
    const legend = document.getElementById('epc-category-legend');
    if (donut && legend) {
        const rows = Array.from(legend.querySelectorAll('.epc-legend-row'));
        const total = rows.reduce((sum, row) => sum + Number(row.dataset.count || 0), 0);
        if (total > 0) {
            let angle = 0;
            const segments = rows.map(row => {
                const value = Number(row.dataset.count || 0);
                const next = angle + (value / total) * 360;
                const color = row.dataset.color || '#55e0d5';
                const segment = color + ' ' + angle + 'deg ' + next + 'deg';
                angle = next;
                return segment;
            });
            donut.style.background = 'conic-gradient(' + segments.join(',') + ')';
        }
    }
});
</script>

<script src="https://static.sketchfab.com/api/sketchfab-viewer-1.12.1.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const status = document.getElementById('epc-selection-status');

    if (!window.Sketchfab) {
        status.textContent = 'API بارگذاری نشد. اتصال اینترنت یا دسترسی به Sketchfab را بررسی کن.';
        return;
    }

    const viewers = [
        {
            iframeId: 'chery-3d-viewer',
            uid: '3026bd87ca3945d6829d24c86ce695f0',
            mappings: {
                '642': { name: 'میل سوپاپ', category: '', subcategory: '', vehicle: '', q: 'میل سوپاپ' }
            }
        },
        {
            iframeId: 'chery-3d-viewer1',
            uid: 'c4bd63d950cb49a59b3807e82ebd4178',
            mappings: {
                '819': { name: 'درب عقب سمت راست', category: '', subcategory: '', vehicle: '', q: 'درب عقب سمت راست' }
            }
        }
    ];

    let readyCount = 0;

    viewers.forEach(function (viewer) {
        const iframe = document.getElementById(viewer.iframeId);
        if (!iframe) return;

        const client = new Sketchfab(iframe);
        client.init(viewer.uid, {
            autostart: 1,
            preload: 1,
            ui_infos: 0,
            ui_controls: 0,
            ui_general_controls: 0,
            ui_help: 0,
            ui_settings: 0,
            ui_inspector: 0,
            ui_annotations: 0,
            ui_stop: 0,
            ui_vr: 0,
            ui_ar: 0,
            ui_hint: 0,
            ui_watermark: 0,
            ui_watermark_link: 0,
            ui_loading: 0,
            success: function (api) {
                api.start();
                api.addEventListener('viewerready', function () {
                    readyCount++;
                    status.textContent = readyCount === viewers.length
                        ? 'مدل‌های سه‌بعدی آماده‌اند. روی قطعه موردنظر کلیک کن.'
                        : 'یکی از مدل‌ها آماده است؛ در حال بارگذاری مدل دیگر...';
                });

                api.addEventListener('click', function (info) {
                    if (!info || info.instanceID == null) {
                        status.textContent = 'جزء قابل شناسایی انتخاب نشد.';
                        return;
                    }

                    const instanceId = String(info.instanceID);
                    const selected = viewer.mappings[instanceId];

                    if (!selected) {
                        status.textContent = 'شناسه ' + instanceId + ' هنوز به جست‌وجوی قطعات متصل نشده است.';
                        return;
                    }

                    status.textContent = 'جست‌وجوی قطعه: ' + selected.q;
                    const params = new URLSearchParams({
                        vehicle: selected.vehicle,
                        category: selected.category,
                        subcategory: selected.subcategory,
                        q: selected.q
                    });

                    window.location.href = '{{ url('/catalog/category') }}?' + params.toString();
                }, { pick: 'fast' });
            },
            error: function () {
                status.textContent = 'راه‌اندازی یکی از مدل‌ها ناموفق بود. دسترسی مدل یا تنظیمات مرورگر را بررسی کن.';
            }
        });
    });
});
</script>
@endsection
