@extends('front.layouts.app', ['footerPartial' => 'front.partials.footer-mini'])

@section('title', 'Login — Just Goom LLP')
@section('meta_description', 'Sign in to your Just Goom account — manage business listings, inquiries, and your B2B dashboard.')
@section('body_attrs', 'class="auth-page-body auth-unified-page auth-login-page" data-page="login"')

@section('content')
  <section class="auth-page auth-page-unified">
    <div class="container">
      <div class="auth-unified-card auth-unified-wide">

        <div class="auth-form-header auth-form-header-center">
          <h1>Login</h1>
        </div>

        @if($errors->any())
          <div class="auth-alert auth-alert-error">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <form class="auth-form auth-form-compact" method="POST" action="{{ route('front.login.submit') }}">
          @csrf
          <div class="form-group">
            <label for="loginEmail">Email Address</label>
            <input type="email" id="loginEmail" name="email" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required>
          </div>
          <div class="form-group">
            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="password" class="form-input" placeholder="Enter your password" required>
          </div>
          <div class="auth-options">
            <label><input type="checkbox" name="remember" value="1"> Remember me</label>
            <a href="#">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="{{ route('front.register') }}">Register</a></p>

      </div>
    </div>
  </section>
@endsection
