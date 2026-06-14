@extends('front.layouts.user')

@section('title', 'Video — Just Goom')
@section('page_title', 'My Video')
@section('body_attrs', 'class="user-panel-body" data-page="videos" data-title="My Video"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.videos') }}">My Video</a> <span>/</span> <span id="formBreadcrumb">Upload Video</span></nav>
      <h2 class="user-form-page-title" id="formTitle">Upload Promotional Video</h2>
      <p class="user-form-page-desc">Max 3 minutes · MP4 or WebM · Featured on homepage for Platinum plan.</p>
      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group"><label>Video Title *</label><input type="text" class="user-form-control" data-crud-field="title" placeholder="e.g. Wedding Collection 2026 Showcase"></div>
          <div class="user-form-group"><label>Description</label><textarea class="user-form-control" rows="3" data-crud-field="description" placeholder="Brief description..."></textarea></div>
          <div class="user-form-group" id="statusGroup" style="display:none"><label>Status</label><select class="user-form-control" data-crud-field="status"><option>Live</option><option>Pending</option></select></div>
          <div class="user-form-group"><label>Video File *</label><div class="user-upload-zone"><input type="file" accept="video/*" hidden><p>Drag &amp; drop or <strong>click to upload video</strong></p></div></div>
          <div class="user-form-group"><label>Custom Thumbnail (optional)</label><div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Upload thumbnail (16:9)</p></div></div>
          <div class="user-form-actions">
            <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Video</a></div>
            <a href="{{ route('front.users.videos') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Save Video</button>
          </div>
        </form>
      </div>
    </div>
<script>
  (function() {
    var id = new URLSearchParams(location.search).get('id');
    if (id) {
      document.body.setAttribute('data-mode', 'edit');
      document.getElementById('formTitle').textContent = 'Edit Promotional Video';
      document.getElementById('formBreadcrumb').textContent = 'Edit Video';
      document.getElementById('statusGroup').style.display = 'block';
    }
  })();
</script>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
