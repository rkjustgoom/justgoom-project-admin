<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CategoryCatalogService;
use App\Services\Front\PublicProfileListingService;

class PageController extends Controller
{
    private const PUBLIC_VIEW_MAP = [
        'all-profiles' => 'all_profiles',
    ];

    private const USER_VIEW_MAP = [
        'dashboard' => 'index',
    ];

    public function __construct(
        private CategoryCatalogService $categoryCatalogService,
        private PublicProfileListingService $publicProfileListingService,
    ) {
    }

    public function home()
    {
        return view('front.pages.index', [
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'companyProfiles' => $this->publicProfileListingService->listForFrontend(),
            'majorCities' => $this->majorCitiesForHome(),
        ]);
    }

    private function majorCitiesForHome(): array
    {
        return [
            [
                'name' => 'Chennai',
                'image' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Chennai Central',
            ],
            [
                'name' => 'Bangalore',
                'image' => 'https://images.unsplash.com/photo-1675589412450-571c4a5afe6e?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Vidhana Soudha',
            ],
            [
                'name' => 'Delhi',
                'image' => 'https://images.unsplash.com/photo-1587135941948-670b381f08ce?w=640&h=480&fit=crop&q=80',
                'landmark' => 'India Gate',
            ],
            [
                'name' => 'Ahmedabad',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/The_famous_jaali_from_the_Sidi_Saiyyed_mosque_in_Ahmedabad.jpg/330px-The_famous_jaali_from_the_Sidi_Saiyyed_mosque_in_Ahmedabad.jpg',
                'landmark' => 'Sidi Saiyyed Mosque',
            ],
            [
                'name' => 'Hyderabad',
                'image' => 'https://images.unsplash.com/photo-1750834115164-8c2658f18dd0?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Charminar',
            ],
            [
                'name' => 'Pune',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Main_gate_of_Saniwar_Wada%2C_Pune.jpg/330px-Main_gate_of_Saniwar_Wada%2C_Pune.jpg',
                'landmark' => 'Shaniwar Wada',
            ],
            [
                'name' => 'Mumbai',
                'image' => 'https://images.unsplash.com/photo-1680344427682-ccb4e98a4d0b?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Gateway of India',
            ],
            [
                'name' => 'Kolkata',
                'image' => 'https://images.unsplash.com/photo-1558431382-27e303142255?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Victoria Memorial',
            ],
        ];
    }

    public function publicPage(string $page)
    {
        $view = $this->resolvePublicView($page);

        abort_unless(view()->exists($view), 404);

        return view($view);
    }

    public function userPage(string $page = 'dashboard')
    {
        $view = $this->resolveUserView($page);

        abort_unless(view()->exists($view), 404);

        return view($view);
    }

    private function resolvePublicView(string $page): string
    {
        $viewName = self::PUBLIC_VIEW_MAP[$page] ?? $page;

        return 'front.pages.'.$viewName;
    }

    private function resolveUserView(string $page): string
    {
        $viewName = self::USER_VIEW_MAP[$page] ?? $page;

        return 'front.users.'.$viewName;
    }
}
