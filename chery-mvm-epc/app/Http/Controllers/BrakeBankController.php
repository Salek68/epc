<?php

namespace App\Http\Controllers;

use App\Models\BrakePad;
use App\Models\BrakePadVehicle;
use Illuminate\Http\Request;

class BrakeBankController extends Controller
{
    /**
     * صفحه جست‌وجوی خودرو
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $vehicles = BrakePadVehicle::query()
            ->when($q !== '', function ($query) use ($q) {
                $normalized = $this->normalize($q);

                $query->where(function ($sub) use ($q, $normalized) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('normalized_name', 'like', "%{$normalized}%");
                });
            })
            ->withCount('brakePads')
            ->orderBy('name')
            ->limit($q !== '' ? 50 : 12)
            ->get();

        return view('brake-bank.index', compact('q', 'vehicles'));
    }

    /**
     * نمایش لنت‌های جلو و عقب خودرو
     */
    public function vehicle(BrakePadVehicle $vehicle)
    {
        $vehicle->load([
            'brakePads.images',
            'brakePads.compatibilities',
        ]);

        $frontPads = $vehicle->brakePads
            ->where('position', 'front')
            ->values();

        $rearPads = $vehicle->brakePads
            ->where('position', 'rear')
            ->values();

        $unknownPads = $vehicle->brakePads
            ->whereNull('position')
            ->values();

        return view('brake-bank.vehicle', compact(
            'vehicle',
            'frontPads',
            'rearPads',
            'unknownPads'
        ));
    }

    /**
     * صفحه جزئیات لنت
     */
    public function show(BrakePad $pad)
    {
        $pad->load([
            'images',
            'compatibilities',
            'vehicles',
        ]);

        return view('brake-bank.show', compact('pad'));
    }

    /**
     * یکسان‌سازی حروف فارسی و عربی
     */
    private function normalize(string $value): string
    {
        $value = str_replace(
            ['ي', 'ك', 'ة', 'ۀ'],
            ['ی', 'ک', 'ه', 'ه'],
            $value
        );

        return mb_strtolower(
            preg_replace('/\s+/u', ' ', trim($value)),
            'UTF-8'
        );
    }
}