@extends('front.layouts.user')

@section('title', 'Add Service — Just Goom')
@section('page_title', 'My Services')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="My Services"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.services') }}">My Services</a> <span>/</span> <span>Add Service</span></nav>
      <h2 class="user-form-page-title">Add Service</h2>
      <p class="user-form-page-desc">List a product or service offering on your JustGoom business profile.</p>

      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group">
            <label>Service Name *</label>
            <input type="text" class="user-form-control" data-crud-field="name" placeholder="e.g. 22K Gold Jewellery" required>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Category *</label>
              <select class="user-form-control" data-crud-field="category" required>
                <option value="">Select category</option>
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
                <option selected>Active</option>
                <option>Inactive</option>
              </select>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Price Range (optional)</label>
              <input type="text" class="user-form-control" data-crud-field="price" placeholder="e.g. ₹5,000 – ₹2,00,000">
            </div>
            <div class="user-form-group">
              <label>Display Order</label>
              <input type="number" class="user-form-control" data-crud-field="order" placeholder="1" min="1" value="1">
            </div>
          </div>
          <div class="user-form-group">
            <label>Short Description *</label>
            <textarea class="user-form-control" rows="3" data-crud-field="description" placeholder="Describe this service in 2–3 lines..." required></textarea>
          </div>
          <div class="user-form-group">
            <label>Service Image</label>
            <div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Upload service image (optional)</p></div>
          </div>
          <label class="user-form-check">
            <input type="checkbox" data-crud-field="featured"> Feature this service on profile homepage
          </label>
          <div class="user-form-actions">
            <a href="{{ route('front.users.services') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Add Service</button>
          </div>
        </form>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
