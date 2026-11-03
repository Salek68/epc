<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\BrakeBankBrand;
use App\Models\BrakeBankSellerOffer;
use App\Models\BrakePad;
use App\Models\SellerStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrakeBankSellerController extends Controller
{
    /**
     * نمایش لنت‌های بانک برای فروشنده
     */
    public function index(Request $request)
    {
        $sellerStore = $this->getSellerStore();

        $search = trim((string) $request->query('q', ''));

        $pads = BrakePad::query()
            ->with([
                'images',
                'vehicles',

                // فقط پیشنهادهای فروشگاه فعلی
                'sellerOffers' => function ($query) use ($sellerStore) {
                    $query->where(
                        'seller_store_id',
                        $sellerStore->id
                    )->with('brands');
                },
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('technical_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $brands = BrakeBankBrand::query()
            ->orderBy('name')
            ->get();

        return view('seller.brake-bank.index', compact(
            'pads',
            'search',
            'brands'
        ));
    }

    /**
     * ثبت یا ویرایش اعلام آمادگی فروشنده
     */
    public function store(Request $request, BrakePad $brakePad)
    {
        $sellerStore = $this->getSellerStore();

        $validated = $request->validate([
            'product_url' => [
                'required',
                'url',
                'max:2048',
            ],

            'image_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'brand_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'brand_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:brake_bank_brands,id',
            ],
        ], [
            'product_url.required' => 'لینک محصول را وارد کنید.',
            'product_url.url' => 'لینک محصول معتبر نیست.',
            'product_url.max' => 'لینک محصول بیش از حد طولانی است.',

            'image_url.url' => 'لینک تصویر معتبر نیست.',
            'image_url.max' => 'لینک تصویر بیش از حد طولانی است.',

            'brand_ids.required' => 'حداقل یک برند انتخاب کنید.',
            'brand_ids.array' => 'فرمت انتخاب برندها معتبر نیست.',
            'brand_ids.min' => 'حداقل یک برند انتخاب کنید.',

            'brand_ids.*.integer' => 'شناسه یکی از برندها معتبر نیست.',
            'brand_ids.*.distinct' => 'یک برند را چند بار انتخاب کرده‌اید.',
            'brand_ids.*.exists' => 'یکی از برندهای انتخاب‌شده معتبر نیست.',
        ]);

        DB::transaction(function () use (
            $validated,
            $sellerStore,
            $brakePad
        ) {
            $offer = BrakeBankSellerOffer::updateOrCreate(
                [
                    'seller_store_id' => $sellerStore->id,
                    'brake_pad_id' => $brakePad->id,
                ],
                [
                    'product_url' => $validated['product_url'],
                    'image_url' => $validated['image_url'] ?? null,

                    // تأیید نهایی فقط توسط مدیر
                    'status' => 'approved',

                    // پاک کردن یادداشت بررسی قبلی
                    'admin_note' => null,
                ]
            );

            // ثبت برندهای انتخاب‌شده
            $offer->brands()->sync($validated['brand_ids']);
        });

        return redirect()
            ->route('seller.brake-bank.index')
            ->with(
                'success',
                'اطلاعات فروش لنت با موفقیت ثبت شد و پس از بررسی مدیر فعال می‌شود.'
            );
    }

    /**
     * نمایش درخواست‌های ثبت‌شده فروشنده
     */
    public function offers()
    {
        $sellerStore = $this->getSellerStore();

        $offers = BrakeBankSellerOffer::query()
            ->with([
                'brakePad.images',
                'brands',
            ])
            ->where('seller_store_id', $sellerStore->id)
            ->latest()
            ->paginate(20);

        return view(
            'seller.brake-bank.offers',
            compact('offers')
        );
    }

    /**
     * دریافت فروشگاه تأییدشده کاربر جاری
     */
    private function getSellerStore(): SellerStore
    {
        return SellerStore::query()
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();
    }
}
