<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CategoryCatalogService;
use App\Services\Front\DocumentService;
use App\Services\Front\ProfileService;
use App\Services\Front\PublicProfileListingService;
use App\Services\Front\ServiceService;
use App\Services\Front\TeamService;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function __construct(
        private PublicProfileListingService $listingService,
        private CategoryCatalogService $categoryCatalogService,
        private ProfileService $profileService,
        private TeamService $teamService,
        private ServiceService $serviceService,
        private DocumentService $documentService,
    ) {
    }

    public function index()
    {
        $stats = $this->listingService->stats();

        return view('front.pages.all_profiles', [
            'companyProfiles' => $this->listingService->listForFrontend(),
            'profileStats' => $stats,
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'filterCategories' => $this->categoryCatalogService->activeCategories(),
            'filterCities' => $this->listingService->citiesForFilter(),
        ]);
    }

    public function show(string $slug)
    {
        $profile = $this->listingService->findPublicProfile($slug);
        $user = $profile->user;

        return view('front.pages.profile', [
            'profile' => $profile,
            'user' => $user,
            'teams' => $this->teamService->listForPublicProfile($user),
            'services' => $this->serviceService->listForPublicProfile($user),
            'documents' => $this->documentService->listForPublicProfile($user),
            'completionPercent' => $this->profileService->completionPercent($user),
            'planName' => $this->profileService->planName($user),
            'isOwner' => auth()->check() && auth()->id() === $user->id,
            'profileUrl' => route('front.profile.show', $profile->slug),
            'qrUrl' => 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='.urlencode(route('front.profile.show', $profile->slug)),
        ]);
    }

    public function redirectLegacy(Request $request)
    {
        if ($request->filled('slug')) {
            return redirect()->route('front.profile.show', $request->query('slug'));
        }

        return redirect()->route('front.all-profiles');
    }
}
