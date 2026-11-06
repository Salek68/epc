
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0b1730">

<title>PartCar | مرجع کد فنی و لیست قطعات خودروهای چینی</title>
<meta name="description" content="جست‌وجوی کد فنی قطعات خودرو، مشاهده لیست قطعات خودروهای چینی، جک، مدیران خودرو، MVM، چری و لیفان در سامانه تخصصی پارت‌کار.">
<meta name="robots" content="index,follow">
<link rel="canonical" href="/">

<meta property="og:type" content="website">
<meta property="og:title" content="پارت‌کار | کد فنی قطعات خودروهای چینی">
<meta property="og:description" content="مرجع جست‌وجوی کد فنی و شناسایی قطعات خودروهای چینی">
<meta property="og:url" content="/">
<meta property="og:image" content="/branding/partcar-app-icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --navy: #0b1730;
    --blue: #087bfa;
    --light-blue: #eaf3ff;
    --text: #17243a;
    --muted: #718096;
    --bg: #f5f7fb;
    --border: #e5eaf1;
    --green: #12a477;
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: Vazirmatn, Tahoma, sans-serif;
    color: var(--text);
  background: #041c37;
    line-height: 1.9;
}

a {
    text-decoration: none;
    color: inherit;
}

.container {
    width: min(1160px, calc(100% - 36px));
    margin: auto;
}

.header {
    height: 82px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    border-bottom: 1px solid var(--border);
}

.logo img {
    width: 185px;
    display: block;
}

.menu {
    display: flex;
    align-items: center;
    gap: 26px;
    font-size: 12px;
    color: #52627a;
}

.menu a:hover {
    color: var(--blue);
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 21px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 800;
    font-family: inherit;
    transition: .2s;
}

.button-primary {
    background: var(--blue);
    color: white;
}

.button-primary:hover {
    background: #0668d7;
    transform: translateY(-2px);
}

.button-light {
    background: white;
    border: 1px solid var(--border);
    color: var(--navy);
}

.hero {
    padding: 95px 0 85px;
    background:
        radial-gradient(circle at 85% 20%, #dcecff 0, transparent 30%),
        linear-gradient(180deg, #f5f8fd, #fff);
}

.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 65px;
}

.tag {
    display: inline-block;
    padding: 7px 13px;
    border-radius: 7px;
    background: var(--light-blue);
    color: #0868ce;
    font-size: 11px;
    font-weight: 800;
}

.hero h1 {
    color: var(--navy);
    font-size: clamp(32px, 4.5vw, 53px);
    line-height: 1.65;
    margin: 20px 0;
    font-weight: 900;
}

.hero h1 span {
    color: var(--blue);
}

.hero-description {
    color: var(--muted);
    font-size: 14px;
    line-height: 2.5;
    max-width: 570px;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 28px;
}

.hero-benefits {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 27px;
    color: #586982;
    font-size: 11px;
}

.hero-benefits span::before {
    content: "✓";
    color: var(--green);
    font-weight: 900;
    margin-left: 6px;
}

.preview {
    background: white;
    border: 1px solid #e1e8f1;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 25px 70px #183c6815;
}

.preview-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.preview-heading strong {
    font-size: 13px;
    color: var(--navy);
}

.status {
    background: #e8f8f1;
    color: #159765;
    font-size: 10px;
    padding: 5px 9px;
    border-radius: 6px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #dce5f0;
    border-radius: 10px;
    padding: 13px;
    color: #8b98aa;
    font-size: 11px;
}

.search-icon {
    font-size: 20px;
    color: var(--blue);
}

.search-label {
    margin-right: auto;
    background: var(--blue);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
}

.preview-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 16px 0;
}

.preview-filters span {
    padding: 7px 11px;
    border-radius: 7px;
    background: #f2f5f9;
    color: #65758c;
    font-size: 10px;
}

.parts {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.part {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
}

.part-icon {
    height: 90px;
    display: grid;
    place-items: center;
    background: #f2f6fb;
    border-radius: 8px;
    font-size: 30px;
    color: #67809f;
}

.part strong {
    display: block;
    font-size: 10px;
    margin-top: 9px;
}

.part small {
    display: block;
    font-size: 9px;
    color: var(--muted);
    margin-top: 3px;
}

.section {
    padding: 85px 0;
}

.section-gray {
    background: var(--bg);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.section-heading {
    max-width: 720px;
    text-align: center;
    margin: 0 auto 42px;
}

.section-heading span {
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
}

.section-heading h2 {
    color: var(--navy);
    font-size: 28px;
    line-height: 1.8;
    margin: 10px 0;
}

.section-heading p {
    color: var(--muted);
    font-size: 12px;
    line-height: 2.4;
    margin: 0;
}

.features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.feature {
    padding: 25px;
    border: 1px solid var(--border);
    border-radius: 13px;
    background: white;
    transition: .2s;
}

.feature:hover {
    border-color: #b6d7ff;
    transform: translateY(-4px);
}

.feature-number {
    color: var(--blue);
    font-size: 11px;
    font-weight: 900;
}

.feature h3 {
    font-size: 15px;
    color: var(--navy);
    margin: 12px 0 8px;
}

.feature p {
    font-size: 11px;
    color: var(--muted);
    line-height: 2.3;
    margin: 0;
}

.brand-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
}

.brand-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 22px 15px;
    text-align: center;
    transition: .2s;
}

.brand-card:hover {
    border-color: var(--blue);
    box-shadow: 0 10px 25px #183c6810;
    transform: translateY(-3px);
}

.brand-card strong {
    display: block;
    font-size: 14px;
    color: var(--navy);
}

.brand-card small {
    display: block;
    color: var(--muted);
    font-size: 10px;
    margin-top: 6px;
}

.seo-content {
    max-width: 920px;
    margin: 55px auto 0;
}

.seo-content h2 {
    color: var(--navy);
    font-size: 23px;
    margin: 35px 0 12px;
}

.seo-content h3 {
    color: #263c5b;
    font-size: 17px;
    margin: 28px 0 8px;
}

.seo-content p {
    color: #64748b;
    font-size: 13px;
    line-height: 2.6;
    margin: 0 0 17px;
}

.seo-content strong {
    color: #243b5b;
}

.workflow {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
}

.step {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    background: white;
}

.step span {
    display: block;
    color: var(--blue);
    font-size: 11px;
    font-weight: 900;
}

.step h3 {
    color: var(--navy);
    font-size: 14px;
    margin: 10px 0 5px;
}

.step p {
    color: var(--muted);
    font-size: 11px;
    margin: 0;
}

.audience {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.audience-card {
    padding: 30px;
    background: var(--navy);
    border-radius: 14px;
    color: white;
}

.audience-card.light {
    background: #edf4fc;
    color: var(--navy);
    border: 1px solid #dfe9f5;
}

.audience-card h3 {
    font-size: 18px;
    margin: 0 0 12px;
}

.audience-card p {
    font-size: 12px;
    line-height: 2.5;
    color: #c7d5e7;
    margin: 0;
}

.audience-card.light p {
    color: var(--muted);
}

.faq {
    max-width: 850px;
    margin: auto;
}

.faq-item {
    padding: 20px;
    border: 1px solid var(--border);
    border-radius: 11px;
    margin-bottom: 12px;
    background: white;
}

.faq-item h3 {
    font-size: 14px;
    color: var(--navy);
    margin: 0 0 8px;
}

.faq-item p {
    font-size: 12px;
    line-height: 2.3;
    color: var(--muted);
    margin: 0;
}

.cta {
    padding: 55px 0;
    background: var(--navy);
    color: white;
}

.cta-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.cta h2 {
    font-size: 25px;
    margin: 0 0 7px;
}

.cta p {
    color: #b6c5d9;
    font-size: 12px;
    margin: 0;
}

.footer {
    padding: 24px 0;
    border-top: 1px solid var(--border);
    color: #8290a4;
    font-size: 11px;
}

.footer-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

@media (max-width: 900px) {
    .hero-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .features {
        grid-template-columns: repeat(2, 1fr);
    }

    .workflow {
        grid-template-columns: repeat(2, 1fr);
    }

    .brand-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .menu {
        gap: 13px;
    }
}

@media (max-width: 600px) {
    .container {
        width: calc(100% - 28px);
    }

    .header {
        height: auto;
        padding: 14px 0;
        flex-wrap: wrap;
    }

    .logo img {
        width: 145px;
    }

    .menu {
        order: 3;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        padding: 5px 0;
    }

    .header > .button {
        padding: 9px 12px;
    }

    .hero {
        padding: 55px 0;
    }

    .hero h1 {
        font-size: 33px;
    }

    .hero-description {
        font-size: 12px;
    }

    .preview {
        padding: 13px;
    }

    .part {
        padding: 7px;
    }

    .part-icon {
        height: 65px;
    }

    .section {
        padding: 60px 0;
    }

    .section-heading h2 {
        font-size: 22px;
    }

    .features,
    .audience {
        grid-template-columns: 1fr;
    }

    .workflow {
        grid-template-columns: 1fr 1fr;
    }

    .brand-grid {
        grid-template-columns: 1fr 1fr;
    }

    .cta-inner,
    .footer-inner {
        align-items: flex-start;
        flex-direction: column;
    }

    .cta h2 {
        font-size: 21px;
    }
}
</style>
</head>

<body>

<div class="container">
    <header class="header">
        <a class="logo" href="/" aria-label="صفحه اصلی پارت کار">
            <img src="/branding/partcar-horizontal.png" alt="PartCar پارت کار">
        </a>

        <nav class="menu">
            <a href="#features">امکانات</a>
            <a href="#brands">برند خودروها</a>
            <a href="#workflow">نحوه کار</a>
            <a href="#faq">سؤالات متداول</a>
        </nav>

        <a class="button button-primary" href="/login">ورود به سامانه</a>
    </header>
</div>

<main>

<section class="hero">
    <div class="container hero-grid">

        <div>
            <span class="tag">مرجع تخصصی قطعات خودروهای چینی</span>

            <h1>
                قطعه درست را
                <br>
                با <span>کد فنی</span> پیدا کن
            </h1>

            <p class="hero-description">
                پارت‌کار سامانه تخصصی جست‌وجو و شناسایی کد فنی قطعات خودرو است.
                لیست قطعات خودروهای چینی، قطعات مشترک، شماره فنی و اطلاعات سازگاری
                خودروها را در یک محیط یکپارچه بررسی کن.
                از جک و مدیران خودرو تا چری، ام‌وی‌ام، لیفان و KMC.
            </p>

            <div class="hero-actions">
                <a class="button button-primary" href="/login">شروع جست‌وجوی قطعات</a>
                <a class="button button-light" href="#brands">مشاهده خودروها</a>
            </div>

            <div class="hero-benefits">
                <span>جست‌وجوی کد فنی</span>
                <span>شناسایی قطعات مشترک</span>
                <span>بررسی خودروهای سازگار</span>
            </div>
        </div>

        <div class="preview">
            <div class="preview-heading">
                <strong>جست‌وجوی هوشمند قطعات</strong>
                <span class="status">PartCar Catalog</span>
            </div>

            <div class="search-box">
                <span class="search-icon">⌕</span>
                نام قطعه یا کد فنی را وارد کنید
                <span class="search-label">جست‌وجو</span>
            </div>

            <div class="preview-filters">
                <span>انتخاب خودرو</span>
                <span>دسته‌بندی قطعه</span>
                <span>کد فنی</span>
            </div>

            <div class="parts">
                <div class="part">
                    <div class="part-icon">⚙</div>
                    <strong>قطعات موتوری</strong>
                    <small>Engine Parts</small>
                </div>

                <div class="part">
                    <div class="part-icon">◈</div>
                    <strong>قطعات جلوبندی</strong>
                    <small>Suspension</small>
                </div>

                <div class="part">
                    <div class="part-icon">▧</div>
                    <strong>قطعات بدنه</strong>
                    <small>Body Parts</small>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="section" id="features">
    <div class="container">

        <div class="section-heading">
            <span>امکانات پارت‌کار</span>
            <h2>ابزار تخصصی شناسایی قطعات خودرو</h2>
            <p>
                برای فروشندگان قطعات یدکی و تعمیرکارانی که می‌خواهند
                قطعه را دقیق‌تر شناسایی کنند و با اطمینان بیشتری سفارش دهند.
            </p>
        </div>

        <div class="features">

            <article class="feature">
                <span class="feature-number">01 / SEARCH</span>
                <h3>جست‌وجوی کد فنی قطعات</h3>
                <p>جست‌وجو بر اساس نام قطعه، شماره فنی یا مدل خودرو برای دسترسی سریع به اطلاعات قطعات.</p>
            </article>

            <article class="feature">
                <span class="feature-number">02 / CATALOG</span>
                <h3>لیست قطعات خودروهای چینی</h3>
                <p>بررسی گروه‌های مختلف قطعات موتوری، بدنه، جلوبندی، عقب‌بندی و سیستم خنک‌کننده.</p>
            </article>

            <article class="feature">
                <span class="feature-number">03 / FITMENT</span>
                <h3>بررسی سازگاری قطعات</h3>
                <p>مشاهده خودروهای مرتبط با هر قطعه و بررسی کاربرد آن در مدل‌های مختلف.</p>
            </article>

            <article class="feature">
                <span class="feature-number">04 / CROSS CODE</span>
                <h3>قطعات مشترک و جایگزین</h3>
                <p>بررسی کدهای فنی متفاوت و شناسایی قطعات مشترک یا جایگزین در خودروهای مختلف.</p>
            </article>

            <article class="feature">
                <span class="feature-number">05 / VISUAL</span>
                <h3>کاتالوگ تصویری قطعات</h3>
                <p>مشاهده تصاویر و اطلاعات فنی قطعات برای شناسایی دقیق‌تر قطعه موردنظر.</p>
            </article>

            <article class="feature">
                <span class="feature-number">06 / SELLERS</span>
                <h3>معرفی فروشندگان قطعات</h3>
                <p>مشاهده پیشنهادهای ثبت‌شده توسط فروشندگان و دسترسی به لینک فروشگاه آن‌ها.</p>
            </article>

        </div>
    </div>
</section>

<section class="section section-gray" id="brands">
    <div class="container">

        <div class="section-heading">
            <span>بانک اطلاعات خودروها</span>
            <h2>لیست قطعات و کد فنی خودروهای چینی</h2>
            <p>
                برند و مدل خودروی موردنظر را انتخاب کن و اطلاعات قطعات ثبت‌شده
                و کدهای فنی مرتبط با آن را در پارت‌کار جست‌وجو کن.
            </p>
        </div>

        <div class="brand-grid">

            <a href="/login" class="brand-card">
                <strong>قطعات جک</strong>
                <small>JAC J4 / J5 / S3 / S5</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات مدیران خودرو</strong>
                <small>مدل‌های MVM و محصولات چری</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات لیفان</strong>
                <small>Lifan 520 / 620 / 820</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات چری</strong>
                <small>Chery Tiggo / Arrizo</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات ام‌وی‌ام</strong>
                <small>MVM 110 / 315 / X22 / X33 / X55</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات کی‌ام‌سی</strong>
                <small>KMC Vehicles</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات برلیانس</strong>
                <small>Brilliance 220 / 230 / 320 / 330</small>
            </a>

            <a href="/login" class="brand-card">
                <strong>قطعات جیلی و چانگان</strong>
                <small>Geely / Changan</small>
            </a>

        </div>

        <div class="seo-content">

            <h2>کد فنی قطعات خودرو چیست؟</h2>

            <p>
                <strong>کد فنی قطعات خودرو</strong> یا شماره فنی، شناسه‌ای برای
                شناسایی دقیق قطعه در کاتالوگ‌های تخصصی خودرو است.
                هنگام خرید قطعات یدکی، استفاده از شماره فنی در کنار مدل خودرو
                می‌تواند احتمال اشتباه در انتخاب قطعه را کاهش دهد.
            </p>

            <p>
                در خودروهای چینی ممکن است قطعات ظاهراً مشابه، از نظر ابعاد،
                اتصالات یا شماره فنی تفاوت داشته باشند. به همین دلیل انتخاب قطعه
                فقط بر اساس نام عمومی آن همیشه کافی نیست.
                بررسی کد فنی قطعات و خودروهای سازگار، راه دقیق‌تری برای
                شناسایی قطعه موردنیاز است.
            </p>

            <h3>جست‌وجوی کد فنی قطعات خودروهای چینی</h3>

            <p>
                پارت‌کار برای جست‌وجوی اطلاعات قطعات خودروهای چینی طراحی شده است.
                کاربران می‌توانند با وارد کردن نام قطعه، شماره فنی یا انتخاب خودرو،
                اطلاعات ثبت‌شده قطعات مرتبط را بررسی کنند.
                تصاویر، کدهای فنی، خودروهای سازگار و قطعات مشترک از جمله اطلاعات
                کاربردی در شناسایی قطعات هستند.
            </p>

            <h3>لیست قطعات جک و کد فنی خودروهای JAC</h3>

            <p>
                اگر به دنبال <strong>لیست قطعات جک</strong> هستید،
                می‌توانید مدل‌هایی مانند JAC J4، JAC J5، JAC S3 و JAC S5
                را در پارت‌کار جست‌وجو کنید.
                بررسی شماره فنی و اطلاعات سازگاری قطعات به فروشندگان و تعمیرکاران
                کمک می‌کند قطعه مناسب خودرو را دقیق‌تر شناسایی کنند.
            </p>

            <h3>لیست قطعات مدیران خودرو و محصولات MVM</h3>

            <p>
                <strong>لیست قطعات مدیران خودرو</strong> شامل گروه‌های مختلف
                قطعات خودروهای ام‌وی‌ام و محصولات چری است.
                مدل‌هایی مانند MVM 110، MVM 315، MVM 530،
                MVM X22، MVM X33، MVM X55 و خانواده تیگو و آریزو
                از خودروهایی هستند که کاربران برای آن‌ها به اطلاعات فنی
                و شماره قطعات نیاز دارند.
            </p>

            <h3>لیست قطعات لیفان و کد فنی Lifan</h3>

            <p>
                برای شناسایی قطعات خودروهای لیفان، تطبیق مدل خودرو با کد فنی
                اهمیت دارد. Lifan 520، Lifan 620، Lifan 820،
                Lifan X50 و Lifan X60 از مدل‌های شناخته‌شده این برند هستند.
                در پارت‌کار می‌توانید اطلاعات قطعات ثبت‌شده این خودروها را
                جست‌وجو و بررسی کنید.
            </p>

            <h3>قطعات یدکی چینی؛ از موتور تا بدنه و جلوبندی</h3>

            <p>
                بازار <strong>قطعات یدکی چینی</strong> تنوع گسترده‌ای دارد.
                قطعات موتوری، بدنه، چراغ‌ها، سپرها، جلوبندی، سیستم تعلیق،
                قطعات برقی و سیستم خنک‌کننده از گروه‌های مهم قطعات خودرو هستند.
                برای انتخاب صحیح هر قطعه باید مدل خودرو، مشخصات فنی و
                سازگاری آن بررسی شود.
            </p>

            <h3>چرا دانستن کد فنی قطعه مهم است؟</h3>

            <p>
                وقتی کد فنی را بشناسید، راحت‌تر می‌توانید قطعه موردنظر را
                از میان گزینه‌های مشابه تشخیص دهید. همچنین بررسی قطعات مشترک
                بین خودروها می‌تواند به پیدا کردن گزینه‌های جایگزین کمک کند.
                پارت‌کار تلاش می‌کند این اطلاعات را در یک محیط تخصصی و قابل جست‌وجو
                در اختیار فعالان بازار قطعات خودرو قرار دهد.
            </p>

        </div>
    </div>
</section>

<section class="section" id="workflow">
    <div class="container">

        <div class="section-heading">
            <span>شروع کار</span>
            <h2>چطور قطعه موردنظرت را پیدا کنی؟</h2>
            <p>چهار مرحله ساده برای جست‌وجو و شناسایی قطعات خودرو.</p>
        </div>

        <div class="workflow">

            <div class="step">
                <span>مرحله ۰۱</span>
                <h3>انتخاب خودرو</h3>
                <p>برند و مدل خودرو را مشخص کن.</p>
            </div>

            <div class="step">
                <span>مرحله ۰۲</span>
                <h3>جست‌وجوی قطعه</h3>
                <p>نام قطعه یا کد فنی را وارد کن.</p>
            </div>

            <div class="step">
                <span>مرحله ۰۳</span>
                <h3>بررسی اطلاعات</h3>
                <p>کد فنی، تصویر و خودروهای سازگار را ببین.</p>
            </div>

            <div class="step">
                <span>مرحله ۰۴</span>
                <h3>مشاهده فروشندگان</h3>
                <p>پیشنهادهای ثبت‌شده را بررسی کن.</p>
            </div>

        </div>
    </div>
</section>

<section class="section section-gray">
    <div class="container audience">

        <div class="audience-card">
            <h3>برای فروشندگان قطعات یدکی</h3>
            <p>
                دسترسی به اطلاعات فنی، بررسی کدهای جایگزین،
                شناسایی قطعات مشترک و معرفی محصولات فروشگاه.
                پارت‌کار بستری تخصصی برای ارتباط بهتر میان اطلاعات قطعات
                و فروشندگان بازار خودروهای چینی فراهم می‌کند.
            </p>
        </div>

        <div class="audience-card light">
            <h3>برای تعمیرکاران و متخصصان</h3>
            <p>
                جست‌وجوی کد فنی، بررسی قطعات مشترک و پیدا کردن قطعه سازگار
                با مدل خودرو. اطلاعات دقیق‌تر می‌تواند به کاهش خطا در
                شناسایی قطعات و سفارش آن‌ها کمک کند.
            </p>
        </div>

    </div>
</section>

<section class="section" id="faq">
    <div class="container">

        <div class="section-heading">
            <span>سؤالات متداول</span>
            <h2>پرسش‌های رایج درباره پارت‌کار</h2>
        </div>

        <div class="faq">

            <article class="faq-item">
                <h3>چطور کد فنی قطعه خودرو را پیدا کنم؟</h3>
                <p>
                    با انتخاب مدل خودرو و جست‌وجوی نام قطعه یا وارد کردن شماره فنی،
                    می‌توانید اطلاعات قطعات ثبت‌شده مرتبط را بررسی کنید.
                </p>
            </article>

            <article class="faq-item">
                <h3>آیا پارت‌کار لیست قطعات خودروهای چینی را دارد؟</h3>
                <p>
                    بله، پارت‌کار برای جست‌وجو و بررسی اطلاعات قطعات خودروهای چینی
                    طراحی شده است. میزان اطلاعات به مدل خودرو و داده‌های ثبت‌شده بستگی دارد.
                </p>
            </article>

            <article class="faq-item">
                <h3>آیا امکان جست‌وجوی لیست قطعات جک وجود دارد؟</h3>
                <p>
                    می‌توانید مدل‌هایی مانند J4، J5، S3 و S5 را انتخاب کنید
                    و اطلاعات قطعات ثبت‌شده آن‌ها را بررسی کنید.
                </p>
            </article>

            <article class="faq-item">
                <h3>آیا قطعات مدیران خودرو و MVM در سامانه هستند؟</h3>
                <p>
                    اطلاعات قطعات خودروهای MVM و محصولات چری در محدوده کاتالوگ
                    پارت‌کار قرار دارند. اطلاعات قابل مشاهده به داده‌های هر مدل بستگی دارد.
                </p>
            </article>

            <article class="faq-item">
                <h3>آیا می‌توانم قطعات لیفان را جست‌وجو کنم؟</h3>
                <p>
                    امکان جست‌وجو و بررسی اطلاعات قطعات ثبت‌شده مدل‌هایی مانند
                    Lifan 520، 620، 820، X50 و X60 وجود دارد.
                </p>
            </article>

            <article class="faq-item">
                <h3>قطعات مشترک و کدهای جایگزین چه کاربردی دارند؟</h3>
                <p>
                    بعضی قطعات ممکن است با کدهای فنی متفاوت در خودروهای مختلف
                    کاربرد مشترک داشته باشند. برای اطمینان باید مشخصات فنی
                    و سازگاری قطعه بررسی شود.
                </p>
            </article>

            <article class="faq-item">
                <h3>آیا پارت‌کار فروشگاه اینترنتی است؟</h3>
                <p>
                    پارت‌کار سامانه تخصصی جست‌وجو و شناسایی قطعات است.
                    فروشندگان می‌توانند لینک محصولات خود را ثبت کنند تا کاربران
                    پیشنهادهای فروش را بررسی کنند.
                </p>
            </article>

        </div>
    </div>
</section>

<section class="cta">
    <div class="container cta-inner">
        <div>
            <h2>دیگه قطعه رو حدسی انتخاب نکن!</h2>
            <p>با کد فنی جست‌وجو کن، قطعات مشترک رو بشناس و دقیق‌تر خرید کن.</p>
        </div>

        <a href="/login" class="button button-primary">ورود به پارت‌کار</a>
    </div>
</section>

</main>

<footer class="footer">
    <div class="container footer-inner">
        <span>PartCar | پارت‌کار</span>
        <span>سامانه تخصصی کد فنی و قطعات خودروهای چینی</span>
    </div>
</footer>

</body>
</html>
