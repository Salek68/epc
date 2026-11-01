
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerStore;
use Illuminate\Http\Request;

class SellerManagementController extends Controller
{
    public function index()
    {
        $sellers = SellerStore::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.sellers.index', compact('sellers'));
    }

    public function show($id)
    {
        $seller = SellerStore::with(['user', 'documents'])
            ->findOrFail($id);

        return view('admin.sellers.show', compact('seller'));
    }

    public function approve($id)
    {
        $seller = SellerStore::findOrFail($id);

        if ($seller->status === 'approved') {
            return back()->with('success', 'این فروشنده قبلاً تأیید شده است.');
        }

        $seller->update([
            'status' => 'approved',
            'approved_at' => now(),
            'admin_note' => null,
        ]);

        return back()->with('success', 'فروشنده تأیید شد.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:2000',
        ]);

        $seller = SellerStore::findOrFail($id);

        $seller->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'approved_at' => null,
        ]);

        return back()->with('success', 'درخواست فروشندگی رد شد.');
    }
}
