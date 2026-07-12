@extends('front.layouts.user')

@section('title', 'My Projects — Just Goom')
@section('page_title', 'My Projects')
@section('body_attrs', 'class="user-panel-body" data-page="projects" data-title="My Projects"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">📁</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Projects</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">📄</span><div class="user-stat-info"><h3>{{ $stats['documents'] }}</h3><span>Documents</span></div></div>
        <div class="user-stat-card red"><span class="user-stat-icon">🎬</span><div class="user-stat-info"><h3>{{ $stats['videos'] }}</h3><span>Videos</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">🔗</span><div class="user-stat-info"><h3>{{ $stats['links'] }}</h3><span>External Links</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Upload project documents, videos, or add external video links</span>
        <a href="{{ route('front.users.project-add') }}" class="user-btn user-btn-primary">+ Add Project</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Description</th><th>Added</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($projects as $project)
            <tr>
              <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
              <td><strong>{{ $project->title }}</strong></td>
              <td>
                @if($project->type === 'document')
                  <span class="user-badge user-badge-success">Document</span>
                @elseif($project->type === 'video')
                  <span class="user-badge user-badge-warning">Video</span>
                @else
                  <span class="user-badge user-badge-info">Link</span>
                @endif
              </td>
              <td>{{ Str::limit($project->description, 60) ?: '—' }}</td>
              <td>{{ $project->created_at?->format('M j, Y') }}</td>
              <td>
                @if($project->external_url)
                  <a href="{{ $project->external_url }}" target="_blank" class="user-table-action">View</a> ·
                @elseif($project->file_path)
                  <a href="{{ asset('storage/' . $project->file_path) }}" target="_blank" class="user-table-action">Download</a> ·
                @endif
                <a href="{{ route('front.users.projects.edit', $project) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.projects.destroy', $project) }}" style="display:inline" onsubmit="return confirm('Delete this project?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No projects yet. Upload project documents, videos, or add external video links.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($projects->hasPages())
      {{ $projects->links('front.partials.pagination') }}
      @endif
    </div>
@endsection
