
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">

  <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            محصولات فروشگاه من
        </h1>

        <p class="mt-2 text-gray-500">
            مدیریت لینک‌ها و تصاویر محصولات ثبت‌شده
        </p>
    </div>

    <div class="flex flex-wrap gap-3">

        {{-- ورود به بانک لنت --}}
        <a href="{{ route('seller.brake-bank.index') }}"
           class="rounded-lg bg-emerald-600 px-5 py-3 text-white transition hover:bg-emerald-700">
            🛞 بانک لنت و فروش لنت
        </a>

        {{-- ثبت محصول عادی --}}
        <a href="{{ route('seller.products.catalog') }}"
           class="rounded-lg bg-blue-600 px-5 py-3 text-white transition hover:bg-blue-700">
            + ثبت محصول جدید
        </a>

    </div>
</div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">
        <table class="w-full min-w-[850px] text-right">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4">تصویر فروشنده</th>
                    <th class="p-4">نام قطعه</th>
                    <th class="p-4">کد فنی</th>
                    <th class="p-4">تعداد کلیک</th>
                    <th class="p-4">وضعیت</th>
                    <th class="p-4">عملیات</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-3">
                            @if($product->image_url)
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="{{ $product->part?->name_fa }}"
                                    class="h-16 w-16 rounded-lg object-contain"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-400">
                                    بدون تصویر
                                </div>
                            @endif
                        </td>

                        <td class="p-3 font-medium">
                            {{ $product->part?->name_fa ?? 'قطعه حذف شده' }}
                        </td>

                        <td class="p-3">
                            {{ $product->part?->technical_code ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ number_format($product->click_count) }}
                        </td>

                        <td class="p-3">
                            @if($product->is_active)
                                <span class="rounded-lg bg-green-100 px-3 py-1 text-green-700">
                                    فعال
                                </span>
                            @else
                                <span class="rounded-lg bg-gray-100 px-3 py-1 text-gray-600">
                                    غیرفعال
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">

                                <a href="{{ $product->product_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="rounded-lg bg-blue-50 px-3 py-2 text-blue-700">
                                    مشاهده لینک
                                </a>

                                <a href="{{ route('seller.products.edit', $product->id) }}"
                                   class="rounded-lg bg-yellow-50 px-3 py-2 text-yellow-700">
                                    ویرایش
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('seller.products.destroy', $product->id) }}"
                                    onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="rounded-lg bg-red-50 px-3 py-2 text-red-700">
                                        حذف
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            هنوز محصولی در فروشگاه خود ثبت نکرده‌اید.
                            <div class="mt-4">
                                <a href="{{ route('seller.products.catalog') }}"
                                   class="text-blue-600">
                                    انتخاب قطعه و ثبت محصول
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>
@endsection
