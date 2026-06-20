@extends('front.layouts.user')

@section('title', 'My Notification — Just Goom')
@section('page_title', 'My Notification')
@section('body_attrs', 'class="user-panel-body" data-page="notifications" data-title="My Notification"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🔔</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">🆕</span><div class="user-stat-info"><h3>{{ $stats['unread'] }}</h3><span>Unread</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['read'] }}</h3><span>Read</span></div></div>
      </div>
      <div class="user-panel">
        <div class="user-panel-head">Notifications</div>
        <div class="user-panel-body">
          @forelse($notifications as $notification)
          <div class="user-list-item">
            <div>
              <strong>{{ $notification->title }}</strong>
              <span>{{ Str::limit($notification->body, 80) ?: ucfirst(str_replace('_', ' ', $notification->type)) }} · {{ $notification->created_at?->format('M j, Y') }}</span>
            </div>
            <a href="{{ route('front.users.notifications.show', $notification) }}" class="user-table-action" style="font-size:12px;white-space:nowrap">View@if(!$notification->isRead()) · Mark read@endif</a>
          </div>
          @empty
          <div class="user-list-item">
            <div>
              <strong>No notifications yet</strong>
              <span>Updates about your profile and activity will show here.</span>
            </div>
          </div>
          @endforelse
        </div>
      </div>
    </div>
@endsection
