
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود به حساب</title>
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
</head>
<body>

<div class="box">
    <h2>ورود به حساب کاربری</h2>

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <label>ایمیل</label>
        <input type="email" name="email"
               value="{{ old('email') }}" required>

        <label>رمز عبور</label>
        <input type="password" name="password" required>

        <button type="submit">ورود</button>
    </form>

    <div class="link">
        حساب ندارید؟
        <a href="{{ route('register') }}">ثبت‌نام کنید</a>
    </div>
</div>

</body>
</html>
