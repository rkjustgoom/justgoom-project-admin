<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CategoryCatalogService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryCatalogService $categoryCatalogService)
    {
    }

    public function index()
    {
        $stats = $this->categoryCatalogService->stats();

        return view('front.pages.categories', [
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'catalogStats' => $stats,
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $details = $this->categoryCatalogService->detailsForSlug(
            $slug,
            $request->query('sub')
        );

        $category = $details['category'];
        $titleName = $details['subCategory']?->name ?? $category->name;

        return view('front.pages.category-details', [
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'category' => $category,
            'subCategory' => $details['subCategory'],
            'section' => $details['section'],
            'isEcommerce' => $details['isEcommerce'],
            'catalogStats' => $details['stats'],
            'products' => $details['products'],
            'services' => $details['services'],
            'projects' => $details['projects'],
            'sellers' => $details['sellers'],
            'categoryIcon' => $details['icon'],
            'pageTitle' => $titleName,
        ]);
    }
}
