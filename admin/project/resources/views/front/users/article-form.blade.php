@extends('front.layouts.user')

@section('title', 'Article — Just Goom')
@section('page_title', 'My Articles')
@section('body_attrs', 'class="user-panel-body" data-page="articles" data-title="My Articles"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.articles') }}">My Articles</a> <span>/</span> <span id="formBreadcrumb">Write Article</span></nav>
      <h2 class="user-form-page-title" id="formTitle">Write New Article</h2>
      <p class="user-form-page-desc">Platinum plan · Global publishing enabled across JustGoom.</p>
      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group"><label>Article Title *</label><input type="text" class="user-form-control" data-crud-field="title" placeholder="Enter a compelling title..."></div>
          <div class="user-form-row">
            <div class="user-form-group"><label>Category *</label><select class="user-form-control" data-crud-field="category"><option>B2B Trade</option><option>Manufacturing</option><option>Real Estate</option><option>Jewellery</option><option>Business</option><option>Technology</option><option>MSME Growth</option></select></div>
            <div class="user-form-group"><label>Visibility</label><select class="user-form-control" data-crud-field="visibility"><option>Global</option><option>Private</option></select></div>
          </div>
          <div class="user-form-group" id="statusGroup" style="display:none"><label>Status</label><select class="user-form-control" data-crud-field="status"><option>Published</option><option>Draft</option></select></div>
          <div class="user-form-group"><label>Featured Image</label><div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Upload cover image (16:9)</p></div></div>
          <div class="user-form-group"><label>Article Content *</label><textarea class="user-form-control" rows="10" data-crud-field="content" placeholder="Write your promotional article here..."></textarea></div>
          <div class="user-form-actions">
            <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Article</a></div>
            <a href="{{ route('front.users.articles') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-default" data-crud-draft>Save Draft</button>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Publish Article</button>
          </div>
        </form>
      </div>
    </div>
<script>
  (function() {
    var id = new URLSearchParams(location.search).get('id');
    if (id) {
      document.body.setAttribute('data-mode', 'edit');
      document.getElementById('formTitle').textContent = 'Edit Article';
      document.getElementById('formBreadcrumb').textContent = 'Edit Article';
      document.getElementById('statusGroup').style.display = 'block';
    }
  })();
</script>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
