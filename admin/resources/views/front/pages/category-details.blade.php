@extends('front.layouts.app')

@php
  $heroTitle = $subCategory?->name ?? $category->name;
  $heroIcon = $categoryIcon['emoji'] ?? '🔌';
  $heroIconUrl = $categoryIcon['url'] ?? null;
  $allProfilesUrl = route('front.all-profiles', array_filter([
    'category' => $category->slug,
    'sub' => $subCategory?->slug,
  ]));
  $projectsTabLabel = $isEcommerce ? 'Store Products' : 'Projects';
@endphp

@section('title', $heroTitle.' — Shop & Suppliers | JustGoom')
@section('meta_description', 'Browse products, services, and sellers in '.$heroTitle.' on JustGoom — '.$catalogStats['subs'].' subcategories, '.$catalogStats['sellers'].'+ verified businesses.')
@section('body_attrs', 'class="category-details-page'.($isEcommerce ? ' is-ecommerce' : '').'" data-page="category-details"')

@section('content')
<section class="page-hero category-details-hero">
  <div class="container">
    <nav class="breadcrumb">
      <a href="{{ route('front.home') }}">Home</a>
      <span class="breadcrumb-sep">›</span>
      <a href="{{ route('front.categories') }}">Categories</a>
      <span class="breadcrumb-sep">›</span>
      @if($subCategory)
        <a href="{{ route('front.category-details', $category->slug) }}">{{ $category->name }}</a>
        <span class="breadcrumb-sep">›</span>
        <span>{{ $subCategory->name }}</span>
      @else
        <span>{{ $category->name }}</span>
      @endif
    </nav>

    <div class="category-details-hero-row">
      <div class="category-details-hero-copy">
        <div class="category-details-badge">{{ $isEcommerce ? 'Ecommerce Category' : 'Business Category' }}</div>
        <h1>
          @if($heroIconUrl)
            <img src="{{ $heroIconUrl }}" alt="" class="category-details-hero-icon-img">
          @else
            <span class="category-details-hero-icon" aria-hidden="true">{{ $heroIcon }}</span>
          @endif
          {{ $heroTitle }}
        </h1>
        <p>
          Discover products, services, and verified sellers in {{ $category->name }}
          @if($subCategory) — focused on {{ $subCategory->name }}@endif.
          Shop catalogs and connect with suppliers across India.
        </p>
        <div class="category-details-hero-actions">
          <a href="#categoryCatalog" class="btn btn-primary">Browse Catalog</a>
          <a href="{{ $allProfilesUrl }}" class="btn btn-outline">View All Sellers</a>
          @guest
            <a href="{{ route('front.register') }}" class="btn btn-outline">List Your Business</a>
          @endguest
        </div>
      </div>
      <div class="category-details-hero-stats">
        <div class="category-details-stat-card">
          <strong>{{ $catalogStats['products'] }}</strong>
          <span>Products</span>
        </div>
        <div class="category-details-stat-card">
          <strong>{{ $catalogStats['services'] }}</strong>
          <span>Services</span>
        </div>
        <div class="category-details-stat-card">
          <strong>{{ $catalogStats['projects'] }}</strong>
          <span>{{ $isEcommerce ? 'Listings' : 'Projects' }}</span>
        </div>
        <div class="category-details-stat-card">
          <strong>{{ $catalogStats['sellers'] }}</strong>
          <span>Sellers</span>
        </div>
      </div>
    </div>
  </div>
  <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
</section>

@if($category->subCategories->isNotEmpty())
<section class="category-details-subs">
  <div class="container">
    <div class="category-details-subs-head">
      <h2>Subcategories</h2>
      <span>{{ $catalogStats['subs'] }} specialties</span>
    </div>
    <div class="category-details-sub-chips">
      <a href="{{ route('front.category-details', $category->slug) }}"
         class="category-sub-chip{{ ! $subCategory ? ' active' : '' }}">All</a>
      @foreach($category->subCategories as $sub)
        <a href="{{ route('front.category-details', ['slug' => $category->slug, 'sub' => $sub->slug]) }}"
           class="category-sub-chip{{ $subCategory && $subCategory->id === $sub->id ? ' active' : '' }}">
          {{ $sub->name }}
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="category-details-main" id="categoryCatalog">
  <div class="container">
    <nav class="category-details-tabs" id="categoryDetailsTabs" aria-label="Category catalog sections">
      <button type="button" class="active" data-tab="products">
        Products <span>{{ $products->count() + ($isEcommerce ? $projects->count() : 0) }}</span>
      </button>
      <button type="button" data-tab="services">
        Services <span>{{ $services->count() }}</span>
      </button>
      <button type="button" data-tab="projects">
        {{ $projectsTabLabel }} <span>{{ $projects->count() }}</span>
      </button>
      <button type="button" data-tab="sellers">
        Sellers <span>{{ $sellers->count() }}</span>
      </button>
    </nav>

    {{-- Products --}}
    <div class="category-details-pane active" data-pane="products">
      <div class="category-details-pane-head">
        <h2>Products</h2>
        <p>Shop product catalogs from businesses in {{ $heroTitle }}.</p>
      </div>

      @php
        $hasCatalogProducts = $products->isNotEmpty();
        $hasProjectProducts = $isEcommerce && $projects->isNotEmpty();
      @endphp

      @if($hasCatalogProducts || $hasProjectProducts)
        <div class="profile-products-grid category-catalog-grid">
          @foreach($products as $product)
            @php
              $sellerProfile = $product->user?->companyProfile;
              $sellerUrl = $sellerProfile?->slug ? route('front.profile.show', $sellerProfile->slug) : null;
            @endphp
            <article class="profile-product-card">
              <div class="profile-product-card-media">
                @if($product->product_image)
                  <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}">
                @else
                  <div class="profile-product-card-placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M20 6H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 13H4V8h16v11z"/></svg>
                  </div>
                @endif
                <span class="profile-item-badge profile-item-badge--product">Product</span>
              </div>
              <div class="profile-product-card-body">
                <h4>{{ $product->product_name }}</h4>
                @if($product->formattedPrice())
                  <div class="profile-product-price">{{ $product->formattedPrice() }}</div>
                @endif
                @if($product->product_desc)
                  <p>{{ \Illuminate\Support\Str::limit($product->product_desc, 80) }}</p>
                @endif
                @if($sellerProfile)
                  <a href="{{ $sellerUrl }}" class="category-card-seller">{{ $sellerProfile->company_name }}</a>
                @endif
              </div>
            </article>
          @endforeach

          @if($isEcommerce)
            @foreach($projects as $project)
              @php
                $cover = $project->coverImage();
                $sellerProfile = $project->user?->companyProfile;
                $sellerUrl = $sellerProfile?->slug ? route('front.profile.show', $sellerProfile->slug) : null;
              @endphp
              <article class="profile-product-card">
                <div class="profile-product-card-media">
                  @if($cover)
                    <img src="{{ asset($cover) }}" alt="{{ $project->title }}">
                  @else
                    <div class="profile-product-card-placeholder" aria-hidden="true">
                      <svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M20 6H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 13H4V8h16v11z"/></svg>
                    </div>
                  @endif
                  <span class="profile-item-badge profile-item-badge--product">Listing</span>
                </div>
                <div class="profile-product-card-body">
                  <h4>{{ $project->title }}</h4>
                  @if($project->formattedPrice())
                    <div class="profile-product-price">{{ $project->formattedPrice() }}</div>
                  @endif
                  @if($project->description)
                    <p>{{ \Illuminate\Support\Str::limit($project->description, 80) }}</p>
                  @endif
                  @if($sellerProfile)
                    <a href="{{ $sellerUrl }}" class="category-card-seller">{{ $sellerProfile->company_name }}</a>
                  @endif
                </div>
              </article>
            @endforeach
          @endif
        </div>
      @else
        <div class="category-details-empty">
          <p>No products listed yet in this category.</p>
          <a href="{{ route('front.register') }}" class="btn btn-primary btn-sm">Become a Seller</a>
        </div>
      @endif
    </div>

    {{-- Services --}}
    <div class="category-details-pane" data-pane="services">
      <div class="category-details-pane-head">
        <h2>Services</h2>
        <p>Installation, contracting, repair, and support services.</p>
      </div>

      @if($services->isNotEmpty())
        <div class="profile-services-list category-services-list">
          @foreach($services as $service)
            @php
              $sellerProfile = $service->user?->companyProfile;
              $sellerUrl = $sellerProfile?->slug ? route('front.profile.show', $sellerProfile->slug) : null;
            @endphp
            <article class="profile-service-row">
              <div class="profile-service-row-media">
                @if($service->product_image)
                  <img src="{{ asset($service->product_image) }}" alt="{{ $service->product_name }}">
                @else
                  <div class="profile-service-row-placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                  </div>
                @endif
              </div>
              <div class="profile-service-row-body">
                <div class="profile-service-row-top">
                  <span class="profile-item-badge profile-item-badge--service">Service</span>
                  @if($service->formattedPrice())
                    <span class="profile-service-row-price">{{ $service->formattedPrice() }}</span>
                  @endif
                </div>
                <h4>{{ $service->product_name }}</h4>
                @if($service->product_desc)
                  <p>{{ \Illuminate\Support\Str::limit($service->product_desc, 140) }}</p>
                @endif
                @if($sellerProfile)
                  <a href="{{ $sellerUrl }}" class="category-card-seller">{{ $sellerProfile->company_name }}</a>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div class="category-details-empty">
          <p>No services listed yet in this category.</p>
        </div>
      @endif
    </div>

    {{-- Projects / Store listings --}}
    <div class="category-details-pane" data-pane="projects">
      <div class="category-details-pane-head">
        <h2>{{ $projectsTabLabel }}</h2>
        <p>
          @if($isEcommerce)
            Featured product listings from Electronics &amp; Electrical sellers.
          @else
            Projects and showcase work from businesses in this category.
          @endif
        </p>
      </div>

      @if($projects->isNotEmpty())
        <div class="profile-products-grid category-catalog-grid">
          @foreach($projects as $project)
            @php
              $cover = $project->coverImage();
              $sellerProfile = $project->user?->companyProfile;
              $sellerUrl = $sellerProfile?->slug ? route('front.profile.show', $sellerProfile->slug) : null;
            @endphp
            <article class="profile-product-card">
              <div class="profile-product-card-media">
                @if($cover)
                  <img src="{{ asset($cover) }}" alt="{{ $project->title }}">
                @else
                  <div class="profile-product-card-placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M20 6H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 13H4V8h16v11z"/></svg>
                  </div>
                @endif
                <span class="profile-item-badge profile-item-badge--product">{{ $isEcommerce ? 'Product' : 'Project' }}</span>
              </div>
              <div class="profile-product-card-body">
                <h4>{{ $project->title }}</h4>
                @if($project->formattedPrice())
                  <div class="profile-product-price">{{ $project->formattedPrice() }}</div>
                @endif
                @if($project->description)
                  <p>{{ \Illuminate\Support\Str::limit($project->description, 90) }}</p>
                @endif
                @if($sellerProfile)
                  <a href="{{ $sellerUrl }}" class="category-card-seller">{{ $sellerProfile->company_name }}</a>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div class="category-details-empty">
          <p>No {{ strtolower($projectsTabLabel) }} yet.</p>
        </div>
      @endif
    </div>

    {{-- Sellers --}}
    <div class="category-details-pane" data-pane="sellers">
      <div class="category-details-pane-head">
        <h2>Verified Sellers</h2>
        <p>Businesses registered under {{ $heroTitle }}.</p>
      </div>

      @if($sellers->isNotEmpty())
        <div class="category-sellers-grid">
          @foreach($sellers as $seller)
            <a href="{{ $seller['profileUrl'] }}" class="category-seller-card">
              <div class="category-seller-banner" @if(!empty($seller['bannerUrl'])) style="background-image:url('{{ $seller['bannerUrl'] }}')" @endif></div>
              <div class="category-seller-body">
                <div class="category-seller-logo">
                  @if(!empty($seller['logoUrl']))
                    <img src="{{ $seller['logoUrl'] }}" alt="">
                  @else
                    <span>{{ strtoupper(substr($seller['name'], 0, 2)) }}</span>
                  @endif
                </div>
                <div>
                  <h3>
                    {{ $seller['name'] }}
                    @if($seller['verified'])
                      <span class="category-seller-verified" title="Verified">✓</span>
                    @endif
                  </h3>
                  <p>{{ $seller['tagline'] ?: $seller['category'] }}</p>
                  <span class="category-seller-meta">📍 {{ $seller['city'] }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
        <div class="category-details-more">
          <a href="{{ $allProfilesUrl }}" class="btn btn-outline">View all sellers →</a>
        </div>
      @else
        <div class="category-details-empty">
          <p>No sellers registered in this category yet.</p>
          <a href="{{ route('front.register') }}" class="btn btn-primary btn-sm">Register your business</a>
        </div>
      @endif
    </div>
  </div>
</section>

<section class="category-details-cta">
  <div class="container category-details-cta-inner">
    <div>
      <h2>Sell in {{ $category->name }}</h2>
      <p>
        Register with the <strong>{{ $category->name }}</strong> category to unlock
        @if($isEcommerce)
          Products, Services &amp; store listings in your seller dashboard.
        @else
          Services, Products &amp; Projects in your business dashboard.
        @endif
      </p>
    </div>
    <div class="category-details-cta-actions">
      @guest
        <a href="{{ route('front.register') }}" class="btn btn-primary">Create Seller Account</a>
        <a href="{{ route('front.login') }}" class="btn btn-outline">Login</a>
      @else
        <a href="{{ route('front.users.services') }}" class="btn btn-primary">Manage Services &amp; Products</a>
        <a href="{{ route('front.users.projects') }}" class="btn btn-outline">{{ $isEcommerce ? 'My Products' : 'My Projects' }}</a>
      @endguest
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
  var tabs = document.getElementById('categoryDetailsTabs');
  if (!tabs) return;
  var buttons = tabs.querySelectorAll('[data-tab]');
  var panes = document.querySelectorAll('.category-details-pane');

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tab = btn.getAttribute('data-tab');
      buttons.forEach(function (b) { b.classList.toggle('active', b === btn); });
      panes.forEach(function (pane) {
        pane.classList.toggle('active', pane.getAttribute('data-pane') === tab);
      });
    });
  });
})();
</script>
@endpush
