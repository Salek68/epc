<?php

namespace App\Http\Controllers;

use App\Models\SellerStore;
use App\Models\SubscriptionTransaction;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    
public function my()
{
    $user = auth()->user();

    $subscriptions = $user->subscriptions()
        ->latest('created_at')
        ->get();

    $transactions = $user->subscriptionTransactions()
        ->latest()
        ->get();

    $activeUserSubscription = $subscriptions
        ->filter(fn ($subscription) =>
            $subscription->role === 'user' && $subscription->isValid()
        )
        ->sortByDesc('expires_at')
        ->first();

    $activeSellerSubscription = $subscriptions
        ->filter(fn ($subscription) =>
            $subscription->role === 'seller' && $subscription->isValid()
        )
        ->sortByDesc('expires_at')
        ->first();

    return view('subscriptions.my', compact(
        'subscriptions',
        'transactions',
        'activeUserSubscription',
        'activeSellerSubscription'
    ));
}

    public function index()
    {
        $user = auth()->user();

        $isSeller = SellerStore::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        $plans = config('subscriptions');

        return view('subscriptions.index', compact(
            'plans',
            'isSeller'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:user,seller',
            'plan' => 'required|in:monthly,yearly',
        ]);

        $user = auth()->user();
        $role = $request->role;
        $planKey = $request->plan;

        // فروشنده باید قبلاً توسط مدیر تأیید شده باشد
        if ($role === 'seller') {
            $isSeller = SellerStore::where('user_id', $user->id)
                ->where('status', 'approved')
                ->exists();

            if (!$isSeller) {
                return back()->withErrors([
                    'role' => 'برای خرید اشتراک فروشنده، ابتدا باید فروشندگی شما تأیید شود.'
                ]);
            }
        }

        $plan = config("subscriptions.$role.$planKey");

        if (!$plan) {
            return back()->withErrors([
                'plan' => 'پلن انتخاب‌شده معتبر نیست.'
            ]);
        }

        // جلوگیری از ثبت درخواست تکراری در انتظار
        $pending = SubscriptionTransaction::where('user_id', $user->id)
            ->where('role', $role)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return back()->withErrors([
                'plan' => 'شما برای این نوع اشتراک یک درخواست در انتظار بررسی دارید.'
            ]);
        }

        $transaction = SubscriptionTransaction::create([
            'user_id' => $user->id,
            'role' => $role,
            'duration_days' => $plan['duration_days'],
            'amount' => $plan['price'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('subscriptions.payment', $transaction->id)
            ->with('success', 'درخواست اشتراک شما ثبت شد.');
    }

    public function payment($id)
    {
        $transaction = SubscriptionTransaction::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        return view('subscriptions.payment', compact('transaction'));
    }

    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $transaction = SubscriptionTransaction::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $path = $request->file('receipt')
            ->store('subscription-receipts', 'public');

        $transaction->update([
            'document_path' => $path,
        ]);

        return redirect()
            ->route('subscriptions.payment', $transaction->id)
            ->with('success', 'فیش پرداخت با موفقیت ارسال شد و منتظر بررسی مدیریت است.');
    }
}
