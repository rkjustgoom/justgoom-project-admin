<header class="user-page-header">
  <div class="user-page-header-left">
    <button type="button" class="user-menu-btn" aria-label="Menu">☰</button>
    <h1 class="user-greeting"><strong data-user-name>{{ auth()->user()?->companyProfile?->company_name ?? auth()->user()?->fullName() }}</strong></h1>
  </div>
</header>
