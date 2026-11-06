
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ثبت نام</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Tahoma, sans-serif;
            background: #f1f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .box {
            background: white;
            width: 90%;
            max-width: 420px;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 5px 25px #0001;
        }
        h2 { text-align: center; }
        label { display: block; margin-top: 18px; }
        input {
            width: 100%;
            padding: 13px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }
        button {
            width: 100%;
            padding: 13px;
            margin-top: 25px;
            background: #1769aa;
            color: white;
            border: 0;
            border-radius: 7px;
            cursor: pointer;
        }
        .error { color: #d00; font-size: 13px; }
        a { color: #1769aa; }
        .link { text-align: center; margin-top: 20px; }
    </style>
<link rel="stylesheet" href="{{ asset('css/partcar-system.css') }}?v=2.0.0"></head>
<body>

<div class="box">
    <h2>ساخت حساب کاربری</h2>

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf

        <label>نام و نام خانوادگی</label>
        <input type="text" name="name"
               value="{{ old('name') }}" required>

        <label>ایمیل</label>
        <input type="email" name="email"
               value="{{ old('email') }}" required>

        <label>رمز عبور</label>
        <input type="password" name="password" required>

        <label>تکرار رمز عبور</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">ثبت نام</button>
    </form>

    <div class="link">
        قبلاً ثبت نام کرده‌اید؟
        <a href="{{ route('login') }}">ورود به حساب</a>
    </div>
</div>

</body>
</html>
