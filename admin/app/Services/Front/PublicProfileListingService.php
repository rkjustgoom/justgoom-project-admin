<?php

namespace App\Services\Front;

use App\Models\CompanyProfile;
use App\Support\ProfileBanner;
use Illuminate\Support\Facades\DB;

class PublicProfileListingService
{
    public function listForFrontend(): array
    {
        return CompanyProfile::query()
            ->with([
                'user' => fn ($q) => $q
                    ->with([
                        'category',
                        'projects' => fn ($pq) => $pq->where('status', 1)->latest()->limit(1),
                    ])
                    ->withCount([
                        'projects as projects_count' => fn ($q) => $q->where('status', 1),
                        'services as services_count',
                    ]),
            ])
            ->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q))
            ->latest()
            ->get()
            ->values()
            ->map(fn (CompanyProfile $profile, int $index) => $this->formatCard($profile, $index))
            ->all();
    }

    public function stats(): array
    {
        $query = CompanyProfile::query()->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q));

        $total = (clone $query)->count();
        $verified = (clone $query)->whereHas('user', function ($q) {
            $this->applyPublicUserConstraints($q);
            $q->whereNotNull('email_verified_at');
        })->count();

        $cities = (clone $query)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->count('city');

        $categories = DB::table('users')
            ->where('type', 'user')
            ->where('status', 1)
            ->whereNotNull('category_id')
            ->distinct()
            ->count('category_id');

        return [
            'total' => $total,
            'verified' => $verified,
            'cities' => $cities,
            'categories' => $categories,
        ];
    }

    public function findPublicProfile(string $slug): CompanyProfile
    {
        return CompanyProfile::query()
            ->with(['user.category', 'user.userPlans.plan'])
            ->where('slug', $slug)
            ->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q))
            ->firstOrFail();
    }

    public function citiesForFilter(): array
    {
        return CompanyProfile::query()
            ->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->orderBy('city')
            ->distinct()
            ->pluck('city')
            ->all();
    }

    private function formatCard(CompanyProfile $profile, int $index): array
    {
        $user = $profile->user;

        return [
            'name' => $profile->company_name,
            'slug' => $profile->slug,
            'category' => $user->category->name ?? 'Uncategorized',
            'categorySlug' => $user->category->slug ?? '',
            'subCategory' => $user?->subCategoryNames() ?? '',
            'subCategorySlug' => $user
                ? $user->subCategories()->pluck('slug')->filter()->implode(',')
                : '',
            'projects' => (int) ($user->projects_count ?? 0),
            'services' => (int) ($user->services_count ?? 0),
            'city' => $profile->city ?: ($user->city ?: 'N/A'),
            'country' => $profile->country ?: 'N/A',
            'verified' => $user->hasVerifiedEmail(),
            'featured' => $index < 4,
            'addedDaysAgo' => $profile->created_at
                ? max(0, (int) $profile->created_at->diffInDays(now()))
                : 0,
            'profileUrl' => route('front.profile.show', $profile->slug),
            'tagline' => $profile->tagline,
            'logoUrl' => $profile->logo ? asset($profile->logo) : null,
            'bannerUrl' => ProfileBanner::url($profile, $user),
        ];
    }

    private function applyPublicUserConstraints($query): void
    {
        $query->where('type', 'user')
            ->where('status', 1);
    }
}
