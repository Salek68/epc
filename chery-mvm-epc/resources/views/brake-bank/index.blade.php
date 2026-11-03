@extends('layouts.app')

@section('title', 'بانک اطلاعات لنت خودروها')

@section('content')

<div class="page-heading">
    <h1>بانک اطلاعات لنت ترمز</h1>
    <p class="muted">
        جست‌وجوی خودرو و مشاهده مشخصات لنت جلو و عقب و خودروهای مشترک
    </p>
</div>

<section class="hero">
    <h1>بانک هوشمند لنت خودروهای چینی</h1>
    <p>
        مدل خودرو را جست‌وجو کن تا کد فنی، قیمت، تصاویر و مشترکات لنت‌ها را ببینی.
    </p>
</section>

<div class="card">
    <h2>جست‌وجوی خودرو</h2>

    <form method="GET" action="{{ route('brake-bank.index') }}">
        <div class="field">
            <label for="q">نام خودرو</label>

            <input
                type="search"
                name="q"
                id="q"
                value="{{ $q }}"
                placeholder="مثلاً KMC X5 یا JAC S5"
                autocomplete="off"
                autofocus
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">
                جست‌وجوی خودرو
            </button>

            <a href="{{ route('brake-bank.index') }}" class="btn btn-secondary">
                پاک کردن
            </a>
        </div>
    </form>
</div>

@if($q !== '')
    <div class="card">
        <h2>نتایج جست‌وجو</h2>

        @forelse($vehicles as $vehicle)
            <a
                href="{{ route('brake-bank.vehicle', $vehicle->id) }}"
                class="category-item"
                style="margin-bottom:10px;"
            >
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <div>
                        <strong>{{ $vehicle->name }}</strong>
                        <small>
                            {{ $vehicle->brake_pads_count }} لنت ثبت‌شده
                        </small>
                    </div>

                    <span class="badge">مشاهده لنت‌ها ←</span>
                </div>
            </a>
        @empty
            <div class="empty">
                <div style="font-size:38px;">🔎</div>
                خودرویی با این نام پیدا نشد.
            </div>
        @endforelse
    </div>
@else
    <div class="card">
        <h2>خودروهای ثبت‌شده</h2>

        <div class="category-grid">
            @forelse($vehicles as $vehicle)
                <a
                    href="{{ route('brake-bank.vehicle', $vehicle->id) }}"
                    class="category-item"
                >
                    <strong>{{ $vehicle->name }}</strong>
                    <small>
                        {{ $vehicle->brake_pads_count }} لنت
                    </small>
                </a>
            @empty
                <div class="empty">
                    هنوز خودرویی در بانک لنت ثبت نشده است.
                </div>
            @endforelse
        </div>
    </div>
@endif

@endsection