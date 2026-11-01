<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /**
     * نرمال‌سازی خودروهای انتخاب‌شده
     * هم رشته تکی و هم آرایه‌ای از خودروها را پشتیبانی می‌کند.
     */
    private function selectedVehicleNames(Request $request): array
    {
        $vehicles = $request->query('vehicle', []);

        if (!is_array($vehicles)) {
            $vehicles = [$vehicles];
        }

        $vehicles = array_map(
            fn ($vehicle) => trim((string) $vehicle),
            $vehicles
        );

        return array_values(array_unique(array_filter(
            $vehicles,
            fn ($vehicle) => $vehicle !== ''
        )));
    }

    /**
     * صفحه اکسپلورر دسته‌بندی‌ها
     */
    public function explorer()
    {
        $categories = DB::table('parts')
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $sections = [
            [
                'title' => 'بدنه و تزئینات',
                'keywords' => ['بدنه', 'سپر', 'چراغ', 'درب', 'در', 'تزئینات'],
                'icon' => 'car-front',
            ],
            [
                'title' => 'موتور و متعلقات',
                'keywords' => ['موتور', 'موتوری', 'پیشرانه'],
                'icon' => 'settings',
            ],
            [
                'title' => 'گیربکس و انتقال قدرت',
                'keywords' => ['گیربکس', 'انتقال قدرت', 'کلاچ'],
                'icon' => 'cog',
            ],
            [
                'title' => 'جلوبندی و تعلیق',
                'keywords' => ['جلوبندی', 'تعلیق', 'فرمان'],
                'icon' => 'settings',
            ],
            [
                'title' => 'عقب‌بندی',
                'keywords' => ['عقب بندی', 'عقب‌بندی', 'اکسل عقب'],
                'icon' => 'settings',
            ],
            [
                'title' => 'زیر‌بندی و ترمز',
                'keywords' => ['زیر بندی', 'زیر‌بندی', 'ترمز'],
                'icon' => 'wrench',
            ],
            [
                'title' => 'برق و الکترونیک',
                'keywords' => ['برق', 'الکتریکی', 'الکترونیک'],
                'icon' => 'zap',
            ],
            [
                'title' => 'خنک‌کاری و تهویه',
                'keywords' => ['خنک', 'رادیاتور', 'تهویه', 'کولر'],
                'icon' => 'wind',
            ],
            [
                'title' => 'سوخت‌رسانی',
                'keywords' => ['سوخت', 'بنزین', 'انژکتور'],
                'icon' => 'fuel',
            ],
            [
                'title' => 'داخلی و کابین',
                'keywords' => ['داخلی', 'کابین', 'داشبورد'],
                'icon' => 'armchair',
            ],
        ];

        $sections = collect($sections)->map(function ($section) use ($categories) {
            $section['category'] = $categories->first(
                function ($category) use ($section) {
                    foreach ($section['keywords'] as $keyword) {
                        if (mb_stripos($category, $keyword) !== false) {
                            return true;
                        }
                    }

                    return false;
                }
            );

            return $section;
        });

        return view('catalog.explorer', compact('sections'));
    }

    /**
     * صفحه اصلی کاتالوگ و جست‌وجوی قطعات
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));

        // پشتیبانی از انتخاب یک یا چند خودرو
        $selectedVehicles = $this->selectedVehicleNames($request);

        // سازگاری با قالب‌هایی که هنوز از متغیر vehicle استفاده می‌کنند
        $vehicle = count($selectedVehicles) === 1
            ? $selectedVehicles[0]
            : '';

        $parts = Part::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('technical_code', 'like', "%{$q}%")
                        ->orWhere('logistics_code', 'like', "%{$q}%")
                        ->orWhere('name_fa', 'like', "%{$q}%");
                });
            })
            ->when(!empty($selectedVehicles), function ($query) use ($selectedVehicles) {
                $query->whereHas('vehicleModels', function ($sub) use ($selectedVehicles) {
                    $sub->whereIn('vehicle_models.name', $selectedVehicles);
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderBy('name_fa')
            ->paginate(25)
            ->withQueryString();

        $categories = DB::table('parts')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $vehicles = VehicleModel::query()
            ->orderBy('name')
            ->pluck('name');

        return view('catalog.index', compact(
            'parts',
            'q',
            'vehicle',
            'selectedVehicles',
            'category',
            'categories',
            'vehicles'
        ));
    }

    /**
     * صفحه دسته‌بندی و زیردسته‌های قطعات
     */
    public function category(Request $request)
    {
        $category = trim((string) $request->query('category', ''));
        $subcategory = trim((string) $request->query('subcategory', ''));
        $q = trim((string) $request->query('q', ''));

        // پشتیبانی از انتخاب چند خودرو
        $selectedVehicles = $this->selectedVehicleNames($request);

        // سازگاری با قالب‌های قدیمی
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

        // جست‌وجوی عبارت‌های چندکلمه‌ای
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

        // فهرست دسته‌های اصلی
        $categories = DB::table('parts')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // فهرست زیردسته‌های دسته انتخاب‌شده
        $subcategories = collect();

        if ($category !== '') {
            $subcategories = DB::table('parts')
                ->where('category', $category)
                ->whereNotNull('subcategory')
                ->where('subcategory', '<>', '')
                ->distinct()
                ->orderBy('subcategory')
                ->pluck('subcategory');
        }

        // فهرست مدل‌های خودرو
        $vehicles = VehicleModel::query()
            ->orderBy('name')
            ->pluck('name');

        // اطلاعات قطعه انتخاب‌شده در مدل سه‌بعدی
        $modelPartId = trim(
            (string) $request->query('model_part_id', '')
        );

        $modelPartMappings = [
            '819' => [
                'q' => 'درب عقب سمت راست',
                'category' => '',
                'subcategory' => '',
            ],
        ];

        $selectedModelPart = $modelPartMappings[$modelPartId] ?? null;

        return view('catalog.category', compact(
            'parts',
            'category',
            'subcategory',
            'vehicle',
            'selectedVehicles',
            'q',
            'categories',
            'subcategories',
            'vehicles',
            'modelPartId',
            'selectedModelPart'
        ));
    }

    /**
     * صفحه جزئیات یک قطعه
     */
    public function show(string $id)
    {
        $part = Part::findOrFail($id);

        $vehicleModels = $part->vehicleModels()
            ->orderBy('name')
            ->get();

        $replacementCodes = preg_split(
            '/[,،;؛\r\n]+/u',
            (string) ($part->replace_raw ?? '')
        );

        $replacementCodes = array_values(array_unique(array_filter(
            array_map('trim', $replacementCodes)
        )));

        $replacementParts = collect();

        if (!empty($replacementCodes)) {
            $replacementParts = Part::query()
                ->where(function ($query) use ($replacementCodes) {
                    $query->whereIn('technical_code', $replacementCodes)
                        ->orWhereIn('logistics_code', $replacementCodes);
                })
                ->limit(100)
                ->get();
        }

        return view('catalog.show', compact(
            'part',
            'vehicleModels',
            'replacementCodes',
            'replacementParts'
        ));
    }
}
