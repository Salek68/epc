
@extends('layouts.app')

@section('title', 'مدیریت فروشندگان')

@section('content')

<div class="page-heading">
    <h1>مدیریت درخواست‌های فروشندگی</h1>
    <p class="muted">مشاهده و بررسی درخواست‌های ثبت‌شده فروشندگان</p>
</div>

@if(session('success'))
    <div class="card" style="color:#166534;background:#dcfce7">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h2>فهرست فروشندگان</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>فروشگاه</th>
                    <th>صاحب فروشگاه</th>
                    <th>شماره تماس</th>
                    <th>وضعیت</th>
                    <th>جزئیات</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sellers as $seller)
                    <tr>
                        <td><strong>{{ $seller->store_name }}</strong></td>
                        <td>{{ $seller->owner_name }}</td>
                        <td>{{ $seller->phone }}</td>

                        <td>
                            @if($seller->status === 'pending')
                                <span class="badge" style="background:#fef3c7;color:#92400e">
                                    در انتظار بررسی
                                </span>
                            @elseif($seller->status === 'approved')
                                <span class="badge" style="background:#dcfce7;color:#166534">
                                    تأیید شده
                                </span>
                            @elseif($seller->status === 'rejected')
                                <span class="badge" style="background:#fee2e2;color:#991b1b">
                                    رد شده
                                </span>
                            @else
                                <span class="badge">{{ $seller->status }}</span>
                            @endif
                        </td>

                        <td>
                            <a class="btn"
                               href="{{ route('admin.sellers.show', $seller->id) }}">
                                مشاهده جزئیات
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">
                            هنوز درخواستی ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $sellers->links() }}
    </div>
</div>

@endsection
