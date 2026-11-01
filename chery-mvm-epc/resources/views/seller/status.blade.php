
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>وضعیت درخواست فروشندگی</title>
</head>
<body style="font-family:Tahoma;background:#f4f5f7;padding:40px">

<div style="max-width:600px;margin:auto;background:white;padding:30px;border-radius:12px">

    <h2>وضعیت درخواست فروشندگی</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <p>نام فروشگاه: {{ $store->store_name }}</p>
    <p>صاحب فروشگاه: {{ $store->owner_name }}</p>

    <hr>

    @if($store->status === 'pending')
        <h3 style="color:#d97706">در انتظار بررسی</h3>
        <p>درخواست شما ثبت شده و توسط مدیریت بررسی خواهد شد.</p>

    @elseif($store->status === 'approved')
        <h3 style="color:green">درخواست شما تأیید شده است</h3>
        <p>اکنون می‌توانید محصولات خود را در سایت ثبت کنید.</p>

    @elseif($store->status === 'rejected')
        <h3 style="color:red">درخواست شما رد شده است</h3>
        <p>دلیل رد درخواست: {{ $store->admin_note }}</p>

    @elseif($store->status === 'suspended')
        <h3 style="color:red">حساب فروشندگی شما تعلیق شده است</h3>
    @endif

</div>

</body>
</html>
