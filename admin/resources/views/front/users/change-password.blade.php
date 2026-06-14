@extends('front.layouts.user')

@section('title', 'Change Password — Just Goom')
@section('page_title', 'Change Password')
@section('body_attrs', 'class="user-panel-body" data-page="change-password" data-title="Change Password"')

@section('content')
<div class="user-content">
      <div class="user-form-card">
        <form>
          <div class="user-form-group"><label>Current Password</label><input type="password" class="user-form-control" placeholder="Enter current password"></div>
          <div class="user-form-group"><label>New Password</label><input type="password" class="user-form-control" placeholder="Enter new password"></div>
          <div class="user-form-group"><label>Confirm New Password</label><input type="password" class="user-form-control" placeholder="Confirm new password"></div>
          <button type="button" class="user-btn user-btn-primary">Update Password</button>
        </form>
      </div>
    </div>
@endsection
