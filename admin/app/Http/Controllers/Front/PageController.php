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
        ]);
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
