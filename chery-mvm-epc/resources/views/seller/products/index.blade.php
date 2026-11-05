
@extends('layouts.app')

@section('content')

<div class="container" dir="rtl">

    {{-- عنوان صفحه --}}
    <div class="page-heading">
        <div>
            <h1>محصولات فروشگاه من</h1>
            <p>مدیریت محصولات، لینک‌های خرید و تصاویر ثبت‌شده در فروشگاه</p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('seller.brake-bank.index') }}" class="btn">
              ثبت در بانک لنت
            </a>

            <a href="{{ route('seller.products.catalog') }}" class="btn btn-primary">
                + ثبت محصول جدید
            </a>

        </div>
    </div>


    {{-- پیام موفقیت --}}
    @if(session('success'))
        <div class="card" style="border-right:4px solid #16a34a; margin-bottom:20px; color:#166534;">
            {{ session('success') }}
        </div>
    @endif


    {{-- خطاهای اعتبارسنجی --}}
    @if($errors->any())
        <div class="card" style="border-right:4px solid #dc2626; margin-bottom:20px; color:#b91c1c;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- آمار محصولات --}}
    <div class="stats" style="margin-bottom:24px;">

        <div class="card">
            <div class="text-muted">تعداد کل محصولات</div>
            <div class="text-2xl font-bold mt-2">
                {{ number_format($products->total()) }}
            </div>
        </div>

        <div class="card">
            <div class="text-muted">محصولات فعال در این صفحه</div>
            <div class="text-2xl font-bold mt-2" style="color:#16a34a;">
                {{ number_format($products->getCollection()->where('is_active', true)->count()) }}
            </div>
        </div>

        <div class="card">
            <div class="text-muted">کلیک محصولات در این صفحه</div>
            <div class="text-2xl font-bold mt-2">
                {{ number_format($products->getCollection()->sum('click_count')) }}
            </div>
        </div>

    </div>


    {{-- جدول محصولات --}}
    <div class="card">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h2 class="font-bold text-lg">فهرست محصولات ثبت‌شده</h2>

            <span class="text-muted text-sm">
                صفحه {{ $products->currentPage() }} از {{ $products->lastPage() ?: 1 }}
            </span>
        </div>

        <div class="table-wrap">

            <table>
                <thead>
                    <tr>
                        <th>تصویر</th>
                        <th>نام قطعه</th>
                        <th>کد فنی</th>
                        <th>تعداد کلیک</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>

                            {{-- تصویر --}}
                            <td>
                                @if($product->image_url)
                                    <img
                                        src="{{ $product->image_url }}"
                                        alt="{{ $product->part?->name_fa }}"
                                        loading="lazy"
                                        style="width:65px;height:65px;object-fit:contain;border-radius:10px;background:#f8fafc;padding:5px;"
                                    >
                                @else
                                    <div style="width:65px;height:65px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:11px;color:#64748b;">
                                        بدون تصویر
                                    </div>
                                @endif
                            </td>


                            {{-- نام قطعه --}}
                            <td>
                                <strong>
                                    {{ $product->part?->name_fa ?? 'قطعه حذف شده' }}
                                </strong>
                            </td>


                            {{-- کد فنی --}}
                            <td>
                                <span style="direction:ltr;display:inline-block;">
                                    {{ $product->part?->technical_code ?? '-' }}
                                </span>
                            </td>


                            {{-- کلیک --}}
                            <td>
                                <strong>
                                    {{ number_format($product->click_count) }}
                                </strong>
                            </td>


                            {{-- وضعیت --}}
                            <td>
                                @if($product->is_active)
                                    <span style="background:#dcfce7;color:#166534;padding:6px 12px;border-radius:20px;font-size:12px;">
                                        ● فعال
                                    </span>
                                @else
                                    <span style="background:#f1f5f9;color:#64748b;padding:6px 12px;border-radius:20px;font-size:12px;">
                                        ● غیرفعال
                                    </span>
                                @endif
                            </td>


                            {{-- عملیات --}}
                            <td>
                                <div class="flex flex-wrap gap-2">

                                    @if($product->product_url)
                                        <a
                                            href="{{ $product->product_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn"
                                        >
                                            مشاهده لینک
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route('seller.products.edit', $product->id) }}"
                                        class="btn"
                                    >
                                        ✏️ ویرایش
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('seller.products.destroy', $product->id) }}"
                                        onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn"
                                            style="background:#fee2e2;color:#b91c1c;border-color:#fecaca;"
                                        >
                                            حذف
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center;padding:45px 15px;">

                                <div style="font-size:42px;margin-bottom:12px;">
                                    📦
                                </div>

                                <strong>هنوز محصولی ثبت نکرده‌اید.</strong>

                                <p class="text-muted mt-2">
                                    برای شروع، قطعه موردنظر را از کاتالوگ انتخاب کنید.
                                </p>

                                <div class="mt-4">
                                    <a href="{{ route('seller.products.catalog') }}" class="btn btn-primary">
                                        + انتخاب قطعه و ثبت محصول
                                    </a>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>


        {{-- صفحه‌بندی --}}
        @if($products->hasPages())
            <div class="mt-5">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
