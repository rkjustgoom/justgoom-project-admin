<div class="top-bar">
  <div class="container top-bar-inner">
    <div class="top-bar-left">
      <a href="tel:+919876543210">📞 +91 98765 43210</a>
      <a href="mailto:info@justgoom.com">✉ info@justgoom.com</a>
    </div>
    <div class="top-bar-right">
      <a href="{{ route('front.register') }}" class="top-bar-cta">+ List Your Business Free</a>
    </div>
  </div>
</div>

<header class="site-header">
  <div class="container header-inner">
    <a href="{{ route('front.home') }}" class="logo logo-wrap">
      <img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom" class="logo-img">
    </a>
    <nav class="main-nav">
      <a href="{{ route('front.home') }}" data-nav="home">Home</a>
      <a href="{{ route('front.about') }}" data-nav="about">About Us</a>
      <a href="{{ route('front.categories') }}" data-nav="categories">Categories</a>
      <a href="{{ route('front.all-profiles') }}" data-nav="all-profiles">All Profiles</a>
      <a href="{{ route('front.articles') }}" data-nav="articles">Articles</a>
      <a href="{{ route('front.contact') }}" data-nav="contact">Contact Us</a>
    </nav>
    <div class="header-actions">
      @auth
        @if(!auth()->user()->isAdmin())
          <a href="{{ route('front.users.dashboard') }}" class="btn btn-outline btn-sm">Dashboard</a>
          <form method="POST" action="{{ route('front.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-accent btn-sm">Logout</button>
          </form>
        @else
          <a href="{{ route('front.login') }}" class="btn btn-outline btn-sm">Login</a>
          <a href="{{ route('front.register') }}" class="btn btn-accent btn-sm">Register</a>
        @endif
      @else
        <a href="{{ route('front.login') }}" class="btn btn-outline btn-sm">Login</a>
        <a href="{{ route('front.register') }}" class="btn btn-accent btn-sm">Register</a>
      @endauth
    </div>
    <button class="mobile-toggle" aria-label="Open menu">☰</button>
  </div>
</header>

<div class="mobile-nav">
  <div class="mobile-nav-panel">
    <button class="mobile-nav-close" aria-label="Close menu">✕</button>
    <nav class="mobile-nav-links">
      <a href="{{ route('front.home') }}" data-nav="home">Home</a>
      <a href="{{ route('front.about') }}" data-nav="about">About Us</a>
      <a href="{{ route('front.categories') }}" data-nav="categories">Categories</a>
      <a href="{{ route('front.all-profiles') }}" data-nav="all-profiles">All Profiles</a>
      <a href="{{ route('front.articles') }}" data-nav="articles">Articles</a>
      <a href="{{ route('front.contact') }}" data-nav="contact">Contact Us</a>
    </nav>
    <div class="mobile-nav-actions">
      <a href="{{ route('front.login') }}" class="btn btn-outline btn-block">Login</a>
      <a href="{{ route('front.register') }}" class="btn btn-accent btn-block">Register</a>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var page = document.body.getAttribute('data-page');
    if (!page) return;

    if (page === 'login' || page === 'register') {
      document.querySelectorAll('.header-actions a, .mobile-nav-actions a').forEach(function (link) {
        var label = link.textContent.trim().toLowerCase();
        if ((page === 'login' && label === 'login') || (page === 'register' && label === 'register')) {
          link.classList.add('active-nav-btn');
        }
      });
      return;
    }

    document.querySelectorAll('[data-nav]').forEach(function (link) {
      if (link.getAttribute('data-nav') === page) {
        link.classList.add('active');
      }
    });
  });
</script>
