
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101c35">

    <title>@yield('title', 'کاتالوگ قطعات چری و MVM')</title>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>

        /* Product Showcase */
.product-showcase {
    display: grid;
    grid-template-columns: minmax(220px, 0.85fr) minmax(0, 1.15fr);
    gap: 30px;
    align-items: center;
    padding: 25px;
}

.product-showcase-image {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-showcase-image img {
    width: 100%;
    height: 280px;
    object-fit: contain;
    padding: 15px;
    transition: transform .3s ease;
}

.product-showcase-image img:hover {
    transform: scale(1.06);
}

.product-image-placeholder {
    text-align: center;
    color: #94a3b8;
    padding: 30px;
}

.product-image-placeholder span {
    font-size: 55px;
}

.product-image-placeholder p {
    font-size: 13px;
}

.product-showcase-info h2 {
    font-size: 21px;
    margin: 16px 0;
}

.showcase-code {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 20px;
}

.showcase-code strong {
    color: #2563eb;
    direction: ltr;
    display: inline-block;
}

.showcase-price {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 22px;
}

.showcase-price span {
    font-size: 13px;
    color: #64748b;
}

.showcase-price strong {
    font-size: 25px;
    color: #15803d;
}

.showcase-price small {
    font-size: 13px;
}

.product-buy-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    max-width: 300px;
    padding: 15px 22px;
    border-radius: 12px;
    background: #16a34a;
    color: white;
    font-weight: 800;
    text-decoration: none;
    transition: .25s ease;
}

.product-buy-button:hover {
    background: #15803d;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(22, 163, 74, .2);
    color: white;
}

.product-unavailable {
    color: #94a3b8;
    background: #f1f5f9;
    padding: 13px;
    border-radius: 10px;
    font-size: 13px;
}

@media (max-width: 700px) {
    .product-showcase {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 20px;
    }

    .product-showcase-image {
        min-height: 220px;
    }

    .product-showcase-image img {
        height: 220px;
    }

    .product-buy-button {
        max-width: 100%;
    }
}

        :root {
            --navy: #101c35;
            --navy-light: #1d3154;
            --blue: #2563eb;
            --blue-light: #eff6ff;
            --bg: #f4f7fb;
            --surface: #ffffff;
            --text: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
            --green: #15803d;
            --radius: 16px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Tahoma, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.9;
        }

        a { color: var(--blue); text-decoration: none; }
        a:hover { text-decoration: underline; }

        .topbar {
            background: var(--navy);
            color: white;
            padding: 16px 20px;
        }

        .topbar-inner {
            max-width: 1320px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            color: white;
            font-size: 21px;
            font-weight: bold;
        }

        .brand small {
            display: block;
            color: #bdcbe0;
            font-size: 11px;
            font-weight: normal;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .nav a {
            color: #e5edf9;
            padding: 7px 12px;
            border-radius: 9px;
        }

        .nav a:hover {
            background: var(--navy-light);
            text-decoration: none;
        }

        .container {
            max-width: 1320px;
            margin: auto;
            padding: 25px 18px 45px;
        }

        .page-heading {
            margin-bottom: 22px;
        }

        .page-heading h1 {
            font-size: 25px;
            margin: 0 0 5px;
        }

        .muted { color: var(--muted); }
        .small { font-size: 12px; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
            min-width: 0;
        }

        .card h2 {
            margin: 0 0 15px;
            font-size: 18px;
        }

        .hero {
            background: linear-gradient(125deg, #101c35, #244b80);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 22px;
        }

        .hero h1 { font-size: 27px; margin: 0 0 8px; }
        .hero p { color: #d9e6f8; margin: 0; }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .field { min-width: 0; }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 13px;
        }

        input, select, textarea {
            width: 100%;
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: white;
            color: var(--text);
            font: inherit;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px #2563eb18;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            border: 0;
            border-radius: 10px;
            padding: 10px 16px;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            background: var(--blue);
            color: white;
        }

        .btn:hover {
            background: #1d4ed8;
            text-decoration: none;
            color: white;
        }

        .btn-secondary {
            background: var(--blue-light);
            color: #1d4ed8;
        }

        .btn-secondary:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 17px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 15px;
        }

        .stat-label { color: var(--muted); font-size: 12px; }
        .stat-value { font-weight: bold; font-size: 19px; }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .category-item {
            display: block;
            color: var(--text);
            border: 1px solid var(--border);
            background: white;
            border-radius: 12px;
            padding: 14px;
            transition: .15s;
        }

        .category-item:hover {
            border-color: #93b4f8;
            background: #f8fbff;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .category-item strong { display: block; }
        .category-item small { color: var(--muted); }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 760px;
            background: white;
        }

        th, td {
            text-align: right;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            white-space: nowrap;
        }

        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #f8fbff; }

        .code {
            direction: ltr;
            unicode-bidi: plaintext;
            font-family: Consolas, monospace;
            font-weight: bold;
            white-space: nowrap;
        }

        .price {
            color: var(--green);
            font-weight: bold;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
        }

        .empty {
            padding: 28px 12px;
            text-align: center;
            color: var(--muted);
        }

        .detail-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 24px;
        }

        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            overflow-wrap: anywhere;
        }

        .detail-label {
            color: var(--muted);
            font-size: 12px;
        }

        .detail-value {
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .chip {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #f8fafc;
            color: var(--text);
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 18px;
        }

        .pagination a, .pagination span {
            padding: 6px 11px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .pagination [aria-current="page"] span {
            background: var(--blue);
            color: white;
        }

        footer {
            padding: 22px 15px;
            text-align: center;
            color: var(--muted);
            border-top: 1px solid var(--border);
            background: white;
            font-size: 12px;
        }

        @media (max-width: 850px) {
            .category-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 560px) {
            body { font-size: 13px; }
            .topbar { padding: 14px; }
            .topbar-inner { align-items: flex-start; }
            .brand { font-size: 18px; }
            .nav { width: 100%; }
            .nav a { padding: 5px 9px; }
            .container { padding: 16px 10px 30px; }
            .hero { padding: 21px 17px; }
            .hero h1, .page-heading h1 { font-size: 21px; }
            .card { padding: 15px; border-radius: 12px; }
            .grid, .grid-2, .detail-list { grid-template-columns: 1fr; }
            .category-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stats { grid-template-columns: 1fr; }
            .stat-value { font-size: 17px; }
            .form-actions .btn { flex: 1; }
        }



/* کوچک‌سازی آیکون‌ها */
svg,
.icon,
i {
    max-width: 18px;
    max-height: 18px;
}

.nav svg,
.nav i {
    width: 15px;
    height: 15px;
}

/* جلوگیری از بزرگ شدن عناصر آیکونی */
button svg,
a svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

/* اندازه مناسب موبایل */
@media (max-width: 560px) {
    svg,
    .icon,
    i {
        max-width: 16px;
        max-height: 16px;
    }
}

    .controls , .titlebar , .watermark{
display: none  !important;
    }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="brand">
                CHERY – MVM EPC
                <small>سامانه جست‌وجو و مدیریت اطلاعات قطعات</small>
            </a>

            <nav class="nav">
                <a href="{{ route('catalog.index') }}">صفحه اصلی</a>
                <a href="{{ route('catalog.category') }}">دسته‌بندی قطعات</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        کاتالوگ داخلی قطعات CHERY – MVM
        <br>
        اطلاعات قیمت و سازگاری بر اساس داده‌های ثبت‌شده در پایگاه داده
    </footer>
</body>
</html>
