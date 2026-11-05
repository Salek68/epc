
@extends('layouts.app')

@section('title', 'اشتراک من')

@section('content')

<div class="page-heading">
    <h1>اشتراک من</h1>
    <p>وضعیت اشتراک‌ها و سوابق پرداخت حساب شما</p>
</div>

@if(session('success'))
    <div class="card" style="border-right:4px solid #16a34a; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

    {{-- اشتراک کاربر --}}
    <div class="card">
        <h2>اشتراک کاربری</h2>

        @if($activeUserSubscription)

            <div style="color:#16a34a; font-weight:bold;">
                اشتراک فعال
            </div>

            <p>
                تاریخ شروع:
                {{ $activeUserSubscription->starts_at->format('Y/m/d') }}
            </p>

            <p>
                تاریخ پایان:
                {{ $activeUserSubscription->expires_at->format('Y/m/d') }}
            </p>

            <h3>
                {{ $activeUserSubscription->remainingDays() }}
                روز باقی‌مانده
            </h3>

        @else

            <div style="color:#dc2626; font-weight:bold;">
                اشتراک فعال ندارید
            </div>

            <p>برای استفاده از کاتالوگ، اشتراک کاربری تهیه کنید.</p>

        @endif

        <a href="{{ route('subscriptions.index') }}" class="btn">
            خرید یا تمدید اشتراک کاربری
        </a>
    </div>


    {{-- اشتراک فروشنده --}}
    <div class="card">
        <h2>اشتراک فروشندگی</h2>

        @if($activeSellerSubscription)

            <div style="color:#16a34a; font-weight:bold;">
                اشتراک فعال
            </div>

            <p>
                تاریخ شروع:
                {{ $activeSellerSubscription->starts_at->format('Y/m/d') }}
            </p>

            <p>
                تاریخ پایان:
                {{ $activeSellerSubscription->expires_at->format('Y/m/d') }}
            </p>

            <h3>
                {{ $activeSellerSubscription->remainingDays() }}
                روز باقی‌مانده
            </h3>

        @else

            <div style="color:#dc2626; font-weight:bold;">
                اشتراک فعال ندارید
            </div>

            <p>برای مدیریت محصولات فروشگاه، اشتراک فروشندگی تهیه کنید.</p>

        @endif

        <a href="{{ route('subscriptions.index') }}" class="btn">
            خرید یا تمدید اشتراک فروشندگی
        </a>
    </div>

</div>


{{-- سوابق اشتراک‌ها --}}
<div class="card" style="margin-top:25px;">

    <h2>سوابق اشتراک‌ها</h2>

    @if($subscriptions->isEmpty())

        <p>هنوز اشتراکی برای حساب شما ثبت نشده است.</p>

    @else

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>نوع اشتراک</th>
                        <th>مدت</th>
                        <th>شروع</th>
                        <th>پایان</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td>
                                {{ $subscription->role === 'seller' ? 'فروشنده' : 'کاربر' }}
                            </td>

                            <td>
                                {{ $subscription->duration_days }} روز
                            </td>

                            <td>
                                {{ $subscription->starts_at->format('Y/m/d') }}
                            </td>

                            <td>
                                {{ $subscription->expires_at->format('Y/m/d') }}
                            </td>

                            <td>
                                @if($subscription->isValid())
                                    <span style="color:#16a34a;">فعال</span>
                                @elseif($subscription->status === 'cancelled')
                                    <span style="color:#dc2626;">لغو شده</span>
                                @else
                                    <span style="color:#d97706;">منقضی شده</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

</div>


{{-- سوابق پرداخت --}}
<div class="card" style="margin-top:25px;">

    <h2>سوابق درخواست و پرداخت</h2>

    @if($transactions->isEmpty())

        <p>هنوز درخواست پرداختی ثبت نکرده‌اید.</p>

    @else

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>نوع اشتراک</th>
                        <th>مدت</th>
                        <th>مبلغ</th>
                        <th>تاریخ درخواست</th>
                        <th>وضعیت</th>
                        <th>جزئیات</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                {{ $transaction->role === 'seller' ? 'فروشنده' : 'کاربر' }}
                            </td>

                            <td>
                                {{ $transaction->duration_days }} روز
                            </td>

                            <td>
                                {{ number_format($transaction->amount) }} تومان
                            </td>

                            <td>
                                {{ $transaction->created_at->format('Y/m/d') }}
                            </td>

                            <td>
                                @switch($transaction->status)
                                    @case('pending')
                                        <span style="color:#d97706;">در انتظار بررسی</span>
                                        @break

                                    @case('approved')
                                        <span style="color:#16a34a;">تأیید شده</span>
                                        @break

                                    @case('rejected')
                                        <span style="color:#dc2626;">رد شده</span>
                                        @break

                                    @default
                                        {{ $transaction->status }}
                                @endswitch
                            </td>

                            <td>
                                @if($transaction->status === 'pending')
                                    <a href="{{ route('subscriptions.payment', $transaction->id) }}">
                                        مشاهده درخواست
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

</div>

<div style="margin-top:20px;">
    <a href="{{ route('subscriptions.index') }}" class="btn">
        مشاهده پلن‌های اشتراک
    </a>
</div>

@endsection
