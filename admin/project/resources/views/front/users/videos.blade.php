@extends('front.layouts.user')

@section('title', 'My Video — Just Goom')
@section('page_title', 'My Video')
@section('body_attrs', 'class="user-panel-body" data-page="videos" data-title="My Video"')

@section('content')
<div class="user-content">
      <div class="user-toolbar"><span class="user-text-muted">Platinum Plan · Promotional videos on homepage</span><a href="{{ route('front.users.video-form') }}" class="user-btn user-btn-primary">+ Upload Video</a></div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Title</th><th>Duration</th><th>Views</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <tr><td>Wedding Collection 2026 Showcase</td><td>1:24</td><td>2,400</td><td><span class="user-badge user-badge-success">Live</span></td><td><a href="video-form.html?id=1" class="user-table-action">Edit</a> · <a href="delete.html?module=video&id=1&return=videos.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>Custom Design Process Tour</td><td>0:58</td><td>1,820</td><td><span class="user-badge user-badge-success">Live</span></td><td><a href="video-form.html?id=2" class="user-table-action">Edit</a> · <a href="delete.html?module=video&id=2&return=videos.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>B2B Bulk Order Walkthrough</td><td>2:05</td><td>—</td><td><span class="user-badge user-badge-warning">Pending</span></td><td><a href="video-form.html?id=3" class="user-table-action">Edit</a> · <a href="delete.html?module=video&id=3&return=videos.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>22K Gold Purity Explained</td><td>1:10</td><td>980</td><td><span class="user-badge user-badge-success">Live</span></td><td><a href="video-form.html?id=4" class="user-table-action">Edit</a> · <a href="delete.html?module=video&id=4&return=videos.html" class="user-table-action-muted">Delete</a></td></tr>
          </tbody>
        </table>
      </div>
    </div>
@endsection
