<?php

namespace App\Services\Front;

use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function update(User $user, array $data): User
    {
        $subCategoryIds = array_values(array_unique(array_map('strval', $data['sub_category_id'] ?? [])));

        $validCount = SubCategory::query()
            ->whereIn('id', $subCategoryIds)
            ->where('category_id', $data['category_id'])
            ->where('status', 1)
            ->count();

        if ($subCategoryIds === [] || $validCount !== count($subCategoryIds)) {
            throw ValidationException::withMessages([
                'sub_category_id' => 'Selected sub category does not belong to the chosen category.',
            ]);
        }

        return DB::transaction(function () use ($user, $data, $subCategoryIds) {
            $user->update([
                'category_id' => $data['category_id'],
                'sub_category_id' => implode(',', $subCategoryIds),
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

            $profileData = [
                'company_name' => $data['company_name'],
                'tagline' => $data['tagline'] ?? null,
                'business_desc' => $data['business_desc'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
                'city' => $data['city'],
                'business_hours' => $data['business_hours'] ?? null,
                'social_website' => $data['social_website'] ?? null,
                'social_subwebsite' => $data['social_subwebsite'] ?? null,
                'social_facebook' => $data['social_facebook'] ?? null,
                'social_twitter' => $data['social_twitter'] ?? null,
                'social_linkedin' => $data['social_linkedin'] ?? null,
            ];

            if (($data['logo'] ?? null) instanceof UploadedFile) {
                $this->deleteLogo($profile->logo);
                $profileData['logo'] = $this->uploadLogo($data['logo']);
            }

            $profile->update($profileData);

            return $user->fresh(['companyProfile', 'category', 'userPlans.plan']);
        });
    }

    public function completionPercent(User $user): int
    {
        $profile = $user->companyProfile;

        $checks = [
            filled($profile?->company_name),
            filled($user->category_id),
            filled($user->sub_category_id),
            filled($profile?->logo),
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

    private function uploadLogo(UploadedFile $file): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/company-logos');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/company-logos/' . $filename;
    }

    private function deleteLogo(?string $logo): void
    {
        if (! $logo) {
            return;
        }

        $path = public_path($logo);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
