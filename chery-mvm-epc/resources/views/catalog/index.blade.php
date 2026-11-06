
@extends('layouts.app')

@section('title', 'کاتالوگ قطعات | PartCar')
@section('page-title', 'کاتالوگ قطعات')

@section('content')
<style>
.pc-catalog{color:#17243a}
.pc-catalog *{box-sizing:border-box}
.pc-page-head{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:22px}
.pc-page-head h1{font-size:23px;font-weight:800;margin:0;color:#172943}
.pc-page-head p{font-size:12px;color:#8290a4;margin:5px 0 0}
.pc-eyebrow{font-size:10px;font-weight:800;color:#087bfa;margin-bottom:5px}
.pc-panel{background:#fff;border:1px solid #e5eaf1;border-radius:12px;padding:20px;margin-bottom:17px;box-shadow:0 2px 10px rgba(20,40,70,.025)}
.pc-panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px}
.pc-panel-head h2{font-size:14px;font-weight:800;color:#1b2c46;margin:0}
.pc-panel-head p{font-size:11px;color:#8a97a9;margin:4px 0 0}
.pc-muted{font-size:11px;color:#8290a4}
.pc-search-panel{border-top:3px solid #087bfa}
.pc-search-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:13px}
.pc-field label{display:block;font-size:11px;font-weight:700;color:#43536b;margin-bottom:7px}
.pc-field input,.pc-field select{width:100%;height:42px;border:1px solid #dfe6ef;border-radius:8px;background:#fff;padding:8px 11px;font:inherit;font-size:11px;color:#263750}
.pc-field input:focus,.pc-field select:focus{outline:0;border-color:#087bfa;box-shadow:0 0 0 3px #087bfa14}
.pc-actions{display:flex;gap:8px;margin-top:15px;flex-wrap:wrap}
.pc-action{display:inline-flex;align-items:center;justify-content:center;min-height:39px;padding:8px 16px;border-radius:8px;background:#087bfa;color:#fff;font:700 11px Vazirmatn;border:0;cursor:pointer;text-decoration:none}
.pc-action:hover{background:#0667d6;color:#fff}
.pc-action-light{background:#f0f5fb;color:#53657e}
.pc-action-light:hover{background:#e5edf7;color:#263d5c}
.pc-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:17px}
.pc-stat{display:flex;align-items:center;gap:13px;background:#fff;border:1px solid #e5eaf1;border-radius:11px;padding:15px}
.pc-stat-icon{width:40px;height:40px;display:grid;place-items:center;border-radius:9px;background:#edf5ff;color:#087bfa;font-size:19px}
.pc-stat small{display:block;color:#8290a4;font-size:10px}
.pc-stat strong{display:block;color:#172943;font-size:21px;font-weight:800;margin-top:2px}
.pc-model-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.pc-model-box{border:1px solid #e5eaf1;border-radius:10px;overflow:hidden;background:#f7f9fc}
.pc-model-box iframe{display:block;width:100%;height:330px;border:0}
.pc-model-label{padding:10px 13px;background:#fff;border-top:1px solid #e5eaf1;font-size:11px;font-weight:700;color:#40536e}
.pc-model-status{margin-top:12px;background:#f0f6ff;border:1px solid #dceaff;border-radius:8px;padding:10px 13px;color:#42648d;font-size:11px}
.pc-category-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.pc-category{display:block;border:1px solid #e5eaf1;border-radius:9px;padding:13px;color:#263750;background:#fff;transition:.15s}
.pc-category:hover{border-color:#9ccaff;background:#fafdff;color:#087bfa;transform:translateY(-1px)}
.pc-category-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:11px}
.pc-category-icon{width:35px;height:35px;display:grid;place-items:center;border-radius:8px;background:#edf5ff;color:#087bfa;font-size:18px}
.pc-category-arrow{color:#087bfa}
.pc-category strong{font-size:12px;display:block}
.pc-category small{font-size:10px;color:#8996a8;display:block;margin-top:5px}
.pc-chart-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.pc-chart-row{display:flex;align-items:center;gap:9px;margin:12px 0;font-size:11px}
.pc-chart-name{width:105px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#596a81}
.pc-chart-track{height:7px;background:#edf1f6;border-radius:10px;flex:1;overflow:hidden}
.pc-chart-fill{height:100%;background:#087bfa;border-radius:10px}
.pc-chart-count{width:30px;text-align:left;font-weight:700;color:#40536e}
.pc-table-wrap{overflow-x:auto;border:1px solid #e5eaf1;border-radius:9px}
.pc-table{width:100%;min-width:720px;border-collapse:collapse}
.pc-table th{background:#f6f8fb;color:#687991;font-size:10px;font-weight:800;padding:12px;text-align:right;white-space:nowrap}
.pc-table td{padding:12px;border-top:1px solid #edf0f5;font-size:11px;color:#35465e}
.pc-table tbody tr:hover{background:#f9fbfe}
.pc-table .technical-code{direction:ltr;unicode-bidi:plaintext;font-family:Consolas,monospace;color:#087bfa;font-weight:700}
.pc-table .price{font-weight:800;color:#159765;white-space:nowrap}
.pc-table a{color:#087bfa;font-weight:700}
.pc-table a:hover{text-decoration:underline}
.pc-empty{text-align:center!important;padding:30px!important;color:#8996a8!important}
.pc-pagination{margin-top:16px;overflow-x:auto}
@media(max-width:900px){
 .pc-search-grid{grid-template-columns:1fr 1fr}
 .pc-category-grid{grid-template-columns:repeat(2,1fr)}
 .pc-chart-grid{grid-template-columns:1fr}
}
@media(max-width:600px){
 .pc-page-head{align-items:flex-start}
 .pc-page-head h1{font-size:19px}
 .pc-panel{padding:14px}
 .pc-search-grid,.pc-model-grid{grid-template-columns:1fr}
 .pc-stats{grid-template-columns:1fr}
 .pc-model-box iframe{height:280px}
 .pc-category-grid{grid-template-columns:1fr 1fr}
 .pc-stat{padding:12px}
}
</style>

<div class="pc-catalog">

    <div class="pc-page-head">
        <div>
            <div class="pc-eyebrow">PARTCAR / PARTS CATALOG</div>
            <h1>کاتالوگ قطعات</h1>
            <p>جست‌وجو، شناسایی و بررسی قطعات خودروهای چینی در یک محیط تخصصی</p>
        </div>
    </div>

    {{-- جست‌وجوی اصلی --}}
    <section class="pc-panel pc-search-panel">
        <div class="pc-panel-head">
            <div>
                <h2>جست‌وجوی قطعه</h2>
                <p>نام قطعه، کد فنی یا مدل خودرو را وارد کنید.</p>
            </div>
        </div>

        <form action="{{ route('catalog.index') }}" method="GET">
            <div class="pc-search-grid">
                <div class="pc-field">
                    <label for="q">نام قطعه یا کد فنی</label>
                    <input id="q" name="q" type="search"
                           value="{{ $q ?? request('q', '') }}"
                           placeholder="مثلاً فیلتر روغن یا کد فنی">
                </div>

                <div class="pc-field">
                    <label for="vehicle">مدل خودرو</label>
                    <select id="vehicle" name="vehicle">
                        <option value="">همه خودروها</option>
                        @foreach ($vehicles as $vehicleName)
                            <option value="{{ $vehicleName }}" @selected(request('vehicle') == $vehicleName)>
                                {{ $vehicleName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pc-field">
                    <label for="category">دسته‌بندی قطعه</label>
                    <select id="category" name="category">
                        <option value="">همه دسته‌بندی‌ها</option>
                        @foreach ($categories as $categoryName)
                            <option value="{{ $categoryName }}" @selected(request('category') == $categoryName)>
                                {{ $categoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pc-actions">
                <button type="submit" class="pc-action">⌕ جست‌وجوی قطعات</button>
                <a href="{{ route('catalog.index') }}" class="pc-action pc-action-light">حذف فیلترها</a>
            </div>
        </form>
    </section>

    {{-- آمار --}}
    <div class="pc-stats">
        <div class="pc-stat">
            <div class="pc-stat-icon">▦</div>
            <div>
                <small>قطعات پیدا شده</small>
                <strong>{{ number_format($parts->total()) }}</strong>
            </div>
        </div>
        <div class="pc-stat">
            <div class="pc-stat-icon">▤</div>
            <div>
                <small>دسته‌بندی‌ها</small>
                <strong>{{ number_format($categories->count()) }}</strong>
            </div>
        </div>
        <div class="pc-stat">
            <div class="pc-stat-icon">🚘</div>
            <div>
                <small>مدل‌های خودرو</small>
                <strong>{{ number_format($vehicles->count()) }}</strong>
            </div>
        </div>
    </div>

    {{-- مدل‌های سه‌بعدی --}}
    <section class="pc-panel">
        <div class="pc-panel-head">
            <div>
                <h2>کاتالوگ تعاملی سه‌بعدی</h2>
                <p>مدل را بچرخانید و برای جست‌وجوی قطعات روی بخش‌های قابل انتخاب کلیک کنید.</p>
            </div>
            <span class="pc-muted">3D EXPLORER</span>
        </div>

        <div class="pc-model-grid">
            <div class="pc-model-box">
                <iframe id="chery-3d-viewer" title="مدل سه‌بعدی قطعات موتوری"
                    allowfullscreen
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    src="https://sketchfab.com/models/3026bd87ca3945d6829d24c86ce695f0/embed?autostart=1&preload=1&ui_infos=0&ui_controls=0&ui_general_controls=0&ui_help=0&ui_settings=0&ui_inspector=0&ui_annotations=0&ui_stop=0&ui_vr=0&ui_ar=0&ui_hint=0&ui_watermark=0&ui_watermark_link=0&ui_loading=0"></iframe>
                <div class="pc-model-label">مدل سه‌بعدی قطعات موتوری</div>
            </div>

            <div class="pc-model-box">
                <iframe id="chery-3d-viewer1" title="مدل سه‌بعدی قطعات بدنه"
                    allowfullscreen
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    src="https://sketchfab.com/models/c4bd63d950cb49a59b3807e82ebd4178/embed?autostart=1&preload=1&ui_infos=0&ui_controls=0&ui_general_controls=0&ui_help=0&ui_settings=0&ui_inspector=0&ui_annotations=0&ui_stop=0&ui_vr=0&ui_ar=0&ui_hint=0&ui_watermark=0&ui_watermark_link=0&ui_loading=0"></iframe>
                <div class="pc-model-label">مدل سه‌بعدی قطعات بدنه</div>
            </div>
        </div>

        <div id="epc-selection-status" class="pc-model-status" role="status" aria-live="polite">
            در حال آماده‌سازی مدل‌های سه‌بعدی...
        </div>
    </section>

    @php
        $epcPageCollection = $parts->getCollection();

        $epcCategoryStats = collect($categories)->map(function ($categoryName) use ($epcPageCollection) {
            return [
                'name' => $categoryName,
                'count' => $epcPageCollection->where('category', $categoryName)->count(),
            ];
        })->sortByDesc('count')->values();

        $epcTopCategories = $epcCategoryStats->take(5);
        $epcMaxCategoryCount = max(1, (int) $epcTopCategories->max('count'));
        $epcPageCount = $epcPageCollection->count();
    @endphp

    {{-- تحلیل دسته‌بندی‌ها --}}
    <section class="pc-panel">
        <div class="pc-panel-head">
            <div>
                <h2>تحلیل دسته‌بندی قطعات</h2>
                <p>توزیع قطعات در نتایج صفحه جاری</p>
            </div>
            <span class="pc-muted">TOP CATEGORIES</span>
        </div>

        @if($epcPageCount > 0)
            <div class="pc-chart-grid">
                <div>
                    @foreach($epcTopCategories as $stat)
                        <div class="pc-chart-row">
                            <span class="pc-chart-name" title="{{ $stat['name'] }}">{{ $stat['name'] }}</span>
                            <div class="pc-chart-track">
                                <div class="pc-chart-fill" style="width:{{ round(($stat['count'] / $epcMaxCategoryCount) * 100) }}%"></div>
                            </div>
                            <span class="pc-chart-count">{{ number_format($stat['count']) }}</span>
                        </div>
                    @endforeach
                </div>
                <div style="background:#f7f9fc;border-radius:9px;padding:17px">
                    <div class="pc-muted">تعداد قطعات در ۵ دسته برتر این صفحه</div>
                    <div style="font-size:27px;font-weight:800;color:#087bfa;margin-top:8px">
                        {{ number_format((int)$epcTopCategories->sum('count')) }}
                    </div>
                    <div class="pc-muted">این آمار مربوط به صفحه جاری است، نه کل دیتابیس.</div>
                </div>
            </div>
        @else
            <div class="pc-muted">برای نمایش تحلیل، ابتدا نتایج جست‌وجو را مشاهده کنید.</div>
        @endif
    </section>

    {{-- دسته‌بندی‌ها --}}
    <section class="pc-panel">
        <div class="pc-panel-head">
            <div>
                <h2>دسته‌بندی قطعات</h2>
                <p>برای مشاهده فهرست قطعات هر گروه، دسته‌بندی موردنظر را انتخاب کنید.</p>
            </div>
            <span class="pc-muted">{{ number_format($categories->count()) }} گروه</span>
        </div>

        <div class="pc-category-grid">
            @forelse($categories as $categoryName)
                @php
                    $icons = [
                        'موتور' => '⚙',
                        'موتوری' => '⚙',
                        'بدنه' => '🚘',
                        'برق' => 'ϟ',
                        'سنسور' => '◉',
                        'ترمز' => '◈',
                        'گیربکس' => '⚙',
                        'تعلیق' => '⌁',
                        'جلوبندی' => '⌁',
                        'چراغ' => '☼',
                        'خنک' => '❄',
                        'داخلی' => '▣',
                        'کابین' => '▣',
                        'فیلتر' => '▤',
                        'سوخت' => '⛽',
                        'اگزوز' => '♨',
                    ];

                    $epcCatIcon = '⚙';
                    foreach($icons as $keyword => $icon) {
                        if(str_contains(mb_strtolower((string)$categoryName), $keyword)) {
                            $epcCatIcon = $icon;
                            break;
                        }
                    }

                    $epcCatCount = $epcPageCollection->where('category', $categoryName)->count();
                @endphp

                <a class="pc-category"
                   href="{{ route('catalog.category', ['category' => $categoryName, 'vehicle' => request('vehicle', '')]) }}">
                    <div class="pc-category-top">
                        <span class="pc-category-icon">{{ $epcCatIcon }}</span>
                        <span class="pc-category-arrow">↗</span>
                    </div>
                    <strong>{{ $categoryName }}</strong>
                    <small>{{ number_format($epcCatCount) }} قطعه در صفحه جاری</small>
                </a>
            @empty
                <p class="pc-muted">هنوز دسته‌بندی‌ای ثبت نشده است.</p>
            @endforelse
        </div>
    </section>

    {{-- جدول قطعات --}}
    <section class="pc-panel">
        <div class="pc-panel-head">
            <div>
                <h2>فهرست قطعات</h2>
                <p>نتایج مطابق فیلترها و عبارت جست‌وجوی شما</p>
            </div>
            <span class="pc-muted">{{ number_format($parts->total()) }} نتیجه</span>
        </div>

        <div class="pc-table-wrap">
            <table class="pc-table">
                <thead>
                    <tr>
                        <th>کد فنی</th>
                        <th>نام قطعه</th>
                        <th>دسته‌بندی</th>
                        <th>مدل خودرو</th>
                        <th>قیمت شرکت</th>
                        <th>جزئیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                        <tr>
                            <td class="technical-code">{{ $part->technical_code ?: '—' }}</td>
                            <td><strong>{{ $part->name_fa ?: 'بدون نام' }}</strong></td>
                            <td>{{ $part->category ?: '—' }}</td>
                            <td>{{ $part->car_model_raw ?: '—' }}</td>
                            <td class="price">
                                @if($part->price !== null)
                                    {{ number_format((float)$part->price) }}
                                @else
                                    ثبت نشده
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('catalog.show', $part->id) }}">مشاهده جزئیات ←</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pc-empty">
                                قطعه‌ای پیدا نشد. عبارت جست‌وجو یا فیلترها را تغییر دهید.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pc-pagination">
            {{ $parts->withQueryString()->links() }}
        </div>
    </section>

</div>

<script src="https://static.sketchfab.com/api/sketchfab-viewer-1.12.1.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const status = document.getElementById('epc-selection-status');

    if (!window.Sketchfab) {
        status.textContent = 'API مدل سه‌بعدی بارگذاری نشد. اتصال اینترنت را بررسی کنید.';
        return;
    }

    const viewers = [
        {
            iframeId: 'chery-3d-viewer',
            uid: '3026bd87ca3945d6829d24c86ce695f0',
            mappings: {
                '642': {
                    name: 'میل سوپاپ',
                    category: '',
                    subcategory: '',
                    vehicle: '',
                    q: 'میل سوپاپ'
                }
            }
        },
        {
            iframeId: 'chery-3d-viewer1',
            uid: 'c4bd63d950cb49a59b3807e82ebd4178',
            mappings: {
                '819': {
                    name: 'درب عقب سمت راست',
                    category: '',
                    subcategory: '',
                    vehicle: '',
                    q: 'درب عقب سمت راست'
                }
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
                        ? 'مدل‌های سه‌بعدی آماده‌اند. روی قطعه موردنظر کلیک کنید.'
                        : 'یکی از مدل‌ها آماده است؛ در حال بارگذاری مدل دیگر...';
                });

                api.addEventListener('click', function (info) {
                    if (!info || info.instanceID == null) {
                        status.textContent = 'جزء قابل شناسایی انتخاب نشد.';
                        return;
                    }

                    const selected = viewer.mappings[String(info.instanceID)];

if (!selected) {
    status.textContent = 'این قطعه هنوز به جست‌وجوی کاتالوگ متصل نشده است.\n' +
        JSON.stringify(info, null, 2);

    console.log('Part Info:', info);
    return;
}

                    status.textContent = 'در حال جست‌وجوی ' + selected.name + '...';

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
                status.textContent = 'راه‌اندازی مدل سه‌بعدی ناموفق بود. دسترسی مدل یا مرورگر را بررسی کنید.';
            }
        });
    });
});
</script>
@endsection
