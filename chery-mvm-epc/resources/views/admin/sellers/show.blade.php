
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جزئیات فروشنده</title>
</head>
<body style="font-family:Tahoma;padding:30px;background:#f4f5f7">

<div style="max-width:700px;margin:auto;background:white;padding:30px;border-radius:12px">

    <h2>اطلاعات فروشگاه</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <p>نام فروشگاه: {{ $seller->store_name }}</p>
    <p>صاحب فروشگاه: {{ $seller->owner_name }}</p>
    <p>تلفن: {{ $seller->phone }}</p>
    <p>وب‌سایت: {{ $seller->website_url }}</p>
    <p>ایمیل حساب: {{ $seller->user->email }}</p>
    <p>وضعیت: {{ $seller->status }}</p>

    <h3>مدارک ارسالی</h3>

    @foreach($seller->documents as $document)
        <p>{{ $document->document_type }}</p>
    @endforeach

    <hr>

    <form method="POST"
          action="{{ route('admin.sellers.approve', $seller->id) }}">
        @csrf
        <button type="submit">تأیید فروشنده</button>
    </form>

    <br>

    <form method="POST"
          action="{{ route('admin.sellers.reject', $seller->id) }}">
        @csrf

        <label>دلیل رد درخواست</label>
        <textarea name="admin_note" required></textarea>

        <button type="submit">رد درخواست</button>
    </form>

    <br>
    <a href="{{ route('admin.sellers.index') }}">بازگشت</a>

</div>

</body>
</html>
