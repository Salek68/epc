<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\SellerProduct;
use App\Models\SellerStore;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SellerProductController extends Controller
{
    /**
     * دریافت فروشگاه تأییدشده فروشنده
     */
    private function approvedStore(): SellerStore
    {
        $store = Auth::user()->sellerStore;

        if (!$store) {
            abort(403, 'ابتدا باید فروشگاه خود را ثبت کنید.');
        }

        if (!$store->isApproved()) {
            abort(403, 'فروشگاه شما هنوز تأیید نشده است.');
        }

        return $store;
    }

    /**
     * کاتالوگ قطعات برای فروشنده
     */
    public function catalog(Request $request)
    {
        $this->approvedStore();

        $category = trim((string) $request->query('category', ''));
        $subcategory = trim((string) $request->query('subcategory', ''));
        $q = trim((string) $request->query('q', ''));

        $selectedVehicles = $request->query('vehicle', []);

        if (!is_array($selectedVehicles)) {
            $selectedVehicles = [$selectedVehicles];
        }

        $selectedVehicles = array_values(array_unique(array_filter(
            array_map(fn ($item) => trim((string) $item), $selectedVehicles)
        )));

        $vehicle = count($selectedVehicles) === 1
            ? $selectedVehicles[0]
            : '';

        $partsQuery = Part::query()
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($subcategory !== '', function ($query) use ($subcategory) {
                $query->where('subcategory', $subcategory);
            })
            ->when(!empty($selectedVehicles), function ($query) use ($selectedVehicles) {
                $query->whereHas('vehicleModels', function ($sub) use ($selectedVehicles) {
                    $sub->whereIn('vehicle_models.name', $selectedVehicles);
                });
            });

        if ($q !== '') {
            $words = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY);

            $partsQuery->where(function ($sub) use ($words) {
                foreach ($words as $word) {
                    $sub->where(function ($inner) use ($word) {
                        $inner->where('technical_code', 'like', "%{$word}%")
                            ->orWhere('logistics_code', 'like', "%{$word}%")
                            ->orWhere('name_fa', 'like', "%{$word}%");
                    });
                }
            });
        }

        $parts = $partsQuery
            ->orderBy('name_fa')
            ->paginate(30)
            ->withQueryString();

        $categories = Part::query()
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $subcategories = collect();

        if ($category !== '') {
            $subcategories = Part::query()
                ->where('category', $category)
                ->whereNotNull('subcategory')
                ->where('subcategory', '<>', '')
                ->distinct()
                ->orderBy('subcategory')
                ->pluck('subcategory');
        }

        $vehicles = VehicleModel::query()
            ->orderBy('name')
            ->pluck('name');

        $store = Auth::user()->sellerStore;

        $registeredPartIds = $store->products()
            ->pluck('part_id')
            ->all();

        return view('seller.products.catalog', compact(
            'parts',
            'category',
            'subcategory',
            'vehicle',
            'selectedVehicles',
            'q',
            'categories',
            'subcategories',
            'vehicles',
            'registeredPartIds'
        ));
    }

    /**
     * فهرست محصولات ثبت‌شده توسط فروشنده
     */
    public function index()
    {
        $store = $this->approvedStore();

        $products = $store->products()
            ->with('part')
            ->latest()
            ->paginate(20);

        return view('seller.products.index', compact('products'));
    }

    /**
     * فرم ثبت لینک فروش برای یک قطعه
     */
    public function create(Request $request)
    {
        $this->approvedStore();

        $part = Part::findOrFail($request->query('part_id'));

        return view('seller.products.create', compact('part'));
    }

    /**
     * ذخیره لینک فروش
     */
    public function store(Request $request)
    {
        $store = $this->approvedStore();

        $validated = $request->validate([
            'part_id' => ['required', 'integer', 'exists:parts,id'],
            'product_url' => ['required', 'url', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        SellerProduct::updateOrCreate(
            [
                'seller_store_id' => $store->id,
                'part_id' => $validated['part_id'],
            ],
            [
                'product_url' => $validated['product_url'],
                'image_url' => $validated['image_url'] ?? null,
                'is_active' => true,
            ]
        );

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'لینک فروش محصول با موفقیت ثبت شد.');
    }

    /**
     * فرم ویرایش لینک فروش
     */
    public function edit(SellerProduct $product)
    {
        $store = $this->approvedStore();

        abort_unless($product->seller_store_id === $store->id, 403);

        $part = $product->part;

        return view('seller.products.edit', compact('product', 'part'));
    }

    /**
     * به‌روزرسانی لینک فروش
     */
    public function update(Request $request, SellerProduct $product)
    {
        $store = $this->approvedStore();

        abort_unless($product->seller_store_id === $store->id, 403);

        $validated = $request->validate([
            'product_url' => ['required', 'url', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['required', Rule::in(['0', '1', 0, 1])],
        ]);

        $product->update([
            'product_url' => $validated['product_url'],
            'image_url' => $validated['image_url'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ]);

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'اطلاعات محصول به‌روزرسانی شد.');
    }

    /**
     * حذف لینک فروش
     */
    public function destroy(SellerProduct $product)
    {
        $store = $this->approvedStore();

        abort_unless($product->seller_store_id === $store->id, 403);

        $product->delete();

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'محصول از فهرست فروشگاه حذف شد.');
    }

    /**
     * ثبت کلیک مشتری و انتقال به سایت فروشنده
     */
  public function visit($id)
{
    $product = \App\Models\SellerProduct::query()
        ->with('store')
        ->where('id', $id)
        ->where('is_active', true)
        ->whereHas('store', function ($query) {
            $query->where('status', 'approved');
        })
        ->firstOrFail();

    // بررسی معتبر بودن لینک فروشنده
    $url = $product->product_url;

    if (
        !filter_var($url, FILTER_VALIDATE_URL) ||
        !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'])
    ) {
        abort(404, 'لینک محصول معتبر نیست.');
    }

    // ثبت تعداد کلیک
    $product->increment('click_count');

    // ثبت اطلاعات کلیک در جدول تاریخچه
    \App\Models\SellerProductClick::create([
        'seller_product_id' => $product->id,
        'clicked_at' => now(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    // انتقال مشتری به سایت فروشنده
    return redirect()->away($url);
}
}
