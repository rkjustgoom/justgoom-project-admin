@extends('front.layouts.app')

@section('title', $profile->company_name . ' — Just Goom')
@section('meta_description', Str::limit(strip_tags($profile->business_desc ?: $profile->tagline ?: $profile->company_name), 160))
@section('body_attrs', 'class="profile-page" data-page="all-profiles"')

@php
  $location = trim(collect([$profile->city, $profile->state, $profile->country])->filter()->implode(', '));
  $fullAddress = trim(collect([$profile->address, $profile->city, $profile->state, $profile->zipcode, $profile->country])->filter()->implode(', '));
  $mapQuery = $fullAddress ?: $location;
  $mapEmbedUrl = $mapQuery
    ? 'https://maps.google.com/maps?q='.rawurlencode($mapQuery).'&z=14&output=embed'
    : null;
  $mapOpenUrl = $mapQuery
    ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapQuery)
    : null;
  $logoUrl = $profile->logo ? asset($profile->logo) : null;
  $initials = collect(explode(' ', $profile->company_name))
    ->filter()
    ->take(2)
    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
    ->implode('');
  $websiteUrl = $profile->social_website
    ? (Str::startsWith($profile->social_website, ['http://', 'https://']) ? $profile->social_website : 'https://'.$profile->social_website)
    : null;
  $subWebsiteUrl = $profile->social_subwebsite
    ? (Str::startsWith($profile->social_subwebsite, ['http://', 'https://']) ? $profile->social_subwebsite : 'https://'.$profile->social_subwebsite)
    : null;
  $avatarColors = ['#6366f1', '#ec4899', '#14b8a6', '#f59e0b', '#0ea5e9', '#8b5cf6'];
@endphp

@section('content')
  <section class="profile-hero">
    <div class="profile-hero-bg" style="background-image:url('{{ asset('front/assets/images/hero-banner.jpg') }}')"></div>
    <div class="container profile-hero-inner">
      <div class="profile-hero-top">
        <div class="profile-hero-brand">
          <div class="profile-hero-logo">
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="{{ $profile->company_name }}">
            @else
              {{ $initials ?: 'JG' }}
            @endif
          </div>
          <div>
            <h1>{{ $profile->company_name }}</h1>
            <p class="profile-category">{{ $user->category->name ?? ($profile->tagline ?: 'Company') }}</p>
            <div class="profile-meta">
              @if($location)
                <span class="profile-meta-item">
                  <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                  {{ $location }}
                </span>
              @endif
              @if($websiteUrl)
                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="profile-meta-item">
                  <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                  {{ parse_url($websiteUrl, PHP_URL_HOST) ?: $profile->social_website }}
                </a>
              @endif
            </div>
          </div>
        </div>
        <div class="profile-hero-badges">
          <span class="profile-hero-badge profile-hero-badge-trusted" title="Trusted business listed on Just Goom">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
            Trusted Site
          </span>
          <span class="profile-hero-badge profile-hero-badge-trending" title="Trending business profile">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6h-6z"/></svg>
            Trending
          </span>
        </div>
      </div>

      <div class="profile-hero-nav">
        <nav class="profile-tabs" id="profileTabs">
          <button type="button" class="active" data-tab="overview">Overview</button>
          <!-- <button type="button" data-tab="activities">Activities</button> -->
          <button type="button" data-tab="projects">Projects</button>
          <button type="button" data-tab="documents">Documents</button>
          <button type="button" data-tab="services">Services</button>
          <button type="button" data-tab="product">Product</button>
          <button type="button" data-tab="videos">Videos</button>
          <button type="button" data-tab="blog">My Blog</button>
          <button type="button" data-tab="location">My Adevtiment / Offerings</button>
        </nav>
      </div>
    </div>
  </section>

  <div class="container profile-body">
    <aside class="profile-sidebar">
      @if($isOwner)
        <div class="profile-card profile-progress-card">
          <h3>Complete Your Profile</h3>
          <div class="profile-progress-bar">
            <div class="profile-progress-fill" style="width:{{ $completionPercent }}%"></div>
          </div>
          <span class="profile-progress-label">{{ $completionPercent }}%</span>
        </div>
      @endif

      <div class="profile-card">
        <h3>Info</h3>
        <ul class="profile-info-list">
          <li><span>Full Name :</span><strong>{{ $profile->company_name }}</strong></li>
          @if($profile->phone)
            <li><span>Mobile :</span><strong>+91 {{ $profile->phone }}</strong></li>
          @endif
          @if($profile->email)
            <li><span>E-mail :</span><strong>{{ $profile->email }}</strong></li>
          @endif
          @if($location)
            <li><span>Location :</span><strong>{{ $location }}</strong></li>
          @endif
        </ul>
      </div>

      @if($profile->social_website || $profile->social_facebook || $profile->social_twitter || $profile->social_linkedin)
        <div class="profile-card">
          <h3>Portfolio</h3>
          <div class="profile-portfolio">
            @if($profile->social_facebook)
              <a href="{{ Str::startsWith($profile->social_facebook, ['http://', 'https://']) ? $profile->social_facebook : 'https://'.$profile->social_facebook }}" class="portfolio-icon github" title="Facebook" target="_blank" rel="noopener">f</a>
            @endif
            @if($websiteUrl)
              <a href="{{ $websiteUrl }}" class="portfolio-icon web" title="Website" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
              </a>
            @endif
            @if($profile->social_twitter)
              <a href="{{ Str::startsWith($profile->social_twitter, ['http://', 'https://']) ? $profile->social_twitter : 'https://'.$profile->social_twitter }}" class="portfolio-icon dribbble" title="Twitter" target="_blank" rel="noopener">X</a>
            @endif
            @if($profile->social_linkedin)
              <a href="{{ Str::startsWith($profile->social_linkedin, ['http://', 'https://']) ? $profile->social_linkedin : 'https://'.$profile->social_linkedin }}" class="portfolio-icon behance" title="LinkedIn" target="_blank" rel="noopener">in</a>
            @endif
          </div>
        </div>
      @endif
      <div class="profile-card">
        <h3>Category</h3>
        <ul class="profile-info-list">
          <li><span>Category :</span><strong>{{ $user->category->name ?? '-' }}</strong></li>
          <li><span>Sub Category :</span><strong>{{ $user->subCategory->name ?? '-' }}</strong></li>
        </ul>
      </div>
      <div
        class="profile-card profile-share-qr-card"
        id="profileQrFlyer"
        data-company="{{ $profile->company_name }}"
        data-logo="{{ $logoUrl }}"
        data-initials="{{ $initials ?: 'JG' }}"
        data-category="{{ $user->category->name ?? 'Company Profile' }}"
        data-profile-url="{{ $profileUrl }}"
        data-qr-url="{{ $qrDownloadUrl }}"
        data-jg-logo="{{ $justgoomLogoUrl }}"
      >
        <h3>Share Profile</h3>
        <div class="profile-qr-wrap">
          <div id="profileQrMount" role="img" aria-label="Profile QR Code"></div>
        </div>
        <p class="profile-qr-note">Scan to view this profile</p>
        <button type="button" class="btn btn-share-profile btn-sm btn-block" id="shareProfileBtn" data-url="{{ $profileUrl }}">
          <svg class="btn-share-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
            <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
          </svg>
          <span class="btn-share-label">Share Profile</span>
        </button>
        <button type="button" class="btn btn-outline btn-sm btn-block" id="downloadProfileQrBtn">Download QR</button>
      </div>

      @if($mapQuery)
        <div class="profile-card profile-location-card">
          <h3>Location</h3>
          <p class="profile-sidebar-address">
            <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            <span>{{ $mapQuery }}</span>
          </p>
          <div class="profile-map-embed">
            <iframe
              title="Business location map"
              src="{{ $mapEmbedUrl }}"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              allowfullscreen
            ></iframe>
          </div>
          <a href="{{ $mapOpenUrl }}" class="btn btn-outline btn-sm btn-block" target="_blank" rel="noopener">Open in Maps</a>
        </div>
      @endif
    </aside>

    <main class="profile-content">
      <div class="profile-tab-pane active" data-pane="overview">
        <div class="profile-card profile-about-card">
          <h3>About</h3>
          <div class="profile-about-text">
            @if($profile->business_desc)
              @foreach(preg_split("/\n\s*\n/", trim($profile->business_desc)) as $para)
                @if(trim($para) !== '')
                  <p>{{ trim($para) }}</p>
                @endif
              @endforeach
            @else
              <p>{{ $profile->tagline ?: 'No business description added yet.' }}</p>
            @endif
          </div>
          <div class="profile-quick-info">
            <div class="quick-info-item">
              <span class="quick-info-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
              </span>
              <div>
                <span class="quick-info-label">Company Type</span>
                <strong>Private Limited</strong>
              </div>
            </div>
            <div class="quick-info-item">
              <span class="quick-info-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
              </span>
              <div>
                <span class="quick-info-label">Website</span>
                <strong>{{ $profile->social_website ?: '-' }}</strong>
              </div>
            </div>
            <div class="quick-info-item">
              <span class="quick-info-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
              </span>
              <div>
                <span class="quick-info-label">Sub Website</span>
                <strong>{{ $profile->social_subwebsite ?: '-' }}</strong>
              </div>
            </div>
          </div>
        </div>

        <div class="profile-card profile-team-card">
          <div class="profile-team-header">
            <h3>Team <span class="profile-section-count">({{ $teams->count() }})</span></h3>
          </div>
          @if($teams->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--grid {{ $teams->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-team-grid">
                @foreach($teams as $member)
                  @php
                    $memberInitials = collect(explode(' ', $member->name))
                      ->filter()
                      ->take(2)
                      ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                      ->implode('');
                    $avatarColor = $avatarColors[crc32($member->name) % count($avatarColors)];
                    $memberImage = $member->image ? asset($member->image) : '';
                  @endphp
                  <article class="team-member-card">
                    @if($member->image)
                      <img src="{{ $memberImage }}" alt="{{ $member->name }}" class="team-avatar-img">
                    @else
                      <div class="team-avatar" style="background:{{ $avatarColor }}">{{ $memberInitials }}</div>
                    @endif
                    <h4 title="{{ $member->name }}">{{ $member->name }}</h4>
                    @if($member->designation)
                      <p class="team-role" title="{{ $member->designation }}">{{ $member->designation }}</p>
                    @endif
                    @if($member->department)
                      <p class="team-department">
                        <svg class="team-department-icon" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path fill="currentColor" d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>
                        {{ $member->department }}
                      </p>
                    @endif
                    <button
                      type="button"
                      class="btn btn-primary btn-block btn-sm js-team-view-profile"
                      data-name="{{ $member->name }}"
                      data-designation="{{ $member->designation }}"
                      data-phone="{{ $member->phone }}"
                      data-email="{{ $member->email }}"
                      data-department="{{ $member->department }}"
                      data-info="{{ $member->short_info }}"
                      data-image="{{ $memberImage }}"
                      data-initials="{{ $memberInitials }}"
                      data-color="{{ $avatarColor }}"
                    >View Profile</button>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No team members added yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="activities">
        <div class="profile-card">
          <h3>Recent Activities</h3>
          @if($activities->isNotEmpty())
            <ul class="profile-activity-list">
              @foreach($activities as $activity)
                <li>
                  <span class="activity-dot"></span>
                  {{ $activity['text'] }} — <time>{{ $activity['time'] }}</time>
                </li>
              @endforeach
            </ul>
          @else
            <p class="profile-empty-note">No recent activities.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="projects">
        <div class="profile-card">
          <h3>Projects <span class="profile-section-count">({{ $projects->count() }})</span></h3>
          @if($projects->isNotEmpty())
            <div class="profile-items-scroll {{ $projects->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-projects-grid">
                @foreach($projects as $project)
                  <div class="profile-project-item">
                    <strong>{{ $project->title }}</strong>
                    <span>{{ ucfirst($project->type) }}@if($project->description) Â· {{ Str::limit(strip_tags($project->description), 60) }}@endif</span>
                  </div>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No projects published yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="documents">
        <div class="profile-card">
          <h3>Documents <span class="profile-section-count">({{ $documents->count() }})</span></h3>
          @if($documents->isNotEmpty())
            <div class="profile-items-scroll {{ $documents->count() > 16 ? 'is-scrollable' : '' }}">
              <ul class="profile-doc-list">
                @foreach($documents as $document)
                  <li>
                    <span>
                      <strong>{{ $document->title }}</strong>
                      <small>{{ $document->fileTypeLabel() }} Â· {{ $document->created_at?->format('M j, Y') }}</small>
                    </span>
                    <a href="{{ asset($document->attachment) }}" target="_blank" rel="noopener">Download</a>
                  </li>
                @endforeach
              </ul>
            </div>
          @else
            <p class="profile-empty-note">No documents available.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="services">
        <div class="profile-card">
          <h3>Services <span class="profile-section-count">({{ $services->count() }})</span></h3>
          @if($services->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--cards {{ $services->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-services-grid profile-services-cards">
                @foreach($services as $service)
                  <article class="profile-service-card">
                    @if($service->product_image)
                      <img src="{{ asset($service->product_image) }}" alt="{{ $service->product_name }}">
                    @else
                      <div class="profile-service-card-placeholder" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="36" height="36"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                      </div>
                    @endif
                    <div class="profile-service-card-body">
                      <h4>{{ $service->product_name }}</h4>
                      @if($service->product_desc)
                        <p>{{ Str::limit($service->product_desc, 90) }}</p>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No services listed yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="product">
        <div class="profile-card">
          <h3>Products <span class="profile-section-count">({{ $products->count() }})</span></h3>
          @if($products->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--cards {{ $products->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-services-grid profile-services-cards">
                @foreach($products as $product)
                  <article class="profile-service-card">
                    @if($product->product_image)
                      <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}">
                    @else
                      <div class="profile-service-card-placeholder" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="36" height="36"><path fill="currentColor" d="M20 6H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 13H4V8h16v11zM8 10h2v2H8v-2zm0 4h2v2H8v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                      </div>
                    @endif
                    <div class="profile-service-card-body">
                      <h4>{{ $product->product_name }}</h4>
                      @if($product->product_desc)
                        <p>{{ Str::limit($product->product_desc, 90) }}</p>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No products listed yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="videos">
        <div class="profile-card">
          <h3>Videos <span class="profile-section-count">({{ $videos->count() }})</span></h3>
          @if($videos->isNotEmpty())
            <div class="profile-items-scroll {{ $videos->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-projects-grid">
                @foreach($videos as $video)
                  @php
                    $videoUrl = str_starts_with($video->link, 'http') ? $video->link : asset($video->link);
                  @endphp
                  <div class="profile-project-item">
                    <strong>{{ $video->title }}</strong>
                    <span>{{ str_starts_with($video->link, 'http') ? 'External link' : 'Uploaded video' }} Â· {{ \Carbon\Carbon::parse($video->created_at)->format('M j, Y') }}</span>
                    <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="profile-inline-link">Watch</a>
                  </div>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No videos available.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="blog">
        <div class="profile-card">
          <h3>My Blog <span class="profile-section-count">({{ $articles->count() }})</span></h3>
          @if($articles->isNotEmpty())
            <div class="profile-items-scroll {{ $articles->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-blog-list">
                @foreach($articles as $article)
                  <a href="{{ route('front.articles') }}" class="profile-blog-item">
                    <strong>{{ $article->title }}</strong>
                    <span>{{ ($article->published_at ?? $article->created_at)?->format('M j, Y') }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No blog posts published yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="location">
        <div class="profile-card">
          <h3>My Location</h3>
          <p class="profile-location-text">
            <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            {{ $fullAddress ?: ($location ?: 'Location not set') }}
          </p>
          @if($mapEmbedUrl)
            <div class="profile-map-embed profile-map-embed-lg">
              <iframe
                title="Business location map"
                src="{{ $mapEmbedUrl }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
              ></iframe>
            </div>
            @if($mapOpenUrl)
              <a href="{{ $mapOpenUrl }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener" style="margin-top:12px;display:inline-flex">Open in Maps</a>
            @endif
          @else
            <div class="profile-map-placeholder">
              <span>Location not set</span>
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

  @if($isOwner)
    <a href="{{ route('front.users.profile') }}" class="profile-fab" aria-label="Edit profile">âš™</a>
  @endif

  <div class="team-profile-modal" id="teamProfileModal" hidden>
    <div class="team-profile-modal-backdrop js-team-modal-close"></div>
    <div class="team-profile-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="teamModalName">
      <button type="button" class="team-profile-modal-close js-team-modal-close" aria-label="Close">&times;</button>
      <div class="team-profile-modal-body">
        <div class="team-profile-modal-header">
          <div class="team-profile-modal-avatar-wrap">
            <img id="teamModalImage" class="team-profile-modal-avatar" alt="">
            <div id="teamModalInitials" class="team-profile-modal-initials"></div>
          </div>
          <div class="team-profile-modal-identity">
            <h3 id="teamModalName"></h3>
            <p id="teamModalDesignation" class="team-profile-modal-role"></p>
          </div>
        </div>

        <ul class="team-profile-modal-meta">
          <li id="teamModalPhoneRow" hidden><span>Mobile</span><strong id="teamModalPhone"></strong></li>
          <li id="teamModalEmailRow" hidden><span>Email</span><strong id="teamModalEmail"></strong></li>
          <li id="teamModalDeptRow" hidden><span>Department</span><strong id="teamModalDept"></strong></li>
        </ul>

        <div id="teamModalBusinessDescRow" class="team-profile-modal-desc" hidden>
          <span>Business Description</span>
          <p id="teamModalBusinessDesc"></p>
        </div>

        <div class="team-profile-modal-company">
          <div class="team-profile-modal-company-top">
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="{{ $profile->company_name }}" class="team-profile-modal-company-logo">
            @else
              <div class="team-profile-modal-company-fallback">{{ $initials ?: 'JG' }}</div>
            @endif
            <div class="team-profile-modal-company-text">
              <strong>{{ $profile->company_name }}</strong>
              @if($fullAddress)
                <span id="teamModalCompanyAddress">{{ $fullAddress }}</span>
              @elseif($location)
                <span id="teamModalCompanyAddress">{{ $location }}</span>
              @endif
            </div>
          </div>
        </div>

        @if($isOwner)
          <button
            type="button"
            class="btn btn-accent btn-block"
            id="downloadVisitingCardBtn"
            data-company="{{ $profile->company_name }}"
            data-logo="{{ $logoUrl }}"
            data-address="{{ $fullAddress ?: $location }}"
          >Download Digital Visiting Card</button>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/profile-qr-share.js') }}?v=7"></script>
<script>
(function () {
  function loadImage(src) {
    return new Promise(function (resolve) {
      if (!src) return resolve(null);
      var img = new Image();
      img.crossOrigin = 'anonymous';
      img.onload = function () { resolve(img); };
      img.onerror = function () { resolve(null); };
      img.src = src;
    });
  }

  function wrapText(ctx, text, maxWidth, maxLines) {
    var words = String(text || '').split(/\s+/);
    var lines = [];
    var line = '';
    var limit = maxLines || 2;
    words.forEach(function (word) {
      var test = line ? line + ' ' + word : word;
      if (ctx.measureText(test).width > maxWidth && line) {
        lines.push(line);
        line = word;
      } else {
        line = test;
      }
    });
    if (line) lines.push(line);
    return lines.slice(0, limit);
  }

  function roundedRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
  }

  var modal = document.getElementById('teamProfileModal');
  if (!modal) return;

  var imgEl = document.getElementById('teamModalImage');
  var initialsEl = document.getElementById('teamModalInitials');
  var nameEl = document.getElementById('teamModalName');
  var roleEl = document.getElementById('teamModalDesignation');
  var phoneRow = document.getElementById('teamModalPhoneRow');
  var phoneEl = document.getElementById('teamModalPhone');
  var emailRow = document.getElementById('teamModalEmailRow');
  var emailEl = document.getElementById('teamModalEmail');
  var deptRow = document.getElementById('teamModalDeptRow');
  var deptEl = document.getElementById('teamModalDept');
  var descRow = document.getElementById('teamModalBusinessDescRow');
  var descEl = document.getElementById('teamModalBusinessDesc');
  var downloadBtn = document.getElementById('downloadVisitingCardBtn');
  var currentMember = null;

  function setRow(row, el, value, prefix) {
    if (value) {
      el.textContent = (prefix || '') + value;
      row.hidden = false;
    } else {
      row.hidden = true;
      el.textContent = '';
    }
  }

  function openModal(btn) {
    currentMember = {
      name: btn.getAttribute('data-name') || '',
      designation: btn.getAttribute('data-designation') || '',
      phone: btn.getAttribute('data-phone') || '',
      email: btn.getAttribute('data-email') || '',
      department: btn.getAttribute('data-department') || '',
      info: btn.getAttribute('data-info') || '',
      image: btn.getAttribute('data-image') || '',
      initials: btn.getAttribute('data-initials') || '',
      color: btn.getAttribute('data-color') || '#1A428A'
    };

    nameEl.textContent = currentMember.name;
    roleEl.textContent = currentMember.designation || '-';
    roleEl.title = currentMember.designation || '';

    imgEl.classList.remove('is-visible');
    initialsEl.classList.remove('is-visible');

    if (currentMember.image) {
      imgEl.src = currentMember.image;
      imgEl.alt = currentMember.name;
      imgEl.classList.add('is-visible');
    } else {
      imgEl.removeAttribute('src');
      initialsEl.textContent = currentMember.initials;
      initialsEl.style.background = currentMember.color;
      initialsEl.classList.add('is-visible');
    }

    var phone = String(currentMember.phone || '').replace(/\D+/g, '');
    var phoneLabel = phone.length === 10 ? ('+91 ' + phone) : (phone ? ('+91 ' + phone) : '');
    setRow(phoneRow, phoneEl, phoneLabel);
    setRow(emailRow, emailEl, currentMember.email);
    setRow(deptRow, deptEl, currentMember.department);

    if (currentMember.info && descRow && descEl) {
      descEl.textContent = currentMember.info;
      descRow.hidden = false;
    } else if (descRow && descEl) {
      descEl.textContent = '';
      descRow.hidden = true;
    }

    modal.hidden = false;
    document.body.classList.add('team-modal-open');
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('team-modal-open');
    currentMember = null;
  }

  document.querySelectorAll('.js-team-view-profile').forEach(function (btn) {
    btn.addEventListener('click', function () { openModal(btn); });
  });

  modal.querySelectorAll('.js-team-modal-close').forEach(function (el) {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });

  function downloadVisitingCard() {
    if (!currentMember || !downloadBtn) return;

    var company = downloadBtn.getAttribute('data-company') || '';
    var logoUrl = downloadBtn.getAttribute('data-logo') || '';
    var address = downloadBtn.getAttribute('data-address') || '';
    var width = 900;
    var height = 560;

    Promise.all([
      loadImage(logoUrl),
      loadImage(currentMember.image)
    ]).then(function (images) {
      var logoImg = images[0];
      var memberImg = images[1];
      var canvas = document.createElement('canvas');
      canvas.width = width;
      canvas.height = height;
      var ctx = canvas.getContext('2d');

      var grad = ctx.createLinearGradient(0, 0, width, height);
      grad.addColorStop(0, '#003366');
      grad.addColorStop(1, '#1A428A');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, width, height);

      ctx.fillStyle = '#ffffff';
      roundedRect(ctx, 28, 28, width - 56, height - 56, 18);
      ctx.fill();

      ctx.fillStyle = '#1A428A';
      ctx.fillRect(28, 28, 12, height - 56);

      var companyX = 70;
      var companyY = 52;
      if (logoImg) {
        ctx.save();
        roundedRect(ctx, companyX, companyY, 48, 48, 8);
        ctx.clip();
        ctx.drawImage(logoImg, companyX, companyY, 48, 48);
        ctx.restore();
        companyX += 62;
      }
      ctx.fillStyle = '#003366';
      ctx.font = 'bold 26px Arial, sans-serif';
      ctx.fillText(company, companyX, companyY + 22);
      if (address) {
        ctx.fillStyle = '#666';
        ctx.font = '16px Arial, sans-serif';
        wrapText(ctx, address, 700, 2).forEach(function (line, i) {
          ctx.fillText(line, companyX, companyY + 46 + (i * 20));
        });
      }

      var photoX = 90;
      var photoY = 170;
      var photoSize = 150;
      ctx.save();
      ctx.beginPath();
      ctx.arc(photoX + photoSize / 2, photoY + photoSize / 2, photoSize / 2, 0, Math.PI * 2);
      ctx.closePath();
      ctx.clip();
      if (memberImg) {
        ctx.drawImage(memberImg, photoX, photoY, photoSize, photoSize);
      } else {
        ctx.fillStyle = currentMember.color || '#1A428A';
        ctx.fillRect(photoX, photoY, photoSize, photoSize);
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 48px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(currentMember.initials || '', photoX + photoSize / 2, photoY + photoSize / 2);
        ctx.textAlign = 'left';
        ctx.textBaseline = 'alphabetic';
      }
      ctx.restore();

      var textX = 280;
      var textY = 200;
      ctx.fillStyle = '#003366';
      ctx.font = 'bold 34px Arial, sans-serif';
      ctx.fillText(currentMember.name || '', textX, textY);

      ctx.fillStyle = '#F7941D';
      ctx.font = '600 20px Arial, sans-serif';
      var roleLines = wrapText(ctx, currentMember.designation || '', 540);
      roleLines.forEach(function (line, i) {
        ctx.fillText(line, textX, textY + 40 + (i * 26));
      });

      var detailY = textY + 40 + (roleLines.length * 26) + 28;
      ctx.fillStyle = '#555';
      ctx.font = '20px Arial, sans-serif';
      var phone = String(currentMember.phone || '').replace(/\D+/g, '');
      if (phone) {
        ctx.fillText('Mobile: +91 ' + phone, textX, detailY);
        detailY += 32;
      }
      if (currentMember.email) {
        ctx.fillText('Email: ' + currentMember.email, textX, detailY);
      }

      ctx.fillStyle = '#1A428A';
      ctx.font = 'bold 15px Arial, sans-serif';
      ctx.fillText('Just Goom Digital Visiting Card', 70, height - 52);

      var link = document.createElement('a');
      var safeName = (currentMember.name || 'team-member').replace(/[^\w\-]+/g, '-').toLowerCase();
      link.download = safeName + '-visiting-card.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  }

  if (downloadBtn) {
    downloadBtn.addEventListener('click', downloadVisitingCard);
  }
})();
</script>
@endpush
