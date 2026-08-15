<?php

namespace App\Services\Front;

use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Project;
use App\Models\Service;
use App\Models\SubCategory;
use App\Models\User;
use App\Support\ProfileBanner;
use App\Support\ProjectSection;
use Illuminate\Support\Collection;

class CategoryCatalogService
{
    public function sectorsForFrontend(): array
    {
        return Category::query()
            ->where('status', 1)
            ->with(['subCategories' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $this->emojiIcon($category->icon),
                'iconUrl' => $this->iconUrl($category->icon),
                'section' => ProjectSection::forCategorySlug($category->slug),
                'subs' => $category->subCategories->map(fn ($sub) => [
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }

    public function stats(): array
    {
        $sectors = Category::query()->where('status', 1)->count();
        $subs = SubCategory::query()
            ->where('status', 1)
            ->whereHas('category', fn ($q) => $q->where('status', 1))
            ->count();

        $profiles = $this->publicProfilesQuery()->count();
        $cities = $this->publicProfilesQuery()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->count('city');

        return [
            'sectors' => $sectors,
            'subcategories' => $subs,
            'profiles' => $profiles,
            'cities' => $cities,
        ];
    }

    public function activeCategories(): Collection
    {
        return Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    public function findActiveBySlug(string $slug): ?Category
    {
        return Category::query()
            ->where('status', 1)
            ->where('slug', $slug)
            ->with(['subCategories' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->first();
    }

    /**
     * Ecommerce / directory catalog for a category details page.
     *
     * @return array{
     *   category: Category,
     *   subCategory: ?SubCategory,
     *   section: string,
     *   isEcommerce: bool,
     *   stats: array{sellers:int,products:int,services:int,projects:int,subs:int},
     *   products: Collection,
     *   services: Collection,
     *   projects: Collection,
     *   sellers: Collection
     * }
     */
    public function detailsForSlug(string $slug, ?string $subSlug = null): array
    {
        $category = $this->findActiveBySlug($slug);
        if (! $category) {
            abort(404);
        }

        $section = ProjectSection::forCategorySlug($category->slug);
        $isEcommerce = $section === ProjectSection::ECOMMERCE;

        $subCategory = null;
        if ($subSlug) {
            $subCategory = $category->subCategories->firstWhere('slug', $subSlug);
        }

        $userIdsQuery = $this->publicUserIdsForCategory($category->id, $subCategory?->id);
        $userIds = (clone $userIdsQuery)->pluck('id');

        $catalogProducts = Service::query()
            ->products()
            ->whereIn('user_id', $userIds)
            ->with(['user.companyProfile'])
            ->latest()
            ->limit(48)
            ->get();

        $services = Service::query()
            ->services()
            ->whereIn('user_id', $userIds)
            ->with(['user.companyProfile'])
            ->latest()
            ->limit(48)
            ->get();

        $projectQuery = Project::query()
            ->where('status', 1)
            ->whereIn('user_id', $userIds)
            ->with(['user.companyProfile'])
            ->latest()
            ->limit(48);

        if ($isEcommerce) {
            $projectQuery->where('section_type', ProjectSection::ECOMMERCE);
        }

        $projects = $projectQuery->get();

        $sellers = CompanyProfile::query()
            ->with(['user.category'])
            ->whereHas('user', function ($q) use ($category, $subCategory) {
                $this->applyPublicUserConstraints($q);
                $q->where('category_id', $category->id);
                if ($subCategory) {
                    $id = (string) $subCategory->id;
                    $q->where(function ($inner) use ($id) {
                        $inner->where('sub_category_id', $id)
                            ->orWhere('sub_category_id', 'like', $id.',%')
                            ->orWhere('sub_category_id', 'like', '%,'.$id.',%')
                            ->orWhere('sub_category_id', 'like', '%,'.$id);
                    });
                }
            })
            ->latest()
            ->limit(24)
            ->get()
            ->map(fn (CompanyProfile $profile, int $index) => $this->formatSellerCard($profile, $index));

        $ecommerceProjectCount = $isEcommerce
            ? Project::query()
                ->where('status', 1)
                ->where('section_type', ProjectSection::ECOMMERCE)
                ->whereIn('user_id', $userIds)
                ->count()
            : 0;

        $stats = [
            'sellers' => (clone $userIdsQuery)->count(),
            'products' => Service::query()->products()->whereIn('user_id', $userIds)->count() + $ecommerceProjectCount,
            'services' => Service::query()->services()->whereIn('user_id', $userIds)->count(),
            'projects' => Project::query()->where('status', 1)->whereIn('user_id', $userIds)->count(),
            'subs' => $category->subCategories->count(),
        ];

        return [
            'category' => $category,
            'subCategory' => $subCategory,
            'section' => $section,
            'isEcommerce' => $isEcommerce,
            'stats' => $stats,
            'products' => $catalogProducts,
            'services' => $services,
            'projects' => $projects,
            'sellers' => $sellers,
            'icon' => $this->categoryIcon($category),
        ];
    }

    private function publicUserIdsForCategory(int $categoryId, ?int $subCategoryId = null)
    {
        $query = User::query()
            ->where('type', 'user')
            ->where('status', 1)
            ->where('category_id', $categoryId);

        if ($subCategoryId) {
            $id = (string) $subCategoryId;
            $query->where(function ($inner) use ($id) {
                $inner->where('sub_category_id', $id)
                    ->orWhere('sub_category_id', 'like', $id.',%')
                    ->orWhere('sub_category_id', 'like', '%,'.$id.',%')
                    ->orWhere('sub_category_id', 'like', '%,'.$id);
            });
        }

        return $query;
    }

    private function formatSellerCard(CompanyProfile $profile, int $index): array
    {
        $user = $profile->user;

        return [
            'name' => $profile->company_name,
            'slug' => $profile->slug,
            'category' => $user->category->name ?? 'Uncategorized',
            'city' => $profile->city ?: ($user->city ?: 'N/A'),
            'verified' => $user && $user->hasVerifiedEmail(),
            'featured' => $index < 4,
            'profileUrl' => route('front.profile.show', $profile->slug),
            'tagline' => $profile->tagline,
            'logoUrl' => $profile->logo ? asset($profile->logo) : null,
            'bannerUrl' => ProfileBanner::url($profile, $user),
        ];
    }

    private function publicProfilesQuery()
    {
        return CompanyProfile::query()->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q));
    }

    private function applyPublicUserConstraints($query): void
    {
        $query->where('type', 'user')
            ->where('status', 1);
    }

    private function iconUrl(?string $icon): ?string
    {
        if (! $icon || ! $this->isImageIcon($icon)) {
            return null;
        }

        return asset($icon);
    }

    private function emojiIcon(?string $icon): string
    {
        if (! $icon || $this->isImageIcon($icon)) {
            return '📂';
        }

        return $icon;
    }

    private function isImageIcon(string $icon): bool
    {
        return str_contains($icon, '/') || preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $icon);
    }

    public function categoryIcon(Category $category): array
    {
        return [
            'emoji' => $this->emojiIcon($category->icon),
            'url' => $this->iconUrl($category->icon),
        ];
    }
}
