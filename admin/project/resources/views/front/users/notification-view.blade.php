@extends('front.layouts.user')

@section('title', 'Notification — Just Goom')
@section('page_title', 'My Notification')
@section('body_attrs', 'class="user-panel-body" data-page="notifications" data-title="My Notification"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.notifications') }}">My Notification</a> <span>/</span> <span>View</span></nav>
      <h2 class="user-form-page-title" data-crud-bind="title">—</h2>
      <p class="user-text-muted" style="margin-bottom:24px"><span data-crud-bind="type">—</span> · <span data-crud-bind="date">—</span></p>
      <div class="user-form-card user-form-card-wide">
        <p style="font-size:15px;line-height:1.7;color:var(--user-text)" data-crud-bind="body">—</p>
        <div class="user-form-actions">
          <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Dismiss</a></div>
          <a href="{{ route('front.users.notifications') }}" class="user-btn user-btn-default">Back</a>
        </div>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
