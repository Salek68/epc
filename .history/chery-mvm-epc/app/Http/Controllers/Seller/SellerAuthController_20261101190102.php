
<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellerAuthController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        $store = $user->sellerStore;

        if ($store) {
            return redirect()->route('seller.status');
        }

        return view('seller.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'website_url' => 'nullable|url|max:2048',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        if ($user->sellerStore) {
            return redirect()->route('seller.status');
        }

        DB::transaction(function () use ($request, $user) {

            $store = SellerStore::create([
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'owner_name' => $request->owner_name,
                'phone' => $request->phone,
                'website_url' => $request->website_url,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            foreach ($request->file('documents', []) as $document) {

                $path = $document->store(
                    'seller-documents/' . $store->id,
                    'private'
                );

                $store->documents()->create([
                    'document_type' => $document->getClientOriginalExtension(),
                    'file_path' => $path,
                ]);
            }
        });

        return redirect()
            ->route('seller.status')
            ->with('success', 'درخواست فروشندگی شما ثبت شد و در انتظار بررسی است.');
    }

    public function status()
    {
        $user = Auth::user();

        $store = $user->sellerStore;

        if (!$store) {
            return redirect()->route('seller.register');
        }

        return view('seller.status', compact('store'));
    }
}
