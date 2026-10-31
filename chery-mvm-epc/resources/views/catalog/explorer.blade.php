
@extends('layouts.app')

@section('title', 'کاتالوگ تصویری تیگو ۷ پرو')

@section('content')

<section class="hero">
    <h1>کاتالوگ تصویری تیگو ۷ پرو</h1>
    <p>یک بخش از خودرو را انتخاب کن تا قطعات مرتبط را ببینی.</p>
</section>

<div class="grid-2">

    <section class="card">
        <h2>نمای خودرو</h2>

        <div id="vehicle-viewer"
             style="position:relative; overflow:hidden; border-radius:14px;
                    background:#eef2f7; min-height:260px;">

            <img
                id="vehicle-image"
                src="{{ asset('images/tiggo-7-pro.jpg') }}"
                alt="تصویر تیگو ۷ پرو"
                style="display:block; width:100%; height:auto;
                       object-fit:contain; transition:transform .12s ease;
                       transform-origin:center;"
                onerror="this.style.display='none';
                         document.getElementById('image-fallback').style.display='flex';"
            >

            <div id="image-fallback"
                 style="display:none; min-height:260px; padding:20px;
                        align-items:center; justify-content:center;
                        text-align:center; color:#64748b;">
                تصویر خودرو پیدا نشد.
                <br>
                فایل tiggo-7-pro.jpg را در پوشه public/images قرار بده.
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary"
                    onclick="rotateVehicle(-15)">
                ↶ چرخش نمایشی
            </button>

            <button type="button" class="btn btn-secondary"
                    onclick="rotateVehicle(15)">
                چرخش نمایشی ↷
            </button>

            <button type="button" class="btn btn-secondary"
                    onclick="resetVehicle()">
                بازنشانی
            </button>
        </div>

        <p class="muted small">
            این چرخش فقط برای پیش‌نمایش تصویر است؛
            برای مشاهده واقعی زوایای مختلف، به چند تصویر یا مدل سه‌بعدی نیاز داریم.
        </p>
    </section>

    <section class="card">
        <h2>گروه‌های قطعات</h2>
        <p class="muted">
            گروه موردنظر را انتخاب کن تا فهرست قطعات آن باز شود.
        </p>

        <div style="display:grid; gap:10px;">
            @foreach ($sections as $section)
                <div style="border:1px solid #e2e8f0;
                            border-radius:12px; padding:13px;
                            display:flex; align-items:center;
                            justify-content:space-between; gap:12px;">

                    <div>
                        <strong>{{ $section['title'] }}</strong>

                        <div class="muted small">
                            @if ($section['category'])
                                دسته دیتابیس:
                                {{ $section['category'] }}
                            @else
                                هنوز به دسته دیتابیس متصل نشده
                            @endif
                        </div>
                    </div>

                    @if ($section['category'])
                        <a class="btn"
                           href="{{ route('catalog.category', [
                               'category' => $section['category'],
                               'vehicle' => 'تیگو 7 پرو'
                           ]) }}">
                            مشاهده
                        </a>
                    @else
                        <span class="badge">نیاز به تنظیم</span>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

</div>

<script>
    let vehicleRotation = 0;

    function rotateVehicle(degrees) {
        vehicleRotation += degrees;

        const image = document.getElementById('vehicle-image');

        image.style.transform =
            'perspective(900px) rotateY(' + vehicleRotation + 'deg)';
    }

    function resetVehicle() {
        vehicleRotation = 0;

        document.getElementById('vehicle-image').style.transform =
            'none';
    }
</script>

@endsection