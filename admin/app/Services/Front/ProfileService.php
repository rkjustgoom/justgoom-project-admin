<?php

namespace App\Services\Front;

use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function update(User $user, array $data): User
    {
        $subCategory = SubCategory::query()
            ->where('id', $data['sub_category_id'])
            ->where('category_id', $data['category_id'])
            ->where('status', 1)
            ->first();

        if (! $subCategory) {
            throw ValidationException::withMessages([
                'sub_category_id' => 'Selected sub category does not belong to the chosen category.',
            ]);
        }

        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'city' => $data['city'],
            ]);

            $profile = $user->companyProfile;

            if (! $profile) {
                throw ValidationException::withMessages([
                    'company_name' => 'Company profile not found. Please contact support.',
                ]);
            }

            $profile->update([
                'company_name' => $data['company_name'],
                'tagline' => $data['tagline'] ?? null,
                'business_desc' => $data['business_desc'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
                'city' => $data['city'],
            ]);

            return $user->fresh(['companyProfile', 'category', 'subCategory', 'userPlans.plan']);
        });
    }

    public function completionPercent(User $user): int
    {
        $profile = $user->companyProfile;

        $checks = [
            filled($profile?->company_name),
            filled($user->category_id),
            filled($user->sub_category_id),
            filled($profile?->tagline),
            filled($profile?->business_desc),
            filled($profile?->phone),
            filled($profile?->email),
            filled($profile?->address),
            filled($profile?->city),
        ];

        $filled = count(array_filter($checks));

        return (int) round(($filled / count($checks)) * 100);
    }

    public function planName(User $user): string
    {
        $plan = $user->userPlans()
            ->with('plan')
            ->latest('purchase_date')
            ->first()
            ?->plan;

        return $plan?->name ? $plan->name.' Plan' : 'Free Plan';
    }
}
