
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">ثبت لینک فروش قطعه</h1>
        <p class="mt-2 text-gray-500">اطلاعات محصول خود را وارد کنید.</p>
    </div>

    <div class="max-w-2xl rounded-xl border bg-white p-6 shadow-sm">

        <div class="mb-6 rounded-lg bg-gray-50 p-4">
            <h2 class="font-bold">{{ $part->name_fa }}</h2>
            <p class="mt-2 text-sm text-gray-500">
                کد فنی: {{ $part->technical_code ?: '-' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
                دسته‌بندی: {{ $part->category ?: '-' }}
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

        <form method="POST" action="{{ route('seller.products.store') }}">
            @csrf

            <input type="hidden" name="part_id" value="{{ $part->id }}">

            <div class="mb-5">
                <label class="mb-2 block font-medium">
                    لینک صفحه محصول در سایت شما
                </label>

                <input
                    type="url"
                    name="product_url"
                    value="{{ old('product_url') }}"
                    required
                    placeholder="https://example.com/product/..."
                    class="w-full rounded-lg border p-3"
                    dir="ltr"
                >
            </div>

            <div class="mb-5">
                <label class="mb-2 block font-medium">
                    لینک تصویر اختصاصی محصول
                </label>

                <input
                    type="url"
                    name="image_url"
                    value="{{ old('image_url') }}"
                    placeholder="https://example.com/images/product.jpg"
                    class="w-full rounded-lg border p-3"
                    dir="ltr"
                >

                <p class="mt-2 text-xs text-gray-500">
                    این تصویر در فهرست فروشندگان به مشتری نمایش داده می‌شود.
                    وارد کردن آن اختیاری است.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700">
                    ثبت محصول
                </button>

                <a href="{{ route('seller.products.catalog') }}"
                    class="rounded-lg bg-gray-100 px-6 py-3 text-gray-700">
                    بازگشت به کاتالوگ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
