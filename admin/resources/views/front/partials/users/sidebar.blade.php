@php
  $sidebarUser = auth()->user();
  if ($sidebarUser) {
      $sidebarUser->loadMissing('category');
  }
  $sidebarUserPlan = $sidebarUser
    ? \App\Models\UserPlan::with('plan')
        ->where('user_id', auth()->id())
        ->where('next_purchase_date', '>=', now()->toDateString())
        ->orderByDesc('next_purchase_date')
        ->first()
    : null;
  $sidebarPlanName = $sidebarUserPlan?->plan?->name
    ?? \App\Models\Plan::where('name', 'Free')->value('name')
    ?? 'Free';
  $sidebarProjectSection = \App\Support\ProjectSection::forUser($sidebarUser);
  $sidebarProjectLabel = match ($sidebarProjectSection) {
      \App\Support\ProjectSection::REAL_ESTATE => 'My Listings',
      \App\Support\ProjectSection::ECOMMERCE => 'My Products',
      default => 'My Projects',
  };
  $sidebarProjectIcon = match ($sidebarProjectSection) {
      \App\Support\ProjectSection::REAL_ESTATE => '🏠',
      \App\Support\ProjectSection::ECOMMERCE => '🛍️',
      default => '📁',
  };
@endphp
<button type="button" class="user-sidebar-close" aria-label="Close menu">✕</button>
<div class="user-sidebar-brand">
  <a href="{{ route('front.users.dashboard') }}"><img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom"></a>
</div>
<div class="user-sidebar-plan">
  <span class="user-sidebar-plan-icon">{{ $sidebarPlanName === 'Platinum' ? '💎' : ($sidebarPlanName === 'Gold' ? '🥇' : '🆓') }}</span>
  <div>
    <strong>{{ $sidebarPlanName }} Plan</strong>
    <span><a href="{{ route('front.users.subscription') }}" style="color:inherit;text-decoration:underline;">Manage subscription</a></span>
  </div>
</div>
<nav>
  <div class="user-nav-section">
    <div class="user-nav-heading">Overview</div>
    <a href="{{ route('front.users.dashboard') }}" class="user-nav-link{{ request()->routeIs('front.users.dashboard') ? ' active' : '' }}" data-nav="dashboard"><span class="nav-icon">📊</span>Dashboard</a>
    <a href="{{ route('front.users.business-activity') }}" class="user-nav-link{{ request()->routeIs('front.users.business-activity') ? ' active' : '' }}" data-nav="business-activity"><span class="nav-icon">📈</span>Business Activity</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">My Business</div>
    <a href="{{ route('front.users.profile') }}" class="user-nav-link{{ request()->routeIs('front.users.profile*') ? ' active' : '' }}" data-nav="profile"><span class="nav-icon">👤</span>My Profile</a>
    <a href="{{ route('front.users.team') }}" class="user-nav-link{{ request()->routeIs('front.users.team', 'front.users.team-*') ? ' active' : '' }}" data-nav="team"><span class="nav-icon">👥</span>My Team</a>
    <a href="{{ route('front.users.services') }}" class="user-nav-link{{ request()->routeIs('front.users.services', 'front.users.service-*') ? ' active' : '' }}" data-nav="services"><span class="nav-icon">💼</span>Services & Products</a>
    <a href="{{ route('front.users.documents') }}" class="user-nav-link{{ request()->routeIs('front.users.documents', 'front.users.document-*') ? ' active' : '' }}" data-nav="documents"><span class="nav-icon">📄</span>My Documents</a>
    <a href="{{ route('front.users.projects') }}" class="user-nav-link{{ request()->routeIs('front.users.projects', 'front.users.project-*') ? ' active' : '' }}" data-nav="projects"><span class="nav-icon">{{ $sidebarProjectIcon }}</span>{{ $sidebarProjectLabel }}</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Content & Marketing</div>
    <a href="{{ route('front.users.articles') }}" class="user-nav-link{{ request()->routeIs('front.users.articles', 'front.users.article-*') ? ' active' : '' }}" data-nav="articles"><span class="nav-icon">📝</span>My Articles</a>
    <a href="{{ route('front.users.videos') }}" class="user-nav-link{{ request()->routeIs('front.users.videos', 'front.users.video-*') ? ' active' : '' }}" data-nav="videos"><span class="nav-icon">🎬</span>My Videos</a>
    <a href="{{ route('front.users.offers') }}" class="user-nav-link{{ request()->routeIs('front.users.offers', 'front.users.offer-*') ? ' active' : '' }}" data-nav="offers"><span class="nav-icon">🏷️</span>My Offers</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Engagement</div>
    <a href="{{ route('front.users.inquiries') }}" class="user-nav-link{{ request()->routeIs('front.users.inquiries', 'front.users.inquiries.*') ? ' active' : '' }}" data-nav="inquiries"><span class="nav-icon">💬</span>My Inquiries</a>
    <a href="{{ route('front.users.notifications') }}" class="user-nav-link{{ request()->routeIs('front.users.notifications', 'front.users.notifications.*') ? ' active' : '' }}" data-nav="notifications"><span class="nav-icon">✉</span>Notifications</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Account</div>
    <a href="{{ route('front.users.subscription') }}" class="user-nav-link{{ request()->routeIs('front.users.subscription') ? ' active' : '' }}" data-nav="subscription"><span class="nav-icon">💳</span>Subscription</a>
    <a href="{{ route('front.users.change-password') }}" class="user-nav-link{{ request()->routeIs('front.users.change-password') ? ' active' : '' }}" data-nav="change-password"><span class="nav-icon">🔑</span>Change Password</a>
  </div>
</nav>
<div class="user-sidebar-footer">
  <a href="{{ route('front.home') }}">🌐 View Public Site</a>
  <form method="POST" action="{{ route('front.logout') }}" id="frontLogoutForm" style="display:block;">
    @csrf
    <button type="submit" class="user-logout-btn" style="color: #fff;">🚪 Logout</button>
  </form>
</div>
