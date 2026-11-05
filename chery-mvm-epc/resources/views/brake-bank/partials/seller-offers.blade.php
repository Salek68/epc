

<div class="seller-offers-section">

    <div class="seller-offers-heading">
        <div>
            <h3>فروشندگان این لنت</h3>
            <p>فروشگاه‌هایی که این لنت را عرضه می‌کنند</p>
        </div>

        <span class="seller-count">
            {{ number_format($sellerCount) }} فروشنده
        </span>
    </div>

    @if($sellerCount > 0)

     

        <div class="seller-offers-grid">

            @foreach($sellerOffers as $offer)

                <div class="seller-offer-card">

                    <div class="seller-offer-top">

                        <div class="seller-store-icon">
                            🏪
                        </div>

                        <div class="seller-store-info">
                            <strong>
                                {{ $offer->sellerStore?->store_name ?? 'فروشگاه فروشنده' }}
                            </strong>

                            <small>
                                فروشنده تأییدشده
                            </small>
                        </div>

                    </div>

                    @if($offer->image_url)
                        <div class="seller-product-image">
                            <img
                                src="{{ $offer->image_url }}"
                                alt="{{ $pad->name }}"
                                loading="lazy"
                            >
                        </div>
                    @endif

                    <div class="seller-brands">
                        <span class="seller-label">برندهای موجود:</span>

                        <div class="seller-brand-list">
                            @forelse($offer->brands as $brand)
                                <span class="seller-brand">
                                    {{ $brand->name }}
                                </span>
                            @empty
                                <span class="seller-muted">
                                    برندی ثبت نشده
                                </span>
                            @endforelse
                        </div>
                    </div>

                    @if($offer->sellerStore?->website_url)
                        <div class="seller-website">
                            <span>وب‌سایت:</span>

                            <a
                                href="{{ $offer->sellerStore->website_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                مشاهده سایت فروشگاه
                            </a>
                        </div>
                    @endif

                    <a
                        href="{{ $offer->product_url }}"
                        target="_blank"
                        rel="nofollow sponsored noopener noreferrer"
                        class="seller-buy-button"
                    >
                        خرید از این فروشگاه
                        ←
                    </a>

                </div>

            @endforeach

        </div>

    @else

        <div class="seller-empty">
            <div style="font-size:35px">🛒</div>
            <strong>هنوز فروشنده‌ای برای این لنت ثبت نشده است.</strong>
            <p>به‌زودی فروشندگان این محصول در این قسمت نمایش داده می‌شوند.</p>
        </div>

    @endif

</div>

<style>
    .seller-offers-section {
        direction: rtl;
        margin-top: 25px;
    }

    .seller-offers-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .seller-offers-heading h3 {
        margin: 0 0 6px;
    }

    .seller-offers-heading p {
        margin: 0;
        color: #8492a6;
        font-size: 13px;
    }

    .seller-count {
        background: #e0f2fe;
        color: #0369a1;
        padding: 8px 13px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: bold;
    }

    .seller-main-image {
        position: relative;
        height: 190px;
        background: #f8fafc;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 18px;
    }

    .seller-main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .seller-main-image span {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(15,23,42,.8);
        color: white;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 11px;
    }

    .seller-offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 15px;
    }

    .seller-offer-card {
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 16px;
        background: #fff;
        color: #1e293b;
    }

    .seller-offer-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .seller-store-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        border-radius: 12px;
        font-size: 20px;
    }

    .seller-store-info strong {
        display: block;
        font-size: 14px;
    }

    .seller-store-info small {
        color: #16a34a;
        font-size: 11px;
    }

    .seller-product-image {
        height: 130px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 14px;
        overflow: hidden;
    }

    .seller-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .seller-label {
        display: block;
        color: #64748b;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .seller-brand-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .seller-brand {
        background: #f1f5f9;
        color: #334155;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
    }

    .seller-muted {
        color: #94a3b8;
        font-size: 12px;
    }

    .seller-website {
        margin-top: 14px;
        font-size: 12px;
    }

    .seller-website span {
        color: #64748b;
    }

    .seller-website a {
        color: #2563eb;
        text-decoration: none;
    }

    .seller-buy-button {
        display: block;
        text-align: center;
        margin-top: 15px;
        padding: 11px;
        border-radius: 9px;
        background: #16a34a;
        color: white !important;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
    }

    .seller-buy-button:hover {
        background: #15803d;
    }

    .seller-empty {
        text-align: center;
        padding: 30px 15px;
        border: 1px dashed #cbd5e1;
        border-radius: 13px;
        color: #64748b;
    }

    .seller-empty p {
        font-size: 12px;
    }
</style>