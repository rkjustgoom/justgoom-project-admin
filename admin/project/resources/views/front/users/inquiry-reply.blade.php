@extends('front.layouts.user')

@section('title', 'Reply Inquiry — Just Goom')
@section('page_title', 'My Inquiry')
@section('body_attrs', 'class="user-panel-body" data-page="inquiries" data-title="My Inquiry"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.inquiries') }}">My Inquiry</a> <span>/</span> <span>Reply</span></nav>
      <h2 class="user-form-page-title">Reply to Inquiry</h2>
      <div class="user-form-card user-form-card-wide">
        <div class="user-form-group"><label>To</label><input type="text" class="user-form-control" data-crud-bind="from" readonly></div>
        <div class="user-form-group"><label>Subject</label><input type="text" class="user-form-control" data-crud-bind="subject" readonly></div>
        <div class="user-form-group"><label>Original Message</label><textarea class="user-form-control" rows="4" data-crud-bind="message" readonly></textarea></div>
        <div class="user-form-group"><label>Your Reply *</label><textarea class="user-form-control" rows="6" placeholder="Write your response to the customer..."></textarea></div>
        <div class="user-form-actions">
          <a href="{{ route('front.users.inquiries') }}" class="user-btn user-btn-default">Cancel</a>
          <button type="button" class="user-btn user-btn-primary" data-crud-save>Send Reply</button>
        </div>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
