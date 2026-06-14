@extends('front.layouts.user')

@section('title', 'My Articles — Just Goom')
@section('page_title', 'My Articles')
@section('body_attrs', 'class="user-panel-body" data-page="articles" data-title="My Articles"')

@section('content')
<div class="user-content">
      <div class="user-toolbar"><span class="user-text-muted">Platinum · Global publishing enabled</span><a href="{{ route('front.users.article-form') }}" class="user-btn user-btn-primary">+ Write Article</a></div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Title</th><th>Category</th><th>Visibility</th><th>Reads</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <tr><td>Why 22K Gold is the Smart Choice</td><td>Jewellery</td><td>Global</td><td>1,240</td><td><span class="user-badge user-badge-success">Published</span></td><td><a href="article-form.html?id=1" class="user-table-action">Edit</a> · <a href="delete.html?module=article&id=1&return=articles.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>Understanding Gold Making Charges</td><td>Business</td><td>Global</td><td>890</td><td><span class="user-badge user-badge-success">Published</span></td><td><a href="article-form.html?id=2" class="user-table-action">Edit</a> · <a href="delete.html?module=article&id=2&return=articles.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>B2B Bulk Gold Supply Guide</td><td>B2B Trade</td><td>Global</td><td>620</td><td><span class="user-badge user-badge-success">Published</span></td><td><a href="article-form.html?id=3" class="user-table-action">Edit</a> · <a href="delete.html?module=article&id=3&return=articles.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>Wedding Season Trends 2026</td><td>Jewellery</td><td>—</td><td>—</td><td><span class="user-badge user-badge-warning">Draft</span></td><td><a href="article-form.html?id=4" class="user-table-action">Edit</a> · <a href="delete.html?module=article&id=4&return=articles.html" class="user-table-action-muted">Delete</a></td></tr>
          </tbody>
        </table>
      </div>
    </div>
@endsection
