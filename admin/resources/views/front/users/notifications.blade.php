@extends('front.layouts.user')

@section('title', 'My Notification — Just Goom')
@section('page_title', 'My Notification')
@section('body_attrs', 'class="user-panel-body" data-page="notifications" data-title="My Notification"')

@section('content')
<div class="user-content">
      <div class="user-panel">
        <div class="user-panel-head">Notifications</div>
        <div class="user-panel-body">
          <div class="user-list-item">
            <div><strong>Profile viewed 28 times today</strong><span>Visitor analytics · 2 hours ago</span></div>
            <a href="notification-view.html?id=1" class="user-table-action" style="font-size:12px;white-space:nowrap">View · Dismiss</a>
          </div>
          <div class="user-list-item">
            <div><strong>Homepage banner approved and live</strong><span>Content moderation · Yesterday</span></div>
            <a href="notification-view.html?id=2" class="user-table-action" style="font-size:12px;white-space:nowrap">View · Dismiss</a>
          </div>
          <div class="user-list-item">
            <div><strong>New inquiry from Raj Kumar</strong><span>Gold bulk order · May 31, 2026</span></div>
            <a href="notification-view.html?id=3" class="user-table-action" style="font-size:12px;white-space:nowrap">View · Dismiss</a>
          </div>
          <div class="user-list-item">
            <div><strong>Article published globally</strong><span>22K Gold Guide · May 28, 2026</span></div>
            <a href="notification-view.html?id=4" class="user-table-action" style="font-size:12px;white-space:nowrap">View · Dismiss</a>
          </div>
        </div>
      </div>
    </div>
@endsection
