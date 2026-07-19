<?php

namespace App\Support;

use App\Models\User;

class ProjectSection
{
    public const NORMAL = 'normal';

    public const REAL_ESTATE = 'real_estate';

    public const ECOMMERCE = 'ecommerce';

    /** Real Estate category primary key */
    public const REAL_ESTATE_CATEGORY_ID = 3;

    public static function forUser(?User $user): string
    {
        if (! $user) {
            return self::NORMAL;
        }

        if ((int) ($user->category_id ?? 0) === self::REAL_ESTATE_CATEGORY_ID) {
            return self::REAL_ESTATE;
        }

        return self::NORMAL;
    }

    public static function isValid(string $section): bool
    {
        return in_array($section, [self::NORMAL, self::REAL_ESTATE, self::ECOMMERCE], true);
    }

    public static function label(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'Real Estate Listing',
            self::ECOMMERCE => 'Ecommerce Product',
            default => 'Project',
        };
    }

    public static function pluralLabel(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'Property Listings',
            self::ECOMMERCE => 'Products',
            default => 'Projects',
        };
    }

    public static function mediaTypeFor(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'listing',
            self::ECOMMERCE => 'product',
            default => 'document',
        };
    }

    /**
     * @return array{title: string, description: string, add_label: string, empty: string}
     */
    public static function copy(string $section): array
    {
        return match ($section) {
            self::REAL_ESTATE => [
                'title' => 'My Property Listings',
                'description' => 'Add property listings with price, configuration, possession, and amenities.',
                'add_label' => '+ Add Listing',
                'empty' => 'No property listings yet. Add your first listing.',
            ],
            self::ECOMMERCE => [
                'title' => 'My Products',
                'description' => 'Showcase ecommerce products with image, price, and description.',
                'add_label' => '+ Add Product',
                'empty' => 'No products yet. Add your first product.',
            ],
            default => [
                'title' => 'My Projects',
                'description' => 'Upload project documents, videos, or add external video links.',
                'add_label' => '+ Add Project',
                'empty' => 'No projects yet. Upload project documents, videos, or add external video links.',
            ],
        };
    }
}
