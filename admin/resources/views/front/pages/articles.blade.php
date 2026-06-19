@extends('front.layouts.app')

@section('title', 'Articles — Business Insights &amp; Promotions | Just Goom LLP')
@section('meta_description', 'Articles from subscribed JustGoom members — global publishing for Gold &amp; Platinum plans, private for Silver.')
@section('body_attrs', 'class="articles-page blogs-page" data-page="articles"')

@section('content')
<section class="page-hero blogs-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>Articles</span>
      </nav>
      <h1>Articles</h1>
      <p>Promotional articles from subscribed members — globally visible for Gold &amp; Platinum, private for Silver.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <div class="stats-bar blogs-stats">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num">120+</div>
        <div class="stat-label">Articles Published</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24+</div>
        <div class="stat-label">Industry Topics</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">50K+</div>
        <div class="stat-label">Monthly Readers</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">3</div>
        <div class="stat-label">Subscription Tiers</div>
      </div>
    </div>
  </div>

  <div class="container blogs-page-layout">

    <div class="articles-access-notice">
      <div class="access-notice-item">
        <span class="plan-badge plan-platinum plan-badge-sm">Platinum &amp; Gold</span>
        <p>Articles published globally — visible to all platform users.</p>
      </div>
      <div class="access-notice-item">
        <span class="plan-badge plan-silver plan-badge-sm">Free</span>
        <p>Articles stored privately — visible only to the author.</p>
      </div>
    </div>

    <div class="blogs-toolbar">
      <div class="blogs-search">
        <input type="search" class="form-input" placeholder="Search articles, topics, keywords…" aria-label="Search articles">
        <button type="button" class="btn btn-primary btn-sm">Search</button>
      </div>
      <div class="blogs-filter">
        <span class="chip active">All Public</span>
        <span class="chip">B2B Trade</span>
        <span class="chip">Manufacturing</span>
        <span class="chip">MSME Growth</span>
        <span class="chip">Real Estate</span>
        <span class="chip">Technology</span>
      </div>
    </div>

    <div class="blogs-featured">
      <article class="blog-featured-main blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/cat-business.jpg') }}" alt="B2B marketplace growth">
        </div>
        <div class="blog-body">
          <div class="article-author-bar article-author-bar-lg">
            <span class="blog-author-avatar">SG</span>
            <div>
              <strong>Shree Gold Jewellers</strong>
              <span class="article-author-meta">Ahmedabad · Jewellery &amp; Bullion</span>
              <span class="plan-badge plan-platinum plan-badge-sm">Platinum</span>
            </div>
          </div>
          <span class="blog-tag">B2B Trade</span>
          <h2>How MSMEs Can Win More B2B Buyers in 2026</h2>
          <p>From verified listings to inquiry follow-ups — practical steps for small businesses to stand out on digital marketplaces and convert more leads.</p>
          <div class="blog-footer">
            <span>May 28, 2026</span>
            <span>6 min read</span>
            <span class="article-visibility">🌐 Public</span>
          </div>
        </div>
      </article>
      <div class="blog-featured-side">
        <a href="#" class="blog-mini">
          <img src="{{ asset('front/assets/images/cat-real-estate.jpg') }}" alt="Commercial property">
          <div>
            <span class="plan-badge plan-gold plan-badge-sm">Gold</span>
            <h4>Commercial Property Investment Guide for SMEs</h4>
            <span>Mehta Real Estate · May 25, 2026</span>
          </div>
        </a>
        <a href="#" class="blog-mini">
          <img src="{{ asset('front/assets/images/blog-2.jpg') }}" alt="Digital marketing">
          <div>
            <span class="plan-badge plan-platinum plan-badge-sm">Platinum</span>
            <h4>5 Digital Strategies Every Local Business Needs</h4>
            <span>Amit Mehta Consulting · May 22, 2026</span>
          </div>
        </a>
        <a href="#" class="blog-mini">
          <img src="{{ asset('front/assets/images/cat-education.jpg') }}" alt="Export guide">
          <div>
            <span class="plan-badge plan-gold plan-badge-sm">Gold</span>
            <h4>Export Readiness Checklist for Indian Manufacturers</h4>
            <span>Sneha Patel Exports · May 20, 2026</span>
          </div>
        </a>
      </div>
    </div>

    <div class="blog-grid">
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/cat-business.jpg') }}" alt="B2B growth">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">NK</span>
            <div>
              <strong>Nisha Kaur Trading Co.</strong>
              <span class="plan-badge plan-platinum plan-badge-sm">Platinum</span>
            </div>
          </div>
          <span class="blog-tag">B2B Trade</span>
          <h3>How MSMEs Can Win More B2B Buyers in 2026</h3>
          <p>Practical steps for small businesses to stand out on digital marketplaces and convert more buyer inquiries.</p>
          <div class="blog-footer">
            <span>May 28, 2026</span>
            <span>6 min read</span>
          </div>
        </div>
      </article>
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/cat-real-estate.jpg') }}" alt="Property investment">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">VP</span>
            <div>
              <strong>Mehta Real Estate</strong>
              <span class="plan-badge plan-gold plan-badge-sm">Gold</span>
            </div>
          </div>
          <span class="blog-tag">Real Estate</span>
          <h3>Commercial Property Investment Guide for SMEs</h3>
          <p>What business owners should know before investing in office, warehouse, or retail space in Gujarat.</p>
          <div class="blog-footer">
            <span>May 25, 2026</span>
            <span>8 min read</span>
          </div>
        </div>
      </article>
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/blog-2.jpg') }}" alt="Digital growth">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">AM</span>
            <div>
              <strong>Amit Mehta Consulting</strong>
              <span class="plan-badge plan-platinum plan-badge-sm">Platinum</span>
            </div>
          </div>
          <span class="blog-tag">MSME Growth</span>
          <h3>5 Digital Strategies Every Local Business Needs</h3>
          <p>From Google visibility to B2B listings — how to reach more customers without a big marketing budget.</p>
          <div class="blog-footer">
            <span>May 22, 2026</span>
            <span>5 min read</span>
          </div>
        </div>
      </article>
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/cat-education.jpg') }}" alt="Export checklist">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">SP</span>
            <div>
              <strong>Sneha Patel Exports</strong>
              <span class="plan-badge plan-gold plan-badge-sm">Gold</span>
            </div>
          </div>
          <span class="blog-tag">Export &amp; Import</span>
          <h3>Export Readiness Checklist for Indian Manufacturers</h3>
          <p>Documentation, compliance, and marketplace tips for businesses looking to sell globally.</p>
          <div class="blog-footer">
            <span>May 20, 2026</span>
            <span>7 min read</span>
          </div>
        </div>
      </article>
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/blog-1.jpg') }}" alt="Supplier verification">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">RK</span>
            <div>
              <strong>Raj Kumar Industries</strong>
              <span class="plan-badge plan-silver plan-badge-sm">Free · Private</span>
            </div>
          </div>
          <span class="blog-tag">B2B Trade</span>
          <h3>Internal Supplier Quality Checklist</h3>
          <p class="article-private-note">🔒 Private article — visible only to author (Free Plan).</p>
          <div class="blog-footer">
            <span>May 18, 2026</span>
            <span>4 min read</span>
          </div>
        </div>
      </article>
      <article class="blog-card blog-card-link">
        <div class="blog-thumb">
          <img src="{{ asset('front/assets/images/cat-health.jpg') }}" alt="Workplace wellness">
        </div>
        <div class="blog-body">
          <div class="article-author-bar">
            <span class="blog-author-avatar">PY</span>
            <div>
              <strong>Patel Yogeshkumar Tech</strong>
              <span class="plan-badge plan-platinum plan-badge-sm">Platinum</span>
            </div>
          </div>
          <span class="blog-tag">Technology</span>
          <h3>AI Tools That Help Small Businesses Save Time</h3>
          <p>From inquiry management to content creation — affordable tech for growing MSMEs in 2026.</p>
          <div class="blog-footer">
            <span>May 15, 2026</span>
            <span>5 min read</span>
          </div>
        </div>
      </article>
    </div>

    <nav class="pagination">
      <a href="#">‹</a>
      <span class="active">1</span>
      <a href="#">2</a>
      <a href="#">3</a>
      <a href="#">›</a>
    </nav>
  </div>

  <section class="section blog-newsletter-section">
    <div class="container">
      <div class="blog-newsletter">
        <div>
          <h2>Publish Your Articles on JustGoom</h2>
          <p>Gold &amp; Platinum members publish globally. Silver members keep articles private. Write and publish from your dashboard.</p>
        </div>
        <a href="{{ route('front.register') }}" class="btn btn-accent">Start Writing →</a>
      </div>
    </div>
  </section>
@endsection
