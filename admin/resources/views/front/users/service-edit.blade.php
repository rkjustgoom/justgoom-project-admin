@extends('front.layouts.user')

@section('title', 'Edit Service — Just Goom')
@section('page_title', 'My Services')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="My Services"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.services') }}">My Services</a> <span>/</span> <span>Edit Service</span></nav>
      <h2 class="user-form-page-title">Edit Service</h2>
      <p class="user-form-page-desc">Update service details visible on your public business profile.</p>

      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group">
            <label>Service Name *</label>
            <input type="text" class="user-form-control" data-crud-field="name" required>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Category *</label>
              <select class="user-form-control" data-crud-field="category" required>
                <option>Retail</option>
                <option>Wholesale</option>
                <option>Service</option>
                <option>Finance</option>
                <option>Tool</option>
                <option>Manufacturing</option>
              </select>
            </div>
            <div class="user-form-group">
              <label>Status</label>
              <select class="user-form-control" data-crud-field="status">
                <option>Active</option>
                <option>Inactive</option>
              </select>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Price Range</label>
              <input type="text" class="user-form-control" data-crud-field="price">
            </div>
            <div class="user-form-group">
              <label>Display Order</label>
              <input type="number" class="user-form-control" data-crud-field="order" min="1">
            </div>
          </div>
          <div class="user-form-group">
            <label>Short Description *</label>
            <textarea class="user-form-control" rows="3" data-crud-field="description" required></textarea>
          </div>
          <div class="user-form-group">
            <label>Service Image</label>
            <div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Replace service image (optional)</p></div>
          </div>
          <label class="user-form-check">
            <input type="checkbox" data-crud-field="featured"> Feature this service on profile homepage
          </label>
          <div class="user-form-actions">
            <div class="user-form-actions-left">
              <a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Service</a>
            </div>
            <a href="{{ route('front.users.services') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Update Service</button>
          </div>
        </form>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
