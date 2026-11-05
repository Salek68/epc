
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            ویرایش محصول فروشگاه
        </h1>
    </div>

    <div class="max-w-2xl rounded-xl border bg-white p-6 shadow-sm">

        <div class="mb-6 rounded-lg bg-gray-50 p-4">
            <h2 class="font-bold">{{ $part?->name_fa ?? 'قطعه' }}</h2>
            <p class="mt-2 text-sm text-gray-500">
                کد فنی: {{ $part?->technical_code ?? '-' }}
            </p>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('seller.products.update', $product->id) }}"
        >
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="mb-2 block font-medium">
                    لینک صفحه محصول
                </label>

                <input
                    type="url"
                    name="product_url"
                    value="{{ old('product_url', $product->product_url) }}"
                    required
                    class="w-full rounded-lg border p-3"
                    dir="ltr"
                >
            </div>

            <div class="mb-5">
                <label class="mb-2 block font-medium">
                    لینک تصویر اختصاصی
                </label>

                <input
                    type="url"
                    name="image_url"
                    value="{{ old('image_url', $product->image_url) }}"
                    placeholder="https://example.com/image.jpg"
                    class="w-full rounded-lg border p-3"
                    dir="ltr"
                >
            </div>

            @if($product->image_url)
                <div class="mb-5">
                    <p class="mb-2 text-sm text-gray-500">تصویر فعلی:</p>
                    <img
                        src="{{ $product->image_url }}"
                        alt="تصویر محصول"
                        class="h-32 w-32 rounded-lg border object-contain"
                    >
                </div>
            @endif

            <div class="mb-6">
                <label class="mb-2 block font-medium">
                    وضعیت نمایش محصول
                </label>

                <select name="is_active" class="w-full rounded-lg border p-3">
                    <option value="1"
                        @selected((string) old('is_active', (int) $product->is_active) === '1')>
                        فعال
                    </option>

                    <option value="0"
                        @selected((string) old('is_active', (int) $product->is_active) === '0')>
                        غیرفعال
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-6 py-3 text-white">
                    ذخیره تغییرات
                </button>

                <a href="{{ route('seller.products.index') }}"
                    class="rounded-lg bg-gray-100 px-6 py-3 text-gray-700">
                    بازگشت
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
