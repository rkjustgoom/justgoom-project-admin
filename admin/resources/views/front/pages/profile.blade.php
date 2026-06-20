@extends('front.layouts.app')

@section('title', $profile->company_name . ' — Just Goom')
@section('meta_description', Str::limit(strip_tags($profile->business_desc ?: $profile->tagline ?: $profile->company_name), 160))
@section('body_attrs', 'class="profile-page" data-page="all-profiles"')

@section('content')
@if($isOwner)
  <div class="profile-completion-banner">
    <div class="container profile-completion-inner">
      <div class="profile-completion-text">
        <strong>Profile Completion Required</strong>
        <span>Complete all profile sections to activate your Free Plan listing.</span>
      </div>
      <div class="profile-completion-progress">
        <div class="progress-bar"><div class="progress-fill" style="width:{{ $completionPercent }}%"></div></div>
        <span>{{ $completionPercent }}% Complete</span>
      </div>
      <a href="{{ route('front.users.profile') }}" class="btn btn-accent btn-sm">Complete Profile →</a>
    </div>
  </div>
@endif

  <section class="profile-hero">
    <div class="profile-hero-banner">
      <img src="{{ asset('front/assets/images/hero-banner.jpg') }}" alt="{{ $profile->company_name }} banner">
    </div>
    <div class="container profile-hero-content">
      <div class="profile-hero-main">
        <div class="profile-avatar-wrap">
          <img src="{{ $profile->logo ? asset($profile->logo) : asset('front/assets/images/cat-real-estate.jpg') }}" alt="{{ $profile->company_name }}" class="profile-avatar">
          @if($user->hasVerifiedEmail())
            <span class="profile-verified" title="Verified">✓</span>
          @endif
        </div>
        <div class="profile-hero-info">
          <div class="profile-hero-top">
            <h1>{{ $profile->company_name }}</h1>
            <span class="plan-badge plan-platinum">{{ $planName }}</span>
          </div>
          @if($profile->tagline)
            <p class="profile-designation">{{ $profile->tagline }}</p>
          @endif
          <div class="profile-meta-row">
            @if($profile->city)
              <span>📍 {{ trim(collect([$profile->city, $profile->state])->filter()->implode(', ')) }}</span>
            @endif
            @if($user->category)
              <span>📂 {{ $user->category->name }}</span>
            @endif
            @if($profile->email)
              <span>✉ {{ $profile->email }}</span>
            @endif
            @if($profile->phone)
              <span>📞 +91 {{ $profile->phone }}</span>
            @endif
          </div>
          <div class="profile-hero-actions">
            <a href="{{ route('front.contact') }}" class="btn btn-accent">Send Inquiry</a>
            <a href="{{ route('front.articles') }}" class="btn btn-outline">View Articles</a>
          </div>
        </div>
      </div>
      <aside class="profile-qr-card">
        <h3>Share Profile</h3>
        <div class="profile-qr-wrap">
          <img src="{{ $qrUrl }}" alt="Profile QR Code" class="profile-qr-img" width="160" height="160">
        </div>
        <p class="profile-qr-note">Scan to view this profile</p>
        <a href="{{ $qrUrl }}" download="profile-qr.png" class="btn btn-outline btn-sm btn-block">Download QR Code</a>
      </aside>
    </div>
  </section>

  <div class="container profile-layout">
    <main class="profile-main">
      @if($profile->business_desc)
        <section class="profile-section">
          <h2>About Business</h2>
          <p>{{ $profile->business_desc }}</p>
        </section>
      @endif

      @if($user->subCategory)
        <section class="profile-section">
          <h2>Category</h2>
          <div class="profile-tags">
            <span>{{ $user->category->name ?? 'General' }}</span>
            <span>{{ $user->subCategory->name }}</span>
          </div>
        </section>
      @endif

      @if($services->isNotEmpty())
        <section class="profile-section">
          <h2>My Services</h2>
          <div class="profile-services-grid profile-services-cards">
            @foreach($services as $service)
              <article class="profile-service-card">
                @if($service->product_image)
                  <img src="{{ asset($service->product_image) }}" alt="{{ $service->product_name }}">
                @else
                  <div class="profile-service-card-placeholder">💼</div>
                @endif
                <div class="profile-service-card-body">
                  <h4>{{ $service->product_name }}</h4>
                  @if($service->product_desc)
                    <p>{{ $service->product_desc }}</p>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        </section>
      @endif

      @if($teams->isNotEmpty())
        <section class="profile-section">
          <h2>My Team</h2>
          <div class="profile-team-grid">
            @foreach($teams as $member)
              @php
                $initials = collect(explode(' ', $member->name))
                  ->filter()
                  ->take(2)
                  ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                  ->implode('');
                $avatarColors = ['#6366f1', '#ec4899', '#14b8a6', '#f59e0b', '#0ea5e9', '#8b5cf6'];
                $avatarColor = $avatarColors[crc32($member->name) % count($avatarColors)];
              @endphp
              <article class="team-member-card">
                @if($member->image)
                  <img src="{{ asset($member->image) }}" alt="{{ $member->name }}" class="team-avatar-img">
                @else
                  <div class="team-avatar" style="background:{{ $avatarColor }}">{{ $initials }}</div>
                @endif
                <h4>{{ $member->name }}</h4>
                @if($member->designation)
                  <p class="team-role">{{ $member->designation }}</p>
                @endif
                @if($member->department)
                  <p class="team-location">{{ $member->department }}</p>
                @endif
                @if($member->short_info)
                  <p class="team-bio">{{ Str::limit($member->short_info, 100) }}</p>
                @endif
              </article>
            @endforeach
          </div>
        </section>
      @endif

      @if($documents->isNotEmpty())
        <section class="profile-section">
          <h2>My Documents</h2>
          <ul class="profile-doc-list">
            @foreach($documents as $document)
              <li>
                <span>
                  <strong>{{ $document->title }}</strong>
                  <small>{{ $document->fileTypeLabel() }} · {{ $document->created_at?->format('M j, Y') }}</small>
                </span>
                <a href="{{ asset($document->attachment) }}" target="_blank" rel="noopener">Download</a>
              </li>
            @endforeach
          </ul>
        </section>
      @endif
    </main>

    <aside class="profile-sidebar">
      <section class="profile-contact-card">
        <h3>Contact Information</h3>
        <ul class="profile-contact-list">
          @if($profile->phone)
            <li><span>📞</span> +91 {{ $profile->phone }}</li>
          @endif
          @if($profile->email)
            <li><span>✉</span> {{ $profile->email }}</li>
          @endif
          @if($profile->address || $profile->city)
            <li><span>📍</span> {{ trim(collect([$profile->address, $profile->city, $profile->state, $profile->zipcode])->filter()->implode(', ')) }}</li>
          @endif
          @if($profile->social_website)
            <li><span>🌐</span> {{ $profile->social_website }}</li>
          @endif
        </ul>
        <a href="{{ route('front.contact') }}" class="btn btn-accent btn-block">Send Inquiry</a>
      </section>
    </aside>
  </div>
@endsection
