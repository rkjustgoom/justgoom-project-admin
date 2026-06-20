<?php

use App\Http\Controllers\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Front\Auth\LoginController as FrontLoginController;
use App\Http\Controllers\Front\Auth\RegisterController;
use App\Http\Controllers\Front\Auth\ResendVerificationController;
use App\Http\Controllers\Front\Auth\VerifyEmailController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\PublicProfileController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\TeamController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('front.home');

Route::get('/register/sub-categories/{category}', [RegisterController::class, 'subCategories'])->name('front.register.sub-categories');
Route::get('/register/check-slug', [RegisterController::class, 'checkSlug'])->name('front.register.check-slug');

Route::middleware('guest')->group(function () {
    Route::get('/login', [FrontLoginController::class, 'show'])->name('front.login');
    Route::post('/login', [FrontLoginController::class, 'login'])->name('front.login.submit');
    Route::get('/register', [RegisterController::class, 'show'])->name('front.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('front.register.submit');
});

Route::post('/email/verification-notification', ResendVerificationController::class)
    ->middleware('throttle:6,1')
    ->name('front.verification.send');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware('signed')
    ->name('front.verification.verify');

Route::post('/logout', [FrontLoginController::class, 'logout'])->name('front.logout');

Route::get('/about', fn () => app(PageController::class)->publicPage('about'))->name('front.about');
Route::get('/articles', fn () => app(PageController::class)->publicPage('articles'))->name('front.articles');
Route::get('/calculators', fn () => app(PageController::class)->publicPage('calculators'))->name('front.calculators');
Route::get('/categories', [FrontCategoryController::class, 'index'])->name('front.categories');
Route::get('/category-details', fn () => app(PageController::class)->publicPage('category-details'))->name('front.category-details');
Route::get('/contact', fn () => app(PageController::class)->publicPage('contact'))->name('front.contact');
Route::get('/all-profiles', [PublicProfileController::class, 'index'])->name('front.all-profiles');
Route::get('/profile', [PublicProfileController::class, 'redirectLegacy'])->name('front.profile');
Route::get('/profile/{slug}', function (string $slug) {
    return redirect('/'.$slug, 301);
})->where('slug', '[a-z0-9\-]+');

Route::prefix('users')->name('front.users.')->middleware(['auth', 'front.user'])->group(function () {
    Route::get('/', [PageController::class, 'userPage'])->name('dashboard');
    Route::get('/analytics', fn () => app(PageController::class)->userPage('analytics'))->name('analytics');
    Route::get('/articles', fn () => app(PageController::class)->userPage('articles'))->name('articles');
    Route::get('/article-form', fn () => app(PageController::class)->userPage('article-form'))->name('article-form');
    Route::get('/banners', fn () => app(PageController::class)->userPage('banners'))->name('banners');
    Route::get('/banner-form', fn () => app(PageController::class)->userPage('banner-form'))->name('banner-form');
    Route::get('/change-password', fn () => app(PageController::class)->userPage('change-password'))->name('change-password');
    Route::get('/delete', fn () => app(PageController::class)->userPage('delete'))->name('delete');
    Route::get('/document-add', [DocumentController::class, 'create'])->name('document-add');
    Route::redirect('/document-form', '/users/document-add')->name('document-form');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/inquiries', fn () => app(PageController::class)->userPage('inquiries'))->name('inquiries');
    Route::get('/inquiry-reply', fn () => app(PageController::class)->userPage('inquiry-reply'))->name('inquiry-reply');
    Route::get('/inquiry-view', fn () => app(PageController::class)->userPage('inquiry-view'))->name('inquiry-view');
    Route::get('/notifications', fn () => app(PageController::class)->userPage('notifications'))->name('notifications');
    Route::get('/notification-view', fn () => app(PageController::class)->userPage('notification-view'))->name('notification-view');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/reviews', fn () => app(PageController::class)->userPage('reviews'))->name('reviews');
    Route::get('/review-view', fn () => app(PageController::class)->userPage('review-view'))->name('review-view');
    Route::get('/service-add', [ServiceController::class, 'create'])->name('service-add');
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::redirect('/service-edit', '/users/services')->name('service-edit');
    Route::redirect('/service-form', '/users/services')->name('service-form');
    Route::get('/subscription', fn () => app(PageController::class)->userPage('subscription'))->name('subscription');
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/team-add', [TeamController::class, 'create'])->name('team-add');
    Route::post('/team', [TeamController::class, 'store'])->name('team.store');
    Route::get('/team/{team}/edit', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('/team/{team}', [TeamController::class, 'update'])->name('team.update');
    Route::delete('/team/{team}', [TeamController::class, 'destroy'])->name('team.destroy');
    Route::redirect('/team-edit', '/users/team')->name('team-edit');
    Route::redirect('/team-form', '/users/team')->name('team-form');
    Route::get('/videos', fn () => app(PageController::class)->userPage('videos'))->name('videos');
    Route::get('/video-form', fn () => app(PageController::class)->userPage('video-form'))->name('video-form');
});

Route::redirect('/admin', '/admin/dashboard');

Route::redirect('/admin/login-legacy', '/admin/login');
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
});

Route::get('/{slug}', [PublicProfileController::class, 'show'])
    ->name('front.profile.show')
    ->where('slug', '[a-z0-9\-]+');
