@extends('front.layouts.app', ['footerPartial' => 'front.partials.footer-mini'])

@section('title', 'Register — Just Goom LLP')
@section('meta_description', 'Create your free Just Goom account — list your business, receive buyer inquiries, and grow your B2B presence.')
@section('body_attrs', 'class="auth-page-body auth-unified-page auth-register-page" data-page="register"')

@push('styles')
<style>
  .field-error { display: block; margin-top: 4px; font-size: 12px; color: #c0392b; min-height:2px; }
  .form-input.is-invalid { border-color: #c0392b; }
  .form-input-readonly { background: #f5f7fa; color: #5a6473; cursor: not-allowed; }
  .form-hint-slug { display: block; margin-top: 4px; font-size: 11px; color: #6b7280; }
  .auth-alert-error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
  .auth-alert-error p { margin: 0 0 4px; }
  .auth-alert-error p:last-child { margin-bottom: 0; }
  .auth-register-page .auth-page-unified { padding: 16px 0 12px; }
  .auth-register-page .auth-unified-card.auth-unified-wide { padding: 20px 22px 18px; }
  .auth-register-page .auth-form-header-center { margin-bottom: 12px; }
  .auth-register-page .auth-form-header-center h1 { font-size: 20px; }
  .auth-register-page .auth-form-compact .auth-form-row { gap: 10px; align-items: start; }
  .auth-register-page .auth-form-compact .form-group { margin-bottom: 0; display: flex; flex-direction: column; }
  .auth-register-page .auth-form-compact .form-group label { margin-bottom: 4px; font-size: 12px; min-height: 18px; }
  .auth-register-page .auth-form-compact .form-input,
  .auth-register-page .auth-form-compact select.form-input {
    padding: 7px 10px;
    font-size: 13px;
    height: 38px;
    box-sizing: border-box;
  }
  .auth-register-page .phone-prefix { padding: 7px 8px; font-size: 13px; }
  .auth-register-page .auth-terms { margin-bottom: 12px; }
  .auth-register-page .auth-switch { margin-top: 12px; }
</style>
@endpush

@section('content')
  <section class="auth-page auth-page-unified">
    <div class="container">
      <div class="auth-unified-card auth-unified-wide">

        <div class="auth-form-header auth-form-header-center">
          <h1>Create Your Account</h1>
        </div>

        @if($errors->any())
          <div class="auth-alert auth-alert-error">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <form class="auth-form auth-form-compact" method="POST" action="{{ route('front.register.submit') }}" id="registerForm" novalidate>
          @csrf
          <div class="auth-form-row">
            <div class="form-group" data-field="company_name">
              <label for="regCompany">Company Name <span class="req">*</span></label>
              <input type="text" id="regCompany" name="company_name" class="form-input @error('company_name') is-invalid @enderror" placeholder="Your company name" value="{{ old('company_name') }}" maxlength="200" minlength="4" autocomplete="organization">
              <span class="field-error">@error('company_name'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="company_slug">
              <label for="regSlug">Company Slug <span class="req">*</span></label>
              <input type="text" id="regSlug" name="company_slug" class="form-input form-input-readonly @error('company_slug') is-invalid @enderror" value="{{ old('company_slug') }}" placeholder="Auto-generated from company name" readonly tabindex="-1" aria-readonly="true">
              <span class="field-error" id="regSlugError">@error('company_slug'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="category_id">
              <label for="regCategory">Category <span class="req">*</span></label>
              <select id="regCategory" name="category_id" class="form-input @error('category_id') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              <span class="field-error">@error('category_id'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="sub_category_id">
              <label for="regSubCategory">Sub Category <span class="req">*</span></label>
              <select id="regSubCategory" name="sub_category_id" class="form-input @error('sub_category_id') is-invalid @enderror">
                <option value="">Select sub category</option>
              </select>
              <span class="field-error">@error('sub_category_id'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="fname">
              <label for="regFname">First Name <span class="req">*</span></label>
              <input type="text" id="regFname" name="fname" class="form-input @error('fname') is-invalid @enderror" placeholder="First name" value="{{ old('fname') }}" maxlength="100" autocomplete="given-name">
              <span class="field-error">@error('fname'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="lname">
              <label for="regLname">Last Name <span class="req">*</span></label>
              <input type="text" id="regLname" name="lname" class="form-input @error('lname') is-invalid @enderror" placeholder="Last name" value="{{ old('lname') }}" maxlength="100" autocomplete="family-name">
              <span class="field-error">@error('lname'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="mobile">
              <label for="regPhone">Mobile Number <span class="req">*</span></label>
              <div class="phone-input">
                <span class="phone-prefix">+91</span>
                <input type="tel" id="regPhone" name="mobile" class="form-input @error('mobile') is-invalid @enderror" placeholder="10 digit mobile number" value="{{ old('mobile') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" autocomplete="tel">
              </div>
              <span class="field-error">@error('mobile'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="email">
              <label for="regEmail">Email Address <span class="req">*</span></label>
              <input type="email" id="regEmail" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" maxlength="191" autocomplete="email">
              <span class="field-error">@error('email'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="password">
              <label for="regPassword">Password <span class="req">*</span></label>
              <input type="password" id="regPassword" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="Create password" minlength="6" maxlength="255" autocomplete="new-password">
              <span class="field-error">@error('password'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="password_confirmation">
              <label for="regConfirm">Confirm Password <span class="req">*</span></label>
              <input type="password" id="regConfirm" name="password_confirmation" class="form-input @error('password_confirmation') is-invalid @enderror" placeholder="Confirm password" minlength="6" maxlength="255" autocomplete="new-password">
              <span class="field-error">@error('password_confirmation'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="form-group" data-field="referral_code">
            <label for="regReferral">Referral Code</label>
            <input type="text" id="regReferral" name="referral_code" class="form-input @error('referral_code') is-invalid @enderror" placeholder="Enter referral code (optional)" value="{{ old('referral_code') }}">
            <span class="field-error">@error('referral_code'){{ $message }}@enderror</span>
          </div>
          <div class="form-group" data-field="terms">
            <label class="auth-terms">
              <input type="checkbox" name="terms" value="1" @checked(old('terms'))>
              I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
            </label>
            <span class="field-error"></span>
          </div>
          <button type="submit" class="btn btn-accent btn-block btn-lg">Create Free Account</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="{{ route('front.login') }}">Login</a></p>

      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  window.REGISTER_SUBCATEGORIES_URL = '/register/sub-categories';
  window.REGISTER_CHECK_SLUG_URL = '/register/check-slug';
  window.REGISTER_OLD = @json($registerOld);
</script>
<script src="{{ asset('front/assets/js/register.js') }}"></script>
@endpush
