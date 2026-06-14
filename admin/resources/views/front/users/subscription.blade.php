@extends('front.layouts.user')

@section('title', 'Subscription — Just Goom')
@section('page_title', 'Subscription')
@section('body_attrs', 'class="user-panel-body" data-page="subscription" data-title="Subscription"')

@section('content')
<div class="user-content">
      <div class="user-plan-banner">
        <span style="font-size:40px">💎</span>
        <div style="flex:1">
          <h2>Platinum Plan</h2>
          <p>₹4,800 / 12 months · Renews on June 7, 2027</p>
        </div>
        <button type="button" class="user-btn user-btn-default">Manage Billing</button>
      </div>
      <div class="user-plan-row">
        <div class="user-plan-card">
          <span style="font-size:28px">🆓</span>
          <h3>Free Plan</h3>
          <div class="price">Free<small style="font-size:13px;font-weight:400;color:#888">/15 days</small></div>
          <ul>
            <li>✓ 15 days trial access</li>
            <li>✓ Basic account registration</li>
            <li>✓ Company profile (1)</li>
            <li>✕ Services, team &amp; documents</li>
            <li>✕ Promotional listings</li>
          </ul>
          <button type="button" class="user-btn user-btn-default" style="width:100%" disabled>Below current</button>
        </div>
        <div class="user-plan-card">
          <span style="font-size:28px">🥇</span>
          <h3>Gold Plan</h3>
          <div class="price">₹3,000<small style="font-size:13px;font-weight:400;color:#888">/6 months</small></div>
          <ul>
            <li>✓ Full company profile</li>
            <li>✓ All details — 15 times each</li>
            <li>✓ Services, team, documents &amp; videos</li>
            <li>✓ Enhanced business visibility</li>
            <li>✕ Unlimited content adds</li>
          </ul>
          <button type="button" class="user-btn user-btn-default" style="width:100%">Downgrade</button>
        </div>
        <div class="user-plan-card featured">
          <span style="font-size:28px">💎</span>
          <h3>Platinum Plan</h3>
          <div class="price">₹4,800<small style="font-size:13px;font-weight:400;color:#888">/12 months</small></div>
          <p class="user-plan-discount"><s>₹6,000</s> · 20% discount</p>
          <ul>
            <li>✓ Unlimited adds for all details</li>
            <li>✓ Full company profile</li>
            <li>✓ Services, team, documents &amp; videos</li>
            <li>✓ Maximum platform visibility</li>
            <li>✓ All Gold Plan benefits</li>
          </ul>
          <button type="button" class="user-btn user-btn-primary" style="width:100%" disabled>Current Plan</button>
        </div>
      </div>
      <div class="user-panel">
        <div class="user-panel-head">Feature Usage (Platinum — Unlimited)</div>
        <div class="user-panel-body" style="padding:0">
          <table class="user-table" style="border:none">
            <thead><tr><th>Feature</th><th>Used</th><th>Limit</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td>Company Profile</td><td>1</td><td>1</td><td><span class="user-badge user-badge-success">Active</span></td></tr>
              <tr><td>Services</td><td>4</td><td>Unlimited</td><td><span class="user-badge user-badge-success">Unlimited</span></td></tr>
              <tr><td>Team Members</td><td>3</td><td>Unlimited</td><td><span class="user-badge user-badge-success">Unlimited</span></td></tr>
              <tr><td>Documents</td><td>2</td><td>Unlimited</td><td><span class="user-badge user-badge-success">Unlimited</span></td></tr>
              <tr><td>Videos</td><td>1</td><td>Unlimited</td><td><span class="user-badge user-badge-success">Unlimited</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="user-panel" style="margin-top:20px">
        <div class="user-panel-head">Plan Comparison</div>
        <div class="user-panel-body" style="padding:0">
          <table class="user-table" style="border:none">
            <thead>
              <tr><th>Feature</th><th>Free</th><th>Gold</th><th>Platinum</th></tr>
            </thead>
            <tbody>
              <tr><td>Duration</td><td>15 days</td><td>6 months</td><td>12 months</td></tr>
              <tr><td>Price</td><td>Free</td><td>₹3,000</td><td>₹4,800 (20% off)</td></tr>
              <tr><td>Company Profile</td><td>1</td><td>1</td><td>1</td></tr>
              <tr><td>Add Details Limit</td><td>—</td><td>15 times each</td><td>Unlimited</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection
