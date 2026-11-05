
@extends('layouts.app')

@section('title', 'جزئیات فروشنده')

@section('content')

<div class="page-heading">
    <h1>جزئیات فروشنده</h1>
    <p class="muted">بررسی اطلاعات فروشگاه و مدارک ارسالی</p>
</div>

@if(session('success'))
    <div class="card" style="color:#166534;background:#dcfce7">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="color:#991b1b;background:#fee2e2">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">

    <h2>اطلاعات فروشگاه</h2>

    <div class="detail-list">

        <div class="detail-row">
            <div class="detail-label">نام فروشگاه</div>
            <div class="detail-value">{{ $seller->store_name }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">صاحب فروشگاه</div>
            <div class="detail-value">{{ $seller->owner_name }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">شماره تماس</div>
            <div class="detail-value">{{ $seller->phone }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">وب‌سایت</div>
            <div class="detail-value">
                @if($seller->website_url)
                    <a href="{{ $seller->website_url }}" target="_blank" rel="noopener noreferrer">
                        {{ $seller->website_url }}
                    </a>
                @else
                    ثبت نشده
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">ایمیل حساب</div>
            <div class="detail-value">{{ $seller->user->email ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">وضعیت فروشگاه</div>
            <div class="detail-value">{{ $seller->status }}</div>
        </div>

    </div>

    <hr style="border:0;border-top:1px solid var(--border);margin:25px 0">

    <h2>مدارک ارسالی</h2>

    @forelse($seller->documents as $document)
        <div class="detail-row">
            <strong>{{ $document->document_type }}</strong>
        </div>
    @empty
        <p class="muted">مدرکی برای این فروشنده ثبت نشده است.</p>
    @endforelse

    @if($seller->admin_note)
        <div class="card" style="background:#fff7ed">
            <strong>یادداشت مدیر:</strong>
            {{ $seller->admin_note }}
        </div>
    @endif

    @if($seller->status === 'pending')

        <div class="form-actions">

            <form method="POST"
                  action="{{ route('admin.sellers.approve', $seller->id) }}"
                  onsubmit="return confirm('فروشنده تأیید شود؟')">

                @csrf

                <button type="submit" class="btn" style="background:#15803d">
                    تأیید فروشنده
                </button>
            </form>

        </div>

        <form method="POST"
              action="{{ route('admin.sellers.reject', $seller->id) }}"
              style="margin-top:20px">

            @csrf

            <div class="field">
                <label>دلیل رد درخواست</label>
                <textarea name="admin_note" rows="3" required>{{ old('admin_note') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" style="background:#dc2626">
                    رد درخواست
                </button>
            </div>

        </form>

    @endif

    <div class="form-actions">
        <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
            بازگشت به فهرست فروشندگان
        </a>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            بازگشت به پنل مدیریت
        </a>
    </div>

</div>

@endsection
