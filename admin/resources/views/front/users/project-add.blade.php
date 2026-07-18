@extends('front.layouts.user')

@section('title', 'Add Project — Just Goom')
@section('page_title', 'My Projects')
@section('body_attrs', 'class="user-panel-body" data-page="projects" data-title="My Projects"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.projects') }}">My Projects</a> <span>/</span> <span>Add Project</span></nav>
      <h2 class="user-form-page-title">Add Project</h2>
      <p class="user-form-page-desc">Upload project documents (PDF, DOC, PPT), videos, or add external video links.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.projects.store') }}" enctype="multipart/form-data" novalidate>
          @csrf
          <div class="user-form-group" data-field="title">
            <label>Project Title *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter project title" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="description">
            <label>Description</label>
            <textarea name="description" class="user-form-control @error('description') is-invalid @enderror" rows="3" maxlength="2000" placeholder="Brief description of the project...">{{ old('description') }}</textarea>
            <small class="user-field-error">@error('description'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="type">
            <label>Project Type *</label>
            <select name="type" id="projectType" class="user-form-control @error('type') is-invalid @enderror">
              <option value="document" {{ old('type') === 'document' ? 'selected' : '' }}>Document (PDF, DOC, DOCX, PPT, PPTX)</option>
              <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video Upload</option>
              <option value="link" {{ old('type') === 'link' ? 'selected' : '' }}>External Video Link (YouTube, Vimeo)</option>
            </select>
            <small class="user-field-error">@error('type'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="file" id="fileGroup">
            <label>Upload File</label>
            <div class="user-upload-zone">
              <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov,.wmv" hidden>
              <p>Upload file (max 100MB)</p>
            </div>
            <p class="user-form-hint">PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MOV, WMV (max 100MB)</p>
            <small class="user-field-error">@error('file'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="external_url" id="urlGroup" style="display:none;">
            <label>External Video URL</label>
            <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ old('external_url') }}" placeholder="https://www.youtube.com/watch?v=...">
            <p class="user-form-hint">YouTube, Vimeo, or other video platform links</p>
            <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="thumbnail">
            <label>Thumbnail Image (optional)</label>
            <div class="user-upload-zone">
              <input type="file" name="thumbnail" accept="image/*" hidden>
              <p>Upload thumbnail image (optional)</p>
            </div>
            <small class="user-field-error">@error('thumbnail'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.projects') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Save Project</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/project-form.js') }}"></script>
@endpush
