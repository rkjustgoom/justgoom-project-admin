@extends('front.layouts.user')

@section('title', 'Dashboard — Just Goom')
@section('page_title', 'Dashboard')
@section('body_attrs', 'class="user-panel-body" data-page="dashboard" data-title="Dashboard"')

@section('content')
<div class="user-content">
        <div class="user-content-intro">
          <p>Here's what's happening with your business profile today.</p>
        </div>

        <div class="user-stat-row">
          <a href="{{ route('front.users.services') }}" class="user-stat-card green">
            <span class="user-stat-icon">💼</span>
            <div class="user-stat-info">
              <h3>7</h3>
              <span>Services Listed</span>
            </div>
          </a>
          <a href="{{ route('front.users.videos') }}" class="user-stat-card yellow">
            <span class="user-stat-icon">🎬</span>
            <div class="user-stat-info">
              <h3>4</h3>
              <span>Promo Videos</span>
            </div>
          </a>
          <a href="{{ route('front.users.inquiries') }}" class="user-stat-card red">
            <span class="user-stat-icon">💬</span>
            <div class="user-stat-info">
              <h3>3</h3>
              <span>New Inquiries</span>
            </div>
          </a>
          <a href="{{ route('front.users.notifications') }}" class="user-stat-card grey">
            <span class="user-stat-icon">🔔</span>
            <div class="user-stat-info">
              <h3>2</h3>
              <span>Notifications</span>
            </div>
          </a>
        </div>

        <div class="user-panels-row">
          <div class="user-panel">
            <div class="user-panel-head">Recent Inquiries</div>
            <div class="user-panel-body">
              <div class="user-list-item">
                <div>
                  <strong>Raj Kumar — Gold Bulk Order</strong>
                  <span>Looking for 22K wedding collection wholesale pricing</span>
                </div>
                <span class="user-badge user-badge-warning">New</span>
              </div>
              <div class="user-list-item">
                <div>
                  <strong>Priya Sharma — Custom Design</strong>
                  <span>Request for custom necklace design inquiry</span>
                </div>
                <span class="user-badge user-badge-success">Replied</span>
              </div>
              <div class="user-list-item">
                <div>
                  <strong>Amit Mehta — B2B Partnership</strong>
                  <span>Interested in long-term supply partnership</span>
                </div>
                <span class="user-badge user-badge-warning">New</span>
              </div>
              <a href="{{ route('front.users.inquiries') }}" class="user-link-more">View all inquiries →</a>
            </div>
          </div>
          <div class="user-panel">
            <div class="user-panel-head">Recent Notifications</div>
            <div class="user-panel-body">
              <div class="user-list-item">
                <div>
                  <strong>Profile viewed 28 times today</strong>
                  <span>Visitor analytics update · 2 hours ago</span>
                </div>
              </div>
              <div class="user-list-item">
                <div>
                  <strong>Homepage banner approved</strong>
                  <span>Your promotional banner is now live · Yesterday</span>
                </div>
                <span class="user-badge user-badge-success">Live</span>
              </div>
              <div class="user-list-item">
                <div>
                  <strong>Article published globally</strong>
                  <span>22K Gold Guide is now public · May 28</span>
                </div>
              </div>
              <a href="{{ route('front.users.notifications') }}" class="user-link-more">View all notifications →</a>
            </div>
          </div>
        </div>
      </div>
@endsection
