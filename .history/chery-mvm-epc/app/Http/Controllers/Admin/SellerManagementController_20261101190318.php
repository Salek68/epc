<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerStore;
use Illuminate\Http\Request;

class SellerManagementController extends Controller
{
    // نمایش فهرست درخواست‌های فروشندگی
    public function index()
    {
        $sellers = SellerStore::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.sellers.index', compact('sellers'));
    }

    // نمایش جزئیات فروشنده
    public function show($id)
    {
        $seller = SellerStore::with(['user', 'documents'])
            ->findOrFail($id);

        return view('admin.sellers.show', compact('seller'));
    }

    // تأیید فروشنده
    public function approve($id)
    {
        $seller = SellerStore::findOrFail($id);

        $seller->update([
            'status' => 'approved',
            'approved_at' => now(),
            'admin_note' => null,
        ]);

        return redirect()
            ->route('admin.sellers.show', $seller->id)
            ->with('success', 'فروشنده با موفقیت تأیید شد.');
    }

    // رد درخواست فروشندگی
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

        return redirect()
            ->route('admin.sellers.show', $seller->id)
            ->with('success', 'درخواست فروشندگی رد شد.');
    }
}
