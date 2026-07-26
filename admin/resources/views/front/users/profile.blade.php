@extends('front.layouts.user')

@section('title', 'My Profile — Just Goom')
@section('page_title', 'My Profile')
@section('body_attrs', 'class="user-panel-body" data-page="profile" data-title="My Profile"')

@push('styles')
<style>
  .ms-wrap { position: relative; }
  .ms-wrap.is-disabled { opacity: 0.65; pointer-events: none; }
  .ms-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    width: 100%;
    min-height: 42px;
    padding: 8px 12px;
    border: 1px solid #d0d7de;
    border-radius: 8px;
    background: #fff;
    color: #64748b;
    font-size: 14px;
    text-align: left;
    cursor: pointer;
    box-sizing: border-box;
  }
  .ms-wrap.is-open .ms-trigger,
  .ms-trigger:focus { outline: none; border-color: #1A428A; }
  .ms-trigger.is-invalid { border-color: #c0392b; }
  .ms-trigger-text {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .ms-wrap.has-value .ms-trigger-text { color: #1e293b; font-weight: 500; }
  .ms-chevron {
    flex-shrink: 0;
    width: 10px;
    height: 10px;
    border-right: 1.5px solid #94a3b8;
    border-bottom: 1.5px solid #94a3b8;
    transform: rotate(45deg) translateY(-2px);
    transition: transform 0.2s ease;
  }
  .ms-wrap.is-open .ms-chevron { transform: rotate(225deg) translateY(-1px); }
  .ms-dropdown {
    position: absolute;
    z-index: 50;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
    overflow: hidden;
  }
  .ms-dropdown[hidden] { display: none; }
  .ms-list { max-height: 220px; overflow-y: auto; padding: 6px 0; }
  .ms-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    margin: 0;
    cursor: pointer;
    font-size: 13px;
    color: #475569;
    user-select: none;
  }
  .ms-option:hover { background: #f8fafc; }
  .ms-option-all {
    font-weight: 600;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 2px;
    padding-bottom: 11px;
  }
  .ms-option input {
    width: 13px;
    height: 13px;
    margin: 0 10px 0 0;
    accent-color: #1A428A;
    cursor: pointer;
    flex-shrink: 0;
  }
  .ms-empty { padding: 14px; font-size: 13px; color: #94a3b8; text-align: center; }
</style>
@endpush

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
        <form method="POST" action="{{ route('front.users.profile.update') }}" id="profileForm" enctype="multipart/form-data" novalidate>
          @csrf
          @method('PUT')
          <div class="user-form-group" data-field="logo">
            <label>Company Logo</label>
            <img
              src="{{ $profile->logo ? asset($profile->logo) : '' }}"
              alt="{{ $profile->company_name }} logo"
              class="company-logo-box"
              id="profileLogoPreview"
              @if(!$profile->logo) style="display:none" @endif
            >
            <div class="user-upload-zone">
              <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>@if($profile->logo)Replace logo (optional)@else Drag &amp; drop or <strong>click to upload</strong> logo (optional)@endif</p>
            </div>
            <p class="user-form-hint">JPG, PNG, WebP or GIF · max 2 MB · displays at 120×120 px on your public profile</p>
            <small class="user-field-error">@error('logo'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="company_name">
              <label>Business Name *</label>
              <input type="text" name="company_name" class="user-form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $profile->company_name) }}" maxlength="200">
              <small class="user-field-error">@error('company_name'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="category_id">
              <label>Category *</label>
              <select name="category_id" id="profileCategory" class="user-form-control @error('category_id') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id', $user->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              <small class="user-field-error">@error('category_id'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="sub_category_id">
              <label>Sub Category *</label>
              <div class="ms-wrap @error('sub_category_id') is-invalid @enderror @error('sub_category_id.*') is-invalid @enderror" id="profileSubCategoryWrap">
                <button type="button" class="ms-trigger user-form-control @error('sub_category_id') is-invalid @enderror @error('sub_category_id.*') is-invalid @enderror" id="profileSubCategoryTrigger" aria-haspopup="listbox" aria-expanded="false">
                  <span class="ms-trigger-text" id="profileSubCategoryText">None selected</span>
                  <span class="ms-chevron" aria-hidden="true"></span>
                </button>
                <div class="ms-dropdown" id="profileSubCategoryDropdown" hidden>
                  <div class="ms-list" id="profileSubCategory" role="listbox" aria-multiselectable="true"></div>
                </div>
                <div id="profileSubCategoryInputs" aria-hidden="true"></div>
              </div>
              <small class="user-field-error">
                @error('sub_category_id'){{ $message }}@enderror
                @error('sub_category_id.*'){{ $message }}@enderror
              </small>
            </div>
            <div class="user-form-group" data-field="city">
              <label>City *</label>
              <input type="text" name="city" class="user-form-control @error('city') is-invalid @enderror" value="{{ old('city', $profile->city ?? $user->city) }}" maxlength="100">
              <small class="user-field-error">@error('city'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-group" data-field="tagline">
            <label>Tagline</label>
            <input type="text" name="tagline" class="user-form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $profile->tagline) }}" placeholder="Short business tagline" maxlength="255">
            <small class="user-field-error">@error('tagline'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="business_desc">
            <label>About Business *</label>
            <textarea name="business_desc" class="user-form-control @error('business_desc') is-invalid @enderror" rows="4" maxlength="5000">{{ old('business_desc', $profile->business_desc) }}</textarea>
            <small class="user-field-error">@error('business_desc'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="phone">
              <label>Phone *</label>
              <input type="tel" name="phone" class="user-form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone ?? $user->phone) }}" maxlength="10" inputmode="numeric" placeholder="Enter Phone Number">
              <small class="user-field-error">@error('phone'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="email">
              <label>Email *</label>
              <input type="email" name="email" class="user-form-control @error('email') is-invalid @enderror" value="{{ old('email', $profile->email ?? $user->email) }}" maxlength="191">
              <small class="user-field-error">@error('email'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-group" data-field="address">
            <label>Address</label>
            <input type="text" name="address" class="user-form-control @error('address') is-invalid @enderror" value="{{ old('address', $profile->address) }}" maxlength="500">
            <small class="user-field-error">@error('address'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="social_website">
              <label>Website</label>
              <input type="url" name="social_website" class="user-form-control @error('social_website') is-invalid @enderror" value="{{ old('social_website', $profile->social_website) }}" placeholder="https://www.example.com" maxlength="255">
              <small class="user-field-error">@error('social_website'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="social_subwebsite">
              <label>Sub Website</label>
              <input type="url" name="social_subwebsite" class="user-form-control @error('social_subwebsite') is-invalid @enderror" value="{{ old('social_subwebsite', $profile->social_subwebsite) }}" placeholder="https://shop.example.com" maxlength="255">
              <small class="user-field-error">@error('social_subwebsite'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="social_facebook">
              <label>Facebook</label>
              <input type="url" name="social_facebook" class="user-form-control @error('social_facebook') is-invalid @enderror" value="{{ old('social_facebook', $profile->social_facebook) }}" placeholder="https://facebook.com/yourpage" maxlength="255">
              <small class="user-field-error">@error('social_facebook'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="social_twitter">
              <label>Twitter / X</label>
              <input type="url" name="social_twitter" class="user-form-control @error('social_twitter') is-invalid @enderror" value="{{ old('social_twitter', $profile->social_twitter) }}" placeholder="https://x.com/yourhandle" maxlength="255">
              <small class="user-field-error">@error('social_twitter'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-group" data-field="social_linkedin">
            <label>LinkedIn</label>
            <input type="url" name="social_linkedin" class="user-form-control @error('social_linkedin') is-invalid @enderror" value="{{ old('social_linkedin', $profile->social_linkedin) }}" placeholder="https://linkedin.com/company/yourcompany" maxlength="255">
            <small class="user-field-error">@error('social_linkedin'){{ $message }}@enderror</small>
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

          @php
            $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            $dayLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
            $savedHours = old('business_hours', $profile->business_hours ?? []);
          @endphp
          <div class="user-form-group" data-field="business_hours">
            <label>Business Hours</label>
            <div class="business-hours-grid">
              @foreach($days as $i => $day)
                @php
                  $dayData = $savedHours[$day] ?? ['is_open' => true, 'open' => '10:00 AM', 'close' => '07:00 PM'];
                  $isOpen = (bool) ($dayData['is_open'] ?? true);
                @endphp
                <div class="bh-row" data-day="{{ $day }}">
                  <label class="bh-toggle">
                    <input type="hidden" name="business_hours[{{ $day }}][is_open]" value="0">
                    <input type="checkbox" name="business_hours[{{ $day }}][is_open]" value="1" {{ $isOpen ? 'checked' : '' }}>
                    <span class="bh-day-label">{{ $dayLabels[$i] }}</span>
                  </label>
                  <div class="bh-times">
                    <select name="business_hours[{{ $day }}][open]" class="user-form-control bh-select" {{ !$isOpen ? 'disabled' : '' }}>
                      @foreach(getTimeOptions() as $time)
                        <option value="{{ $time }}" @selected(($dayData['open'] ?? '10:00 AM') === $time)>{{ $time }}</option>
                      @endforeach
                    </select>
                    <span class="bh-separator">to</span>
                    <select name="business_hours[{{ $day }}][close]" class="user-form-control bh-select" {{ !$isOpen ? 'disabled' : '' }}>
                      @foreach(getTimeOptions() as $time)
                        <option value="{{ $time }}" @selected(($dayData['close'] ?? '07:00 PM') === $time)>{{ $time }}</option>
                      @endforeach
                    </select>
                  </div>
                  <span class="bh-closed-label" style="{{ $isOpen ? 'display:none' : '' }}">Closed</span>
                </div>
              @endforeach
            </div>
          </div>

          <button type="submit" class="user-btn user-btn-primary">Save Profile</button>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
@php
  $oldSubIds = old('sub_category_id', $user->subCategoryIds());
  if (! is_array($oldSubIds)) {
      $oldSubIds = array_values(array_filter(array_map('trim', explode(',', (string) $oldSubIds))));
  }
@endphp
<script>
  window.PROFILE_SUBCATEGORIES_URL = @json(url('/register/sub-categories'));
  window.PROFILE_OLD = @json(['sub_category_id' => array_values($oldSubIds)]);
  window.PROFILE_SUBCATEGORIES = @json($subCategories->map(fn ($s) => ['id' => $s->id, 'name' => $s->name])->values());
</script>
<script src="{{ asset('front/assets/js/profile.js') }}"></script>
@endpush
