<button type="button" class="user-sidebar-close" aria-label="Close menu">✕</button>
<div class="user-sidebar-brand">
  <a href="{{ route('front.users.dashboard') }}"><img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom"></a>
</div>
<div class="user-sidebar-plan">
  <span class="user-sidebar-plan-icon">💎</span>
  <div><strong>Platinum Plan</strong><span>All features unlocked</span></div>
</div>
<nav>
  <div class="user-nav-section">
    <div class="user-nav-heading">Overview</div>
    <a href="{{ route('front.users.dashboard') }}" class="user-nav-link" data-nav="dashboard"><span class="nav-icon">📊</span>Dashboard</a>
    <a href="{{ route('front.users.analytics') }}" class="user-nav-link" data-nav="analytics"><span class="nav-icon">📈</span>Visitor Analytics</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">My Business</div>
    <a href="{{ route('front.users.profile') }}" class="user-nav-link" data-nav="profile"><span class="nav-icon">👤</span>My Profile</a>
    <a href="{{ route('front.users.team') }}" class="user-nav-link" data-nav="team"><span class="nav-icon">👥</span>My Team</a>
    <a href="{{ route('front.users.services') }}" class="user-nav-link" data-nav="services"><span class="nav-icon">💼</span>My Services</a>
    <a href="{{ route('front.users.documents') }}" class="user-nav-link" data-nav="documents"><span class="nav-icon">📄</span>My Document</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Marketing</div>
    <a href="{{ route('front.users.banners') }}" class="user-nav-link" data-nav="banners"><span class="nav-icon">🖼</span>My Banner</a>
    <a href="{{ route('front.users.videos') }}" class="user-nav-link" data-nav="videos"><span class="nav-icon">🎬</span>My Video</a>
    <a href="{{ route('front.users.articles') }}" class="user-nav-link" data-nav="articles"><span class="nav-icon">📝</span>My Articles</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Engagement</div>
    <a href="{{ route('front.users.inquiries') }}" class="user-nav-link" data-nav="inquiries"><span class="nav-icon">💬</span>My Inquiry</a>
    <a href="{{ route('front.users.notifications') }}" class="user-nav-link" data-nav="notifications"><span class="nav-icon">✉</span>My Notification</a>
    <a href="{{ route('front.users.reviews') }}" class="user-nav-link" data-nav="reviews"><span class="nav-icon">⭐</span>My Review</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Account</div>
    <a href="{{ route('front.users.change-password') }}" class="user-nav-link" data-nav="change-password"><span class="nav-icon">🔑</span>Change Password</a>
  </div>
</nav>
<div class="user-sidebar-footer">
  <a href="{{ route('front.home') }}">🌐 View Public Site</a>
  <form method="POST" action="{{ route('front.logout') }}" id="frontLogoutForm" style="display:block;">
    @csrf
    <button type="submit" class="user-logout-btn">🚪 Logout</button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var page = document.body.dataset.page;
    if (!page) return;
    document.querySelectorAll('.user-nav-link[data-nav]').forEach(function (link) {
      if (link.getAttribute('data-nav') === page) {
        link.classList.add('active');
      }
    });
  });
</script>
