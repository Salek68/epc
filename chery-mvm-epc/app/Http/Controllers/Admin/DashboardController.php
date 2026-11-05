<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerStore;
use App\Models\SubscriptionTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check() && auth()->user()->is_admin, 403);

        $pendingSellers = SellerStore::where('status', 'pending')->count();

        $approvedSellers = SellerStore::where('status', 'approved')->count();

        $pendingTransactions = SubscriptionTransaction::where('status', 'pending')->count();

        $approvedTransactions = SubscriptionTransaction::where('status', 'approved')->count();

        return view('admin.dashboard', compact(
            'pendingSellers',
            'approvedSellers',
            'pendingTransactions',
            'approvedTransactions'
        ));
    }
}
