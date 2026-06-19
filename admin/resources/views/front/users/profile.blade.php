@extends('front.layouts.user')

@section('title', 'My Profile — Just Goom')
@section('page_title', 'My Profile')
@section('body_attrs', 'class="user-panel-body" data-page="profile" data-title="My Profile"')

@section('content')
<div class="user-content">
      <div class="user-toolbar">
        <p class="user-text-muted">Profile completion required for Free Plan onboarding — <strong class="user-text-accent">{{ $completionPercent }}%</strong> complete</p>
        <a href="{{ $previewUrl }}" target="_blank" class="user-btn user-btn-default">Preview Public Profile</a>
      </div>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card">
        <form method="POST" action="{{ route('front.users.profile.update') }}" id="profileForm" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="user-form-group">
            <label>Company Logo</label>
            @if($profile->logo)
              <img src="{{ asset($profile->logo) }}" alt="{{ $profile->company_name }} logo" class="user-preview-thumb" style="display:block;margin-bottom:12px;max-width:120px;max-height:120px;object-fit:contain;border-radius:8px;">
            @endif
            <div class="user-upload-zone">
              <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>@if($profile->logo)Replace logo (optional)@else Drag &amp; drop or <strong>click to upload</strong> logo (optional)@endif</p>
            </div>
            @error('logo')<small class="user-field-error">{{ $message }}</small>@enderror
            <p class="user-form-hint">JPG, PNG, WebP or GIF · max 2 MB · square image recommended</p>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Business Name *</label>
              <input type="text" name="company_name" class="user-form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $profile->company_name) }}" required>
              @error('company_name')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
            <div class="user-form-group">
              <label>Category *</label>
              <select name="category_id" id="profileCategory" class="user-form-control @error('category_id') is-invalid @enderror" required>
                <option value="">Select category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id', $user->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Sub Category *</label>
              <select name="sub_category_id" id="profileSubCategory" class="user-form-control @error('sub_category_id') is-invalid @enderror" required>
                <option value="">Select sub category</option>
                @foreach($subCategories as $subCategory)
                  <option value="{{ $subCategory->id }}" @selected(old('sub_category_id', $user->sub_category_id) == $subCategory->id)>{{ $subCategory->name }}</option>
                @endforeach
              </select>
              @error('sub_category_id')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
            <div class="user-form-group">
              <label>City *</label>
              <input type="text" name="city" class="user-form-control @error('city') is-invalid @enderror" value="{{ old('city', $profile->city ?? $user->city) }}" required>
              @error('city')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
          </div>
          <div class="user-form-group">
            <label>Tagline</label>
            <input type="text" name="tagline" class="user-form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $profile->tagline) }}" placeholder="Short business tagline">
            @error('tagline')<small class="user-field-error">{{ $message }}</small>@enderror
          </div>
          <div class="user-form-group">
            <label>About Business *</label>
            <textarea name="business_desc" class="user-form-control @error('business_desc') is-invalid @enderror" rows="4" required>{{ old('business_desc', $profile->business_desc) }}</textarea>
            @error('business_desc')<small class="user-field-error">{{ $message }}</small>@enderror
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Phone *</label>
              <input type="tel" name="phone" class="user-form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone ?? $user->phone) }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" required>
              @error('phone')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
            <div class="user-form-group">
              <label>Email *</label>
              <input type="email" name="email" class="user-form-control @error('email') is-invalid @enderror" value="{{ old('email', $profile->email ?? $user->email) }}" required>
              @error('email')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
          </div>
          <div class="user-form-group">
            <label>Address</label>
            <input type="text" name="address" class="user-form-control @error('address') is-invalid @enderror" value="{{ old('address', $profile->address) }}">
            @error('address')<small class="user-field-error">{{ $message }}</small>@enderror
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Plan</label>
              <input type="text" class="user-form-control" value="{{ $planName }}" readonly>
            </div>
            <div class="user-form-group">
              <label>Public Profile Slug</label>
              <input type="text" class="user-form-control" value="{{ $profile->slug }}" readonly>
            </div>
          </div>
          <button type="submit" class="user-btn user-btn-primary">Save Profile</button>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script>
  window.PROFILE_SUBCATEGORIES_URL = @json(url('/register/sub-categories'));
  window.PROFILE_OLD = @json(['sub_category_id' => old('sub_category_id', $user->sub_category_id)]);
</script>
<script src="{{ asset('front/assets/js/profile.js') }}"></script>
@endpush
