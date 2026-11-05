@extends('layouts.app')

@section('title', 'درخواست‌های فروش لنت من')

@section('content')

<style>
    .offer-page {
        direction: rtl;
        color: #e8eef8;
        padding: 24px 0;
    }

    .offer-header {
        background: linear-gradient(120deg,#101b30,#19355b,#10434b);
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 22px;
    }

    .offer-header h1 {
        margin: 0 0 10px;
        color: white;
    }

    .offer-header p {
        color: #c5d4e9;
        margin: 0;
    }

    .offer-back {
        display: inline-block;
        margin-top: 17px;
        color: #55e0d5;
        text-decoration: none;
    }

    .offer-table-wrap {
        overflow-x: auto;
        background: #101b2d;
        border: 1px solid #293b55;
        border-radius: 15px;
    }

    .offer-table {
        width: 100%;
        min-width: 750px;
        border-collapse: collapse;
    }

    .offer-table th,
    .offer-table td {
        padding: 15px;
        text-align: right;
        border-bottom: 1px solid #293b55;
    }

    .offer-table th {
        color: #55e0d5;
        font-size: 12px;
        background: #14243a;
    }

    .offer-table td {
        color: #dce7f5;
        font-size: 13px;
    }

    .offer-table a {
        color: #55e0d5;
        text-decoration: none;
    }

    .offer-status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .pending {
        color: #ffd68a;
        background: rgba(245,158,11,.13);
    }

    .approved {
        color: #72e5a5;
        background: rgba(34,197,94,.13);
    }

    .rejected,
    .suspended {
        color: #ff9292;
        background: rgba(239,68,68,.13);
    }

    .offer-empty {
        padding: 45px 20px;
        text-align: center;
        background: #101b2d;
        border: 1px dashed #40516a;
        border-radius: 15px;
        color: #aebed2;
    }
</style>

<div class="offer-page">

    <div class="offer-header">
        <h1>درخواست‌های فروش لنت من</h1>

        <p>
            در این قسمت وضعیت درخواست‌های ثبت‌شده برای فروش لنت را مشاهده می‌کنید.
        </p>

        <a href="{{ route('seller.brake-bank.index') }}" class="offer-back">
            ← بازگشت به بانک لنت
        </a>
    </div>

    @if($offers->count())

        <div class="offer-table-wrap">

            <table class="offer-table">

                <thead>
                    <tr>
                        <th>کد فنی</th>
                        <th>نام لنت</th>
                        <th>برندها</th>
                        <th>لینک محصول</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>توضیحات مدیر</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($offers as $offer)

                        @php
                            $statusLabels = [
                                'pending' => 'در انتظار بررسی',
                                'approved' => 'تأیید شده',
                                'rejected' => 'رد شده',
                                'suspended' => 'تعلیق شده',
                            ];
                        @endphp

                        <tr>

                            <td>
                                {{ $offer->brakePad?->technical_code ?? '—' }}
                            </td>

                            <td>
                                {{ $offer->brakePad?->name ?? 'لنت حذف شده' }}
                            </td>

                            <td>
                                {{ $offer->brands->pluck('name')->implode('، ') ?: '—' }}
                            </td>

                            <td>
                                <a href="{{ $offer->product_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    مشاهده محصول
                                </a>
                            </td>

                            <td>
                                <span class="offer-status {{ $offer->status }}">
                                    {{ $statusLabels[$offer->status] ?? $offer->status }}
                                </span>
                            </td>

                            <td>
                                {{ $offer->created_at?->format('Y/m/d') ?? '—' }}
                            </td>

                            <td>
                                {{ $offer->admin_note ?: '—' }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div style="margin-top:22px">
            {{ $offers->links() }}
        </div>

    @else

        <div class="offer-empty">
            <h3>هنوز درخواستی ثبت نکرده‌اید</h3>

            <p>
                از بانک لنت، لنت موردنظر خود را انتخاب و درخواست فروش ثبت کنید.
            </p>

            <a href="{{ route('seller.brake-bank.index') }}"
               style="color:#55e0d5">
                مشاهده بانک لنت
            </a>
        </div>

    @endif

</div>

@endsection