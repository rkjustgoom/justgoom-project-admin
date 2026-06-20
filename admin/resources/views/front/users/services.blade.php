@extends('front.layouts.user')

@section('title', 'My Services — Just Goom')
@section('page_title', 'My Services')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="My Services"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">💼</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Services</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
        <div class="user-stat-card red"><span class="user-stat-icon">⭐</span><div class="user-stat-info"><h3>{{ $stats['featured'] }}</h3><span>Featured</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Products and services on your public profile</span>
        <a href="{{ route('front.users.service-add') }}" class="user-btn user-btn-primary">+ Add Service</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Service</th><th>Description</th><th>Added</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($services as $service)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td><strong>{{ $service->product_name }}</strong></td>
              <td>{{ Str::limit($service->product_desc, 80) ?: '—' }}</td>
              <td>{{ $service->created_at?->format('M j, Y') }}</td>
              <td>
                <a href="{{ route('front.users.services.edit', $service) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.services.destroy', $service) }}" style="display:inline" onsubmit="return confirm('Remove this service?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="user-text-muted" style="text-align:center;padding:24px;">No services yet. Add your first service to show it on your public profile.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
@endsection
