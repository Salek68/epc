<div class="card" style="border:1px solid #e2e8f0;box-shadow:none;">

    <div class="product-showcase">

        {{-- تصاویر --}}
        <div>
            @php
                $primaryImage = $pad->images
                    ->firstWhere('is_primary', true)
                    ?? $pad->images->first();
            @endphp

            <div class="product-showcase-image">
                @if($primaryImage)
                    <img
                        src="{{ asset('storage/' . $primaryImage->image_path) }}"
                        alt="{{ $pad->name }}"
                        loading="lazy"
                    >
                @else
                    <div class="product-image-placeholder">
                        <span>🛞</span>
                        <p>تصویری برای این لنت ثبت نشده است</p>
                    </div>
                @endif
            </div>

            @if($pad->images->count() > 1)
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px;">
                    @foreach($pad->images as $image)
                        <a
                            href="{{ asset('storage/' . $image->image_path) }}"
                            target="_blank"
                            style="width:65px;height:65px;border:1px solid #e2e8f0;border-radius:9px;overflow:hidden;"
                        >
                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $pad->name }}"
                                style="width:100%;height:100%;object-fit:contain;"
                            >
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- مشخصات --}}
        <div class="product-showcase-info">

            <span class="badge">
                {{ $pad->position === 'front' ? 'لنت جلو' : ($pad->position === 'rear' ? 'لنت عقب' : 'موقعیت نامشخص') }}
            </span>

            <h2>{{ $pad->name }}</h2>

            <div class="showcase-code">
                کد فنی:
                <strong>{{ $pad->technical_code }}</strong>
            </div>

            <div class="showcase-price">
                <span>قیمت فعلی</span>

                <strong>
                    @if($pad->price !== null)
                        {{ number_format($pad->price) }}
                        <small>تومان</small>
                    @else
                        قیمت ثبت نشده
                    @endif
                </strong>
            </div>

            @if($pad->carton_quantity)
                <div class="detail-row">
                    <span class="detail-label">تعداد در کارتن</span>
                    <div class="detail-value">
                        {{ $pad->carton_quantity }}
                    </div>
                </div>
            @endif

            <div class="form-actions">
                <a
                    href="{{ route('brake-bank.pad.show', $pad->id) }}"
                    class="btn"
                >
                    مشاهده مشخصات کامل
                </a>
            </div>
        </div>
    </div>

    {{-- سایر خودروهای مشترک --}}
    @if($pad->compatibilities->isNotEmpty())
        <div style="border-top:1px solid #e2e8f0;padding-top:15px;margin-top:10px;">
            <strong style="display:block;margin-bottom:10px;">
                سایر مشترکات این لنت
            </strong>

            <div class="chips">
                @foreach($pad->compatibilities as $compatibility)
                    <span class="chip">
                        {{ $compatibility->vehicle_name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- خودروهای متصل در بانک --}}
    @if($pad->vehicles->isNotEmpty())
        <div style="padding-top:12px;">
            <strong style="display:block;margin-bottom:10px;">
                خودروهای ثبت‌شده برای این کد
            </strong>

            <div class="chips">
                @foreach($pad->vehicles as $compatibleVehicle)
                    <a
                        href="{{ route('brake-bank.vehicle', $compatibleVehicle->id) }}"
                        class="chip"
                    >
                        {{ $compatibleVehicle->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>