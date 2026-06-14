@extends('front.layouts.user')

@section('title', 'Add Team Member — Just Goom')
@section('page_title', 'My Team')
@section('body_attrs', 'class="user-panel-body" data-page="team" data-title="My Team"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.team') }}">My Team</a> <span>/</span> <span>Add Member</span></nav>
      <h2 class="user-form-page-title">Add Team Member</h2>
      <p class="user-form-page-desc">Add staff who represent your business on your JustGoom profile.</p>

      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Full Name *</label>
              <input type="text" class="user-form-control" data-crud-field="name" placeholder="Patel Ramesh" required>
            </div>
            <div class="user-form-group">
              <label>Designation / Role *</label>
              <input type="text" class="user-form-control" data-crud-field="role" placeholder="Sales Manager" required>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Email *</label>
              <input type="email" class="user-form-control" data-crud-field="email" placeholder="name@company.com" required>
            </div>
            <div class="user-form-group">
              <label>Phone *</label>
              <input type="tel" class="user-form-control" data-crud-field="phone" placeholder="+91 98765 43210" required>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Department</label>
              <select class="user-form-control" data-crud-field="department">
                <option value="">Select department</option>
                <option>Sales</option>
                <option>Operations</option>
                <option>Wholesale</option>
                <option>Customer Support</option>
                <option>Management</option>
              </select>
            </div>
            <div class="user-form-group">
              <label>Status</label>
              <select class="user-form-control" data-crud-field="status">
                <option selected>Active</option>
                <option>Inactive</option>
              </select>
            </div>
          </div>
          <div class="user-form-group">
            <label>Profile Photo</label>
            <div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Upload team member photo (optional)</p></div>
          </div>
          <div class="user-form-group">
            <label>Bio / Responsibilities</label>
            <textarea class="user-form-control" rows="3" data-crud-field="bio" placeholder="Brief description of their role and responsibilities..."></textarea>
          </div>
          <label class="user-form-check">
            <input type="checkbox" data-crud-field="primary"> Show as primary contact on public profile
          </label>
          <div class="user-form-actions">
            <a href="{{ route('front.users.team') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Add Member</button>
          </div>
        </form>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
