
@extends('layouts.app')

@section('title', 'پرداخت اشتراک')

@section('content')

<div class="page-heading">
    <h1>پرداخت اشتراک</h1>
    <p>اطلاعات درخواست اشتراک شما</p>
</div>

@if(session('success'))
    <div class="card" style="border-right:4px solid #16a34a; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="border-right:4px solid #dc2626; margin-bottom:20px;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="card">

    <h2>مشخصات اشتراک</h2>

    <div class="detail-list">

        <div>
            <strong>نوع اشتراک:</strong>
            {{ $transaction->role === 'seller' ? 'فروشنده' : 'کاربر' }}
        </div>

        <div>
            <strong>مدت اعتبار:</strong>
            {{ $transaction->duration_days }} روز
        </div>

        <div>
            <strong>مبلغ قابل پرداخت:</strong>
            {{ number_format($transaction->amount) }} تومان
        </div>

        <div>
            <strong>وضعیت درخواست:</strong>
            @if($transaction->status === 'pending')
                در انتظار بررسی
            @elseif($transaction->status === 'approved')
                تأیید شده
            @elseif($transaction->status === 'rejected')
                رد شده
            @else
                {{ $transaction->status }}
            @endif
        </div>

    </div>

    <hr>

    @if($transaction->document_path)

        <div style="margin-bottom:20px;">
            <h3>فیش ارسال‌شده</h3>

            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($transaction->document_path) }}"
               target="_blank">
                مشاهده تصویر فیش پرداخت
            </a>
        </div>

        <p>فیش شما ارسال شده است و درخواست در انتظار بررسی مدیریت است.</p>

    @else

        <h3>ارسال فیش پرداخت</h3>

        <p>
            پس از پرداخت مبلغ اشتراک، تصویر رسید پرداخت را در قسمت زیر بارگذاری کنید.
        </p>

        <form method="POST"
              action="{{ route('subscriptions.receipt', $transaction->id) }}"
              enctype="multipart/form-data">

            @csrf

            <div style="margin:20px 0;">
                <label for="receipt">تصویر فیش پرداخت</label>

                <input
                    type="file"
                    name="receipt"
                    id="receipt"
                    accept="image/jpeg,image/png,image/webp"
                    required
                >
            </div>

            <button type="submit" class="btn">
                ارسال فیش برای مدیریت
            </button>

        </form>

    @endif

    <div style="margin-top:25px;">
        <a href="{{ route('subscriptions.index') }}">بازگشت به اشتراک‌ها</a>
    </div>

</div>

@endsection
