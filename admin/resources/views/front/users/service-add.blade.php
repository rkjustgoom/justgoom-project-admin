@extends('front.layouts.user')

@section('title', 'Add Service — Just Goom')
@section('page_title', 'My Services')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="My Services"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.services') }}">My Services</a> <span>/</span> <span>Add Service</span></nav>
      <h2 class="user-form-page-title">Add Service</h2>
      <p class="user-form-page-desc">List a product or service offering on your JustGoom business profile.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.services.store') }}" enctype="multipart/form-data" id="serviceForm" novalidate>
          @csrf
          <div class="user-form-group" data-field="product_name">
            <label>Service Name *</label>
            <input type="text" name="product_name" class="user-form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" placeholder="e.g. Solar Panel Installation" maxlength="200">
            <small class="user-field-error">@error('product_name'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_desc">
            <label>Short Description</label>
            <textarea name="product_desc" class="user-form-control @error('product_desc') is-invalid @enderror" rows="3" maxlength="5000" placeholder="Describe this service in 2–3 lines...">{{ old('product_desc') }}</textarea>
            <small class="user-field-error">@error('product_desc'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_image">
            <label>Service Image</label>
            <div class="user-upload-zone">
              <input type="file" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>Upload service image (optional)</p>
            </div>
            <p class="user-form-hint">JPG, PNG, WebP or GIF · max 2 MB</p>
            <small class="user-field-error">@error('product_image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.services') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Add Service</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/service-form.js') }}"></script>
@endpush
