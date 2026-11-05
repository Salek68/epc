
@extends('layouts.app')

@section('title', 'مدیریت اشتراک‌ها')

@section('content')

<style>
    .subscription-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #dcfce7;
        color: #166534;
    }

    .status-rejected,
    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-cancelled {
        background: #e2e8f0;
        color: #475569;
    }

    .subscription-actions {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 160px;
    }

    .subscription-actions form {
        margin: 0;
    }

    .btn-success {
        background: #15803d;
        color: white;
    }

    .btn-success:hover {
        background: #166534;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    .btn-proof {
        background: #eff6ff;
        color: #1d4ed8;
        padding: 7px 10px;
        border-radius: 8px;
        display: inline-block;
        font-size: 12px;
    }

    .subscription-actions input {
        padding: 8px;
        font-size: 12px;
    }

    .subscription-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .subscription-stat {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
    }

    .subscription-stat strong {
        display: block;
        font-size: 22px;
        margin-top: 5px;
    }

    @media(max-width: 560px) {
        .subscription-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-heading">
    <h1>مدیریت اشتراک‌ها</h1>
    <p class="muted">
        بررسی درخواست‌های خرید و تمدید اشتراک کاربران و فروشندگان
    </p>
</div>

@if(session('success'))
    <div class="card" style="background:#dcfce7;color:#166534;border-color:#bbf7d0">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="background:#fee2e2;color:#991b1b;border-color:#fecaca">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="subscription-stats">

    <div class="subscription-stat">
        <div class="stat-label">کل درخواست‌ها</div>
        <strong>{{ number_format($transactions->total()) }}</strong>
    </div>

    <div class="subscription-stat">
        <div class="stat-label">درخواست‌های در انتظار بررسی</div>
        <strong>
            {{ number_format(
                \App\Models\SubscriptionTransaction::where('status', 'pending')->count()
            ) }}
        </strong>
    </div>

    <div class="subscription-stat">
        <div class="stat-label">پرداخت‌های تأییدشده</div>
        <strong>
            {{ number_format(
                \App\Models\SubscriptionTransaction::where('status', 'approved')->count()
            ) }}
        </strong>
    </div>

</div>

<div class="card">

    <h2>فهرست درخواست‌های پرداخت</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>شناسه</th>
                    <th>اطلاعات کاربر</th>
                    <th>نوع اشتراک</th>
                    <th>مدت</th>
                    <th>مبلغ</th>
                    <th>فیش پرداخت</th>
                    <th>وضعیت</th>
                    <th>تاریخ درخواست</th>
                    <th>عملیات</th>
                </tr>
            </thead>

            <tbody>

            @forelse($transactions as $transaction)

                <tr>
                    <td>
                        <span class="code">#{{ $transaction->id }}</span>
                    </td>

                    <td>
                        <strong>
                            {{ $transaction->user->name ?? 'کاربر حذف‌شده' }}
                        </strong>

                        <div class="muted small">
                            {{ $transaction->user->email ?? '-' }}
                        </div>
                    </td>

                    <td>
                        <span class="badge">
                            {{ $transaction->role === 'seller' ? 'فروشنده' : 'کاربر عادی' }}
                        </span>
                    </td>

                    <td>
                        {{ number_format($transaction->duration_days) }} روز
                    </td>

                    <td>
                        <span class="price">
                            {{ number_format($transaction->amount) }}
                        </span>
                        <small>تومان</small>
                    </td>

                    <td>
                        @if($transaction->document_path)
                            <a class="btn-proof"
                               href="{{ \Illuminate\Support\Facades\Storage::url($transaction->document_path) }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                مشاهده فیش
                            </a>
                        @else
                            <span class="muted">ثبت نشده</span>
                        @endif
                    </td>

                    <td>
                        @php
                            $statusLabels = [
                                'pending' => 'در انتظار بررسی',
                                'approved' => 'تأیید شده',
                                'rejected' => 'رد شده',
                                'cancelled' => 'لغو شده',
                                'failed' => 'ناموفق',
                            ];
                        @endphp

                        <span class="subscription-status status-{{ $transaction->status }}">
                            {{ $statusLabels[$transaction->status] ?? 'نامشخص' }}
                        </span>
                    </td>

                    <td>
                        {{ $transaction->created_at?->format('Y/m/d H:i') }}
                    </td>

                    <td>
                        <div class="subscription-actions">

                            @if($transaction->status === 'pending')

                                <form method="POST"
                                      action="{{ route('admin.subscription-transactions.approve', $transaction->id) }}"
                                      onsubmit="return confirm('پرداخت تأیید و اشتراک فعال شود؟')">

                                    @csrf

                                    <button type="submit" class="btn btn-success">
                                        تأیید و فعال‌سازی
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.subscription-transactions.reject', $transaction->id) }}"
                                      onsubmit="return confirm('درخواست پرداخت رد شود؟')">

                                    @csrf

                                    <input type="text"
                                           name="admin_note"
                                           placeholder="دلیل رد درخواست">

                                    <button type="submit" class="btn btn-danger">
                                        رد درخواست
                                    </button>
                                </form>

                            @else

                                <span class="muted small">
                                    این درخواست بررسی شده است.
                                </span>

                                @if($transaction->admin_note)
                                    <span class="muted small">
                                        توضیحات: {{ $transaction->admin_note }}
                                    </span>
                                @endif

                                @if($transaction->approved_at)
                                    <span class="muted small">
                                        تأیید در:
                                        {{ $transaction->approved_at->format('Y/m/d H:i') }}
                                    </span>
                                @endif

                            @endif

                        </div>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="9" class="empty">
                        هنوز هیچ درخواست اشتراکی ثبت نشده است.
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $transactions->links() }}
    </div>

</div>

@endsection
