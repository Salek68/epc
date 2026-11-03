@extends('layouts.app')

@section('title', 'مشخصات لنت ' . $pad->technical_code)

@section('content')

<div class="page-heading">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        ← بازگشت
    </a>
</div>

<section class="hero">
    <span class="badge">BRAKE PAD DETAILS</span>

    <h1 style="margin-top:12px;">{{ $pad->name }}</h1>

    <p>
        مشخصات فنی، قیمت فعلی و خودروهای سازگار با این لنت
    </p>
</section>

<div class="card">
    <h2>گالری تصاویر لنت</h2>

    @if($pad->images->isNotEmpty())

        <div class="grid-2">
            @foreach($pad->images as $image)
                <a
                    href="{{ asset('storage/' . $image->image_path) }}"
                    target="_blank"
                    class="product-showcase-image"
                >
                    <img
                        src="{{ asset('storage/' . $image->image_path) }}"
                        alt="{{ $pad->name }}"
                        loading="lazy"
                    >
                </a>
            @endforeach
        </div>

    @else
        <div class="empty">
            <div style="font-size:50px;">🛞</div>
            هنوز عکسی برای این لنت بارگذاری نشده است.
        </div>
    @endif
</div>

<div class="card">
    <h2>مشخصات فنی</h2>

    <div class="detail-list">

        <div class="detail-row">
            <div class="detail-label">نام لنت</div>
            <div class="detail-value">{{ $pad->name }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">کد فنی</div>
            <div class="detail-value code">
                {{ $pad->technical_code }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">موقعیت لنت</div>
            <div class="detail-value">
                @if($pad->position === 'front')
                    لنت جلو
                @elseif($pad->position === 'rear')
                    لنت عقب
                @else
                    نامشخص
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">قیمت فعلی</div>
            <div class="detail-value price">
                {{ $pad->price !== null ? number_format($pad->price) . ' تومان' : 'ثبت نشده' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">تعداد در کارتن</div>
            <div class="detail-value">
                {{ $pad->carton_quantity ?? 'ثبت نشده' }}
            </div>
        </div>

    </div>
</div>

<div class="card">
    <h2>خودروهای سازگار</h2>

    <div class="chips">
        @forelse($pad->vehicles as $vehicle)
            <a
                href="{{ route('brake-bank.vehicle', $vehicle->id) }}"
                class="chip"
            >
                {{ $vehicle->name }}
            </a>
        @empty
            <div class="empty">خودرویی برای این لنت ثبت نشده است.</div>
        @endforelse
    </div>
</div>

<div class="card">
    <h2>سایر مشترکات</h2>

    <div class="chips">
        @forelse($pad->compatibilities as $compatibility)
            <span class="chip">
                {{ $compatibility->vehicle_name }}
            </span>
        @empty
            <div class="empty">مشترکات دیگری ثبت نشده است.</div>
        @endforelse
    </div>
</div>

@endsection