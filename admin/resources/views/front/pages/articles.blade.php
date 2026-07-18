@extends('front.layouts.app')

@section('title', 'Articles — Business Insights &amp; Promotions | Just Goom LLP')
@section('meta_description', 'Articles from JustGoom members — business tips, insights, and promotional content from verified businesses.')
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
    <p>Business tips and promotional articles from verified JustGoom members.</p>
  </div>
  <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
</section>

<div class="container blogs-page-layout">
  @php
    $fallbackImages = [
      asset('front/assets/images/blog-1.jpg'),
      asset('front/assets/images/blog-2.jpg'),
      asset('front/assets/images/blog-3.jpg'),
      asset('front/assets/images/cat-business.jpg'),
    ];

    $mapArticle = function ($article, $index = 0) use ($fallbackImages) {
      $company = $article->user?->companyProfile;
      $authorName = $company?->company_name
        ?: trim(($article->user?->fname ?? '') . ' ' . ($article->user?->lname ?? ''))
        ?: 'JustGoom Member';
      $initials = collect(preg_split('/\s+/', trim($authorName)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('') ?: 'JG';
      $published = $article->published_at ?? $article->created_at;
      $locationParts = array_filter([$company?->city, $company?->state]);
      $categoryName = $article->user?->category?->name ?? 'Business';

      return [
        'url' => route('front.articles.show', $article->slug),
        'title' => $article->title,
        'excerpt' => \Illuminate\Support\Str::limit(strip_tags($article->body), 140),
        'author' => $authorName,
        'initials' => $initials,
        'meta' => implode(' · ', array_filter([implode(', ', $locationParts), $categoryName])),
        'tag' => $categoryName,
        'date' => $published?->format('M j, Y') ?? '',
        'read' => max(1, (int) ceil(str_word_count(strip_tags($article->body)) / 200)) . ' min read',
        'image' => $article->featured_image
          ? asset($article->featured_image)
          : $fallbackImages[$index % count($fallbackImages)],
      ];
    };
  @endphp

  @if($articles->isEmpty())
    <div class="article-empty-state">
      <h2>No articles published yet</h2>
      <p>Check back soon for business insights from JustGoom members.</p>
    </div>
  @else
    @if($featuredMain && $articles->currentPage() === 1)
      @php
        $main = $mapArticle($featuredMain, 0);
        $sideItems = $featuredSide->values()->map(fn ($a, $i) => $mapArticle($a, $i + 1));
      @endphp
      <div class="blogs-featured">
        <a href="{{ $main['url'] }}" class="blog-featured-main blog-card-link">
          <div class="blog-thumb">
            <img src="{{ $main['image'] }}" alt="{{ $main['title'] }}">
          </div>
          <div class="blog-body">
            <div class="article-author-bar article-author-bar-lg">
              <span class="blog-author-avatar">{{ $main['initials'] }}</span>
              <div>
                <strong>{{ $main['author'] }}</strong>
                @if($main['meta'])
                  <span class="article-author-meta">{{ $main['meta'] }}</span>
                @endif
              </div>
            </div>
            <span class="blog-tag">{{ $main['tag'] }}</span>
            <h2>{{ $main['title'] }}</h2>
            <p>{{ $main['excerpt'] }}</p>
            <div class="blog-footer">
              <span>{{ $main['date'] }}</span>
              <span>{{ $main['read'] }}</span>
            </div>
          </div>
        </a>
        @if($sideItems->isNotEmpty())
          <div class="blog-featured-side">
            @foreach($sideItems as $side)
              <a href="{{ $side['url'] }}" class="blog-mini">
                <img src="{{ $side['image'] }}" alt="{{ $side['title'] }}">
                <div>
                  <h4>{{ $side['title'] }}</h4>
                  <span>{{ $side['author'] }} · {{ $side['date'] }}</span>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    @endif

    <div class="blog-grid">
      @foreach($gridArticles as $index => $article)
        @php $card = $mapArticle($article, $index); @endphp
        <a href="{{ $card['url'] }}" class="blog-card blog-card-link">
          <div class="blog-thumb">
            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy">
          </div>
          <div class="blog-body">
            <div class="article-author-bar">
              <span class="blog-author-avatar">{{ $card['initials'] }}</span>
              <div>
                <strong>{{ $card['author'] }}</strong>
              </div>
            </div>
            <span class="blog-tag">{{ $card['tag'] }}</span>
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['excerpt'] }}</p>
            <div class="blog-footer">
              <span>{{ $card['date'] }}</span>
              <span>{{ $card['read'] }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    @if($articles->hasPages())
      <div class="blogs-pagination">
        {{ $articles->links('front.partials.pagination') }}
      </div>
    @endif
  @endif
</div>

<section class="section blog-newsletter-section">
  <div class="container">
    <div class="blog-newsletter">
      <div>
        <h2>Publish Your Articles on JustGoom</h2>
        <p>Share your business expertise with buyers and partners. Write and publish from your dashboard.</p>
      </div>
      <a href="{{ route('front.register') }}" class="btn btn-accent">Start Writing →</a>
    </div>
  </div>
</section>
@endsection
