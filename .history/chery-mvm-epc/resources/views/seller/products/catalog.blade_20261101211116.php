
@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            انتخاب قطعه برای فروش
        </h1>

        <p class="mt-2 text-gray-500">
            قطعه موردنظر را پیدا کنید و لینک فروش آن را در فروشگاه خود ثبت کنید.
        </p>
    </div>

    {{-- فیلترهای کاتالوگ --}}
    <div class="mb-6 rounded-xl border bg-white p-5 shadow-sm">

        <form method="GET"
              action="{{ route('seller.products.catalog') }}"
              id="catalog-filter-form">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- خودرو --}}
                <div>
                    <label class="mb-2 block font-medium">
                        مدل خودرو
                    </label>

                    <select name="vehicle[]" multiple
                            class="w-full rounded-lg border p-3">

                        @foreach($vehicles as $item)
                            <option value="{{ $item }}"
                                @selected(in_array($item, $selectedVehicles))>
                                {{ $item }}
                            </option>
                        @endforeach

                    </select>

                    <small class="text-gray-400">
                        برای انتخاب چند خودرو Ctrl را نگه دارید.
                    </small>
                </div>

                {{-- دسته‌بندی --}}
                <div>
                    <label class="mb-2 block font-medium">
                        دسته‌بندی
                    </label>

                    <select name="category"
                            class="w-full rounded-lg border p-3">

                        <option value="">همه دسته‌ها</option>

                        @foreach($categories as $item)
                            <option value="{{ $item }}"
                                @selected($category == $item)>
                                {{ $item }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- زیردسته --}}
                <div>
                    <label class="mb-2 block font-medium">
                        زیردسته
                    </label>

                    <select name="subcategory"
                            class="w-full rounded-lg border p-3">

                        <option value="">همه زیردسته‌ها</option>

                        @foreach($subcategories as $item)
                            <option value="{{ $item }}"
                                @selected($subcategory == $item)>
                                {{ $item }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- جست‌وجو --}}
                <div>
                    <label class="mb-2 block font-medium">
                        جست‌وجوی قطعه
                    </label>

                    <input type="text"
                           name="q"
                           value="{{ $q }}"
                           placeholder="نام یا کد فنی قطعه..."
                           class="w-full rounded-lg border p-3">
                </div>

            </div>

            <div class="mt-5 flex flex-wrap gap-3">

                <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700">
                    اعمال فیلترها
                </button>

                <a href="{{ route('seller.products.catalog') }}"
                   class="rounded-lg bg-gray-100 px-6 py-3 text-gray-700 hover:bg-gray-200">
                    پاک کردن فیلترها
                </a>

            </div>

        </form>
    </div>

    {{-- جدول قطعات --}}
    <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">

        <table class="w-full min-w-[900px] text-right">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4">تصویر</th>
                    <th class="p-4">کد فنی</th>
                    <th class="p-4">نام قطعه</th>
                    <th class="p-4">زیردسته</th>
                    <th class="p-4">خودرو</th>
                    <th class="p-4">قیمت مرجع</th>
                    <th class="p-4">عملیات</th>
                </tr>
            </thead>

            <tbody>

                @forelse($parts as $part)

                    <tr class="border-t hover:bg-gray-50">

                        {{-- تصویر کاتالوگ --}}
                        <td class="p-3">
                            @if($part->img_src)
                                <img src="{{ $part->img_src }}"
                                     alt="{{ $part->name_fa }}"
                                     class="h-16 w-16 rounded-lg object-contain">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-400">
                                    بدون تصویر
                                </div>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $part->technical_code }}
                        </td>

                        <td class="p-3 font-medium">
                            {{ $part->name_fa }}
                        </td>

                        <td class="p-3">
                            {{ $part->subcategory ?: '-' }}
                        </td>

                        <td class="p-3">
                            {{ $part->car_model_raw ?: '-' }}
                        </td>

                        <td class="p-3">
                            {{ $part->price ?: '-' }}
                        </td>

                        <td class="p-3">

                            @if(in_array($part->id, $registeredPartIds))

                                <a href="{{ route('seller.products.index') }}"
                                   class="inline-block rounded-lg bg-green-100 px-4 py-2 text-green-700">
                                    مدیریت لینک ثبت‌شده
                                </a>

                            @else

                                <a href="{{ route('seller.products.create', ['part_id' => $part->id]) }}"
                                   class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                                    ثبت لینک فروش
                                </a>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="p-8 text-center text-gray-500">
                            قطعه‌ای با این مشخصات پیدا نشد.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-6">
        {{ $parts->withQueryString()->links() }}
    </div>

</div>

@endsection
