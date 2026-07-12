<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $plans = Plan::orderBy('rate')->get();

        $currentUserPlan = UserPlan::with('plan')
            ->where('user_id', $user->id)
            ->where('next_purchase_date', '>=', now()->toDateString())
            ->orderByDesc('next_purchase_date')
            ->first();

        $currentPlan = $currentUserPlan?->plan
            ?? Plan::where('name', 'Free')->first()
            ?? $plans->first();

        $usage = [
            'services' => $user->services()->count(),
            'team' => $user->teams()->count(),
            'documents' => $user->documents()->count(),
            'videos' => DB::table('videos')->where('user_id', $user->id)->whereNull('deleted_at')->count(),
            'projects' => $user->projects()->count(),
            'articles' => $user->articles()->count(),
        ];

        return view('front.users.subscription', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'currentUserPlan' => $currentUserPlan,
            'usage' => $usage,
        ]);
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $user = $request->user();

        $purchaseDate = now()->toDateString();
        $nextPurchaseDate = now()->addDays(max(1, (int) $plan->duration_days))->toDateString();

        UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'purchase_date' => $purchaseDate,
            'next_purchase_date' => $nextPurchaseDate,
        ]);

        return redirect()
            ->route('front.users.subscription')
            ->with('success', "Switched to {$plan->name} plan successfully.");
    }
}
