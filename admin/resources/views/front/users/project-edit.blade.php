@extends('front.layouts.user')

@section('title', 'Edit Project — Just Goom')
@section('page_title', 'My Projects')
@section('body_attrs', 'class="user-panel-body" data-page="projects" data-title="My Projects"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.projects') }}">My Projects</a> <span>/</span> <span>Edit Project</span></nav>
      <h2 class="user-form-page-title">Edit Project</h2>
      <p class="user-form-page-desc">Update project details, replace files, or change external links.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.projects.update', $project) }}" enctype="multipart/form-data" novalidate>
          @csrf @method('PUT')
          <div class="user-form-group" data-field="title">
            <label>Project Title *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title', $project->title) }}" placeholder="Enter project title" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="description">
            <label>Description</label>
            <textarea name="description" class="user-form-control @error('description') is-invalid @enderror" rows="3" maxlength="2000" placeholder="Brief description of the project...">{{ old('description', $project->description) }}</textarea>
            <small class="user-field-error">@error('description'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="type">
            <label>Project Type *</label>
            <select name="type" id="projectType" class="user-form-control @error('type') is-invalid @enderror">
              <option value="document" {{ old('type', $project->type) === 'document' ? 'selected' : '' }}>Document</option>
              <option value="video" {{ old('type', $project->type) === 'video' ? 'selected' : '' }}>Video Upload</option>
              <option value="link" {{ old('type', $project->type) === 'link' ? 'selected' : '' }}>External Video Link</option>
            </select>
            <small class="user-field-error">@error('type'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="file" id="fileGroup">
            <label>Replace File (optional)</label>
            <div class="user-upload-zone">
              <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov,.wmv" hidden>
              <p>Upload new file to replace current (max 100MB)</p>
            </div>
            @if($project->file_path)
              <p class="user-form-hint">Current: <a href="{{ asset($project->file_path) }}" target="_blank">View file</a> · Max 100MB</p>
            @else
              <p class="user-form-hint">PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MOV, WMV (max 100MB)</p>
            @endif
            <small class="user-field-error">@error('file'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="external_url" id="urlGroup" style="display:none;">
            <label>External Video URL</label>
            <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ old('external_url', $project->external_url) }}" placeholder="https://www.youtube.com/watch?v=...">
            <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="thumbnail">
            <label>Thumbnail Image (optional)</label>
            <div class="user-upload-zone">
              <input type="file" name="thumbnail" accept="image/*" hidden>
              <p>Upload new thumbnail (optional)</p>
            </div>
            @if($project->thumbnail)
              <p class="user-form-hint">Current: <img src="{{ asset($project->thumbnail) }}" alt="thumb" style="height:40px; border-radius:4px;"></p>
            @endif
            <small class="user-field-error">@error('thumbnail'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.projects') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Update Project</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/project-form.js') }}"></script>
@endpush
