@extends('front.layouts.user')

@section('title', 'View Inquiry — Just Goom')
@section('page_title', 'My Inquiry')
@section('body_attrs', 'class="user-panel-body" data-page="inquiries" data-title="My Inquiry"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.inquiries') }}">My Inquiry</a> <span>/</span> <span>View Inquiry</span></nav>
      <h2 class="user-form-page-title">Inquiry Details</h2>
      <div class="user-form-card user-form-card-wide">
        <dl class="user-detail-grid">
          <dt>From</dt><dd data-crud-bind="from">—</dd>
          <dt>Email</dt><dd data-crud-bind="email">—</dd>
          <dt>Phone</dt><dd data-crud-bind="phone">—</dd>
          <dt>Date</dt><dd data-crud-bind="date">—</dd>
          <dt>Status</dt><dd data-crud-bind="status">—</dd>
          <dt>Subject</dt><dd data-crud-bind="subject">—</dd>
          <dt>Message</dt><dd data-crud-bind="message" style="grid-column:1/-1;padding-top:8px;line-height:1.6">—</dd>
        </dl>
        <div data-crud-reply-block style="display:none;margin-top:24px;padding-top:20px;border-top:1px solid var(--user-border)">
          <strong style="display:block;margin-bottom:8px;color:var(--user-success)">Your Reply</strong>
          <p data-crud-bind="reply" style="font-size:14px;color:var(--user-muted);line-height:1.6">—</p>
        </div>
        <div class="user-form-actions">
          <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Inquiry</a></div>
          <a href="{{ route('front.users.inquiries') }}" class="user-btn user-btn-default">Back</a>
          <a href="#" data-crud-reply-link class="user-btn user-btn-primary">Reply</a>
        </div>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
