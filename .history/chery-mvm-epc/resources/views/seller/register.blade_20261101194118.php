
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ثبت درخواست فروشندگی</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            background: #f4f5f7;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            box-sizing: border-box;
        }
        button {
            background: #1769aa;
            color: white;
            border: 0;
            padding: 13px 25px;
            cursor: pointer;
            border-radius: 6px;
        }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>درخواست همکاری به عنوان فروشنده</h2>
    <p>اطلاعات فروشگاه خود را وارد کنید.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ route('seller.register.store') }}"
          enctype="multipart/form-data">

        @csrf

        <label>نام فروشگاه</label>
        <input type="text" name="store_name"
               value="{{ old('store_name') }}" required>

        <label>نام صاحب فروشگاه</label>
        <input type="text" name="owner_name"
               value="{{ old('owner_name') }}" required>

        <label>شماره تماس</label>
        <input type="text" name="phone"
               value="{{ old('phone') }}" required>

        <label>آدرس وب‌سایت</label>
        <input type="url" name="website_url"
               value="{{ old('website_url') }}"
               placeholder="https://example.com">

        <label>مدارک فروشگاه (اختیاری)</label>
        <input type="file" name="documents[]" multiple
               accept=".jpg,.jpeg,.png,.pdf">

        <button type="submit">ارسال درخواست</button>
    </form>
</div>

</body>
</html>
