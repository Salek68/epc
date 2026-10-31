```blade
@extends('layouts.app')

@section('title', ($part->name_fa ?: 'جزئیات قطعه') . ' | CHERY – MVM EPC')

@section('content')

<div class="form-actions" style="margin-top:0; margin-bottom:18px">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        ← بازگشت به فهرست
    </a>

    <a href="{{ route('catalog.category') }}" class="btn btn-secondary">
        دسته‌بندی قطعات
    </a>
</div>

<section class="hero">
    <span class="badge">اطلاعات قطعه</span>

    <h1 style="margin-top:12px">
        {{ $part->name_fa ?: 'قطعه بدون نام' }}
    </h1>

    <p>
        کد فنی:
        <span class="code">{{ $part->technical_code ?: 'ثبت نشده' }}</span>
    </p>
</section>

{{-- تصویر و خرید محصول --}}
<section class="card product-showcase">

    <div class="product-showcase-image">
        @if (!empty($part->img_src))
            <a href="{{ $part->img_src }}"
               target="_blank"
               rel="noopener noreferrer">

                <img
                    src="{{ $part->img_src }}"
                    alt="{{ $part->name_fa }}"
                    loading="lazy">
            </a>
        @else
            <div class="product-image-placeholder">
                <span>📷</span>
                <p>تصویری برای این قطعه ثبت نشده است</p>
            </div>
        @endif
    </div>

    <div class="product-showcase-info">

        <span class="badge">محصول اصلی</span>

        <h2>{{ $part->name_fa ?: 'قطعه بدون نام' }}</h2>

        <div class="showcase-code">
            کد فنی:
            <strong>{{ $part->technical_code ?: 'ثبت نشده' }}</strong>
        </div>

        @if ($part->price !== null)
            <div class="showcase-price">
                <span>قیمت شرکت</span>
                <strong>
                    {{ number_format((float) $part->price) }}
                    <small>تومان</small>
                </strong>
            </div>
        @endif

        @if (!empty($part->p_link))
            <a
                href="{{ $part->p_link }}"
                target="_blank"
                rel="noopener noreferrer"
                class="product-buy-button">

                🛒 خرید این محصول
                <span>↗</span>
            </a>
        @else
            <div class="product-unavailable">
                لینک خرید برای این محصول ثبت نشده است.
            </div>
        @endif

    </div>
</section>

{{-- مشخصات فنی --}}
<section class="card">
    <h2>مشخصات فنی و قیمت</h2>

    <div class="detail-list">

        <div class="detail-row">
            <div class="detail-label">کد فنی</div>
            <div class="detail-value code">
                {{ $part->technical_code ?: 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">کد لجستیکی</div>
            <div class="detail-value code">
                {{ $part->logistics_code ?: 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">نام قطعه</div>
            <div class="detail-value">
                {{ $part->name_fa ?: 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">گروه اصلی</div>
            <div class="detail-value">
                {{ $part->category ?: 'دسته‌بندی نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">زیرگروه</div>
            <div class="detail-value">
                {{ $part->subcategory ?: 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">قیمت شرکت</div>
            <div class="detail-value price">
                {{ $part->price !== null
                    ? number_format((float) $part->price)
                    : 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">قیمت با مالیات</div>
            <div class="detail-value price">
                {{ isset($part->price_tax)
                    ? number_format((float) $part->price_tax)
                    : 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">مدل خودرو در فایل اصلی</div>
            <div class="detail-value">
                {{ $part->car_model_raw ?: 'ثبت نشده' }}
            </div>
        </div>

    </div>
</section>

{{-- خودروهای مرتبط --}}
<section class="card">
    <h2>خودروهای مرتبط</h2>

    @if ($vehicleModels->isNotEmpty())
        <div class="chips">
            @foreach ($vehicleModels as $vehicle)
                <span class="chip">{{ $vehicle->name }}</span>
            @endforeach
        </div>
    @else
        <p class="muted">
            ارتباط این قطعه با خودروها در جدول ارتباطی ثبت نشده است.
        </p>
    @endif
</section>

{{-- کدهای جایگزین --}}
<section class="card">
    <h2>کدهای جایگزین</h2>

    @if (count($replacementCodes) > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>کد جایگزین</th>
                        <th>وضعیت در دیتابیس</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($replacementCodes as $code)
                        @php
                            $found = $replacementParts->contains(
                                fn ($item) =>
                                    $item->technical_code === $code ||
                                    $item->logistics_code === $code
                            );
                        @endphp

                        <tr>
                            <td class="code">{{ $code }}</td>
                            <td>
                                @if ($found)
                                    <span class="badge">قطعه متناظر پیدا شد</span>
                                @else
                                    <span class="muted">در دیتابیس پیدا نشد</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="muted">
            کد جایگزینی برای این قطعه ثبت نشده است.
        </p>
    @endif
</section>

{{-- قطعات موجود با کد جایگزین --}}
<section class="card">
    <h2>قطعات موجود با کد جایگزین</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>کد فنی</th>
                    <th>نام قطعه</th>
                    <th>قیمت شرکت</th>
                    <th>مشاهده</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($replacementParts as $replacement)
                    <tr>
                        <td class="code">
                            {{ $replacement->technical_code ?: '—' }}
                        </td>

                        <td>
                            {{ $replacement->name_fa ?: 'بدون نام' }}
                        </td>

                        <td class="price">
                            {{ $replacement->price !== null
                                ? number_format((float) $replacement->price)
                                : 'ثبت نشده' }}
                        </td>

                        <td>
                            @if ($replacement->id !== $part->id)
                                <a href="{{ route('catalog.show', $replacement->id) }}">
                                    مشاهده قطعه
                                </a>
                            @else
                                قطعه فعلی
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">
                            قطعه‌ای با کد جایگزین در دیتابیس پیدا نشد.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
```