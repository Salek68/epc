
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدیریت فروشندگان</title>
</head>
<body style="font-family:Tahoma;padding:30px;background:#f4f5f7">

<h2>مدیریت درخواست‌های فروشندگی</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table width="100%" border="1" cellpadding="12" cellspacing="0">
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
                <td>{{ $seller->store_name }}</td>
                <td>{{ $seller->owner_name }}</td>
                <td>{{ $seller->phone }}</td>
                <td>{{ $seller->status }}</td>
                <td>
                    <a href="{{ route('admin.sellers.show', $seller->id) }}">
                        مشاهده
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">هنوز درخواستی ثبت نشده است.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $sellers->links() }}

</body>
</html>
