<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionTransactionController extends Controller
{
    
public function index()
{
    abort_unless(auth()->check() && auth()->user()->is_admin, 403);

    $transactions = \App\Models\SubscriptionTransaction::with('user')
        ->latest()
        ->paginate(20);

    return view('admin.subscriptions.index', compact('transactions'));
}

    public function approve($id)
    {
        // فقط مدیر اجازه تأیید دارد
        abort_unless(auth()->check() && auth()->user()->is_admin, 403);

        DB::transaction(function () use ($id) {

            // قفل تراکنش برای جلوگیری از تأیید هم‌زمان
            $transaction = SubscriptionTransaction::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'pending') {
                abort(422, 'این تراکنش قبلاً بررسی شده است.');
            }

            // پیدا کردن آخرین اشتراک معتبر همین کاربر و همین نوع حساب
            $currentSubscription = Subscription::where('user_id', $transaction->user_id)
                ->where('role', $transaction->role)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->orderByDesc('expires_at')
                ->lockForUpdate()
                ->first();

            // اگر اشتراک قبلی هنوز اعتبار دارد، تمدید از انتهای آن شروع می‌شود
            $startsAt = $currentSubscription
                ? $currentSubscription->expires_at->copy()
                : now();

            $expiresAt = $startsAt->copy()->addDays(
                $transaction->duration_days
            );

            // ثبت اشتراک جدید
            Subscription::create([
                'user_id' => $transaction->user_id,
                'transaction_id' => $transaction->id,
                'role' => $transaction->role,
                'duration_days' => $transaction->duration_days,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'status' => 'active',
            ]);

            // تأیید تراکنش
            $transaction->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'پرداخت تأیید شد و اشتراک با موفقیت فعال گردید.'
        );
    }

    public function reject(Request $request, $id)
    {
        abort_unless(auth()->check() && auth()->user()->is_admin, 403);

        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $transaction = SubscriptionTransaction::whereKey($id)
            ->where('status', 'pending')
            ->firstOrFail();

        $transaction->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with(
            'success',
            'درخواست پرداخت رد شد.'
        );
    }
}
