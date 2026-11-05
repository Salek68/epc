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

    public function show(SellerStore $seller)
    {
        $seller->load(['user', 'documents']);

        return view('admin.sellers.show', compact('seller'));
    }

    public function approve(SellerStore $seller)
    {
        $seller->update([
            'status' => 'approved',
            'approved_at' => now(),
            'admin_note' => null,
        ]);

        return back()->with('success', 'فروشگاه با موفقیت تأیید شد.');
    }

    public function reject(Request $request, SellerStore $seller)
    {
        $request->validate([
            'admin_note' => 'required|string|max:2000',
        ]);

        $seller->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'approved_at' => null,
        ]);

        return back()->with('success', 'درخواست فروشندگی رد شد.');
    }
}
