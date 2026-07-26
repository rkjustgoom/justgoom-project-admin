<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\UpdateProfileRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Services\Front\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService)
    {
    }

    public function show(Request $request)
    {
        $user = $request->user()->load(['companyProfile', 'category', 'userPlans.plan']);
        $profile = $user->companyProfile;

        abort_unless($profile, 404);

        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $subCategories = SubCategory::query()
            ->where('category_id', $user->category_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('front.users.profile', [
            'user' => $user,
            'profile' => $profile,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'completionPercent' => $this->profileService->completionPercent($user),
            'planName' => $this->profileService->planName($user),
            'previewUrl' => route('front.profile.show', $profile->slug),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->update($request->user(), $request->validated());

        return redirect()
            ->route('front.users.profile')
            ->with('success', 'Profile saved successfully.');
    }
}
