@extends('front.layouts.user')

@section('title', 'Subscription — Just Goom')
@section('page_title', 'Subscription')
@section('body_attrs', 'class="user-panel-body" data-page="subscription" data-title="Subscription"')

@section('content')
@php
  $planMeta = [
    'Free' => ['icon' => '🆓', 'features' => ['15 days trial access', 'Basic account registration', 'Company profile (1)', 'Limited content features']],
    'Gold' => ['icon' => '🥇', 'features' => ['Full company profile', 'Services, team, documents & videos', 'Enhanced business visibility', 'Plan-based content limits']],
    'Platinum' => ['icon' => '💎', 'features' => ['Unlimited adds for all details', 'Full company profile', 'Services, team, documents & videos', 'Maximum platform visibility']],
  ];
  $currentName = $currentPlan?->name ?? 'Free';
@endphp
<div class="user-content">
      <div class="user-plan-banner">
        <span style="font-size:40px">{{ $planMeta[$currentName]['icon'] ?? '💳' }}</span>
        <div style="flex:1">
          <h2>{{ $currentPlan?->name ?? 'Free' }} Plan</h2>
          <p>
            @if(($currentPlan?->rate ?? 0) > 0)
              ₹{{ number_format((float) $currentPlan->rate, 0) }}
              @if($currentPlan?->duration_days)
                / {{ $currentPlan->duration_days }} days
              @endif
              @if($currentUserPlan?->next_purchase_date)
                · Renews on {{ $currentUserPlan->next_purchase_date->format('M j, Y') }}
              @endif
            @else
              Free
              @if($currentPlan?->duration_days)
                / {{ $currentPlan->duration_days }} days trial
              @endif
            @endif
          </p>
        </div>
        <a href="#plan-cards" class="user-btn user-btn-default">Manage Plan</a>
      </div>

      <div class="user-plan-row" id="plan-cards">
        @forelse($plans as $plan)
          @php
            $meta = $planMeta[$plan->name] ?? ['icon' => '📦', 'features' => ['Plan features available after activation']];
            $isCurrent = (int) $plan->id === (int) ($currentPlan?->id);
          @endphp
          <div class="user-plan-card{{ $isCurrent ? ' featured' : '' }}">
            <span style="font-size:28px">{{ $meta['icon'] }}</span>
            <h3>{{ $plan->name }} Plan</h3>
            <div class="price">
              @if((float) $plan->rate > 0)
                ₹{{ number_format((float) $plan->rate, 0) }}
                <small style="font-size:13px;font-weight:400;color:#888">/{{ $plan->duration_days }} days</small>
              @else
                Free
                <small style="font-size:13px;font-weight:400;color:#888">/{{ $plan->duration_days }} days</small>
              @endif
            </div>
            <ul>
              @foreach($meta['features'] as $feature)
                <li>✓ {{ $feature }}</li>
              @endforeach
              @if($plan->max_video_count)
                <li>✓ Up to {{ $plan->max_video_count }} videos</li>
              @endif
            </ul>
            @if($isCurrent)
              <button type="button" class="user-btn user-btn-primary" style="width:100%" disabled>Current Plan</button>
            @else
              <form method="POST" action="{{ route('front.users.subscription.subscribe', $plan) }}">
                @csrf
                <button type="submit" class="user-btn user-btn-default" style="width:100%" onclick="return confirm('Switch to {{ $plan->name }} plan?');">
                  {{ (float) $plan->rate > (float) ($currentPlan?->rate ?? 0) ? 'Upgrade' : 'Switch' }} to {{ $plan->name }}
                </button>
              </form>
            @endif
          </div>
        @empty
          <p class="user-text-muted">No subscription plans are available yet.</p>
        @endforelse
      </div>

      <div class="user-panel">
        <div class="user-panel-head">Feature Usage ({{ $currentName }})</div>
        <div class="user-panel-body" style="padding:0">
          <table class="user-table" style="border:none">
            <thead><tr><th>Feature</th><th>Used</th><th>Limit</th><th>Status</th></tr></thead>
            <tbody>
              <tr>
                <td>Company Profile</td>
                <td>1</td>
                <td>1</td>
                <td><span class="user-badge user-badge-success">Active</span></td>
              </tr>
              <tr>
                <td>Services</td>
                <td>{{ $usage['services'] }}</td>
                <td>{{ $currentName === 'Platinum' ? 'Unlimited' : ($currentName === 'Gold' ? '15' : '—') }}</td>
                <td><span class="user-badge user-badge-success">{{ $currentName === 'Free' ? 'Limited' : 'Active' }}</span></td>
              </tr>
              <tr>
                <td>Team Members</td>
                <td>{{ $usage['team'] }}</td>
                <td>{{ $currentName === 'Platinum' ? 'Unlimited' : ($currentName === 'Gold' ? '15' : '—') }}</td>
                <td><span class="user-badge user-badge-success">{{ $currentName === 'Free' ? 'Limited' : 'Active' }}</span></td>
              </tr>
              <tr>
                <td>Documents</td>
                <td>{{ $usage['documents'] }}</td>
                <td>{{ $currentName === 'Platinum' ? 'Unlimited' : ($currentName === 'Gold' ? '15' : '—') }}</td>
                <td><span class="user-badge user-badge-success">{{ $currentName === 'Free' ? 'Limited' : 'Active' }}</span></td>
              </tr>
              <tr>
                <td>Videos</td>
                <td>{{ $usage['videos'] }}</td>
                <td>{{ $currentPlan?->max_video_count ?: ($currentName === 'Free' ? '0' : '—') }}</td>
                <td><span class="user-badge user-badge-success">{{ ($currentPlan?->max_video_count ?? 0) > 0 ? 'Active' : 'Locked' }}</span></td>
              </tr>
              <tr>
                <td>Projects</td>
                <td>{{ $usage['projects'] }}</td>
                <td>{{ $currentPlan?->max_project_count ?: '—' }}</td>
                <td><span class="user-badge user-badge-success">Active</span></td>
              </tr>
              <tr>
                <td>Articles</td>
                <td>{{ $usage['articles'] }}</td>
                <td>{{ $currentPlan?->max_article_count ?: '—' }}</td>
                <td><span class="user-badge user-badge-success">Active</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="user-panel" style="margin-top:20px">
        <div class="user-panel-head">Plan Comparison</div>
        <div class="user-panel-body" style="padding:0">
          <table class="user-table" style="border:none">
            <thead>
              <tr>
                <th>Feature</th>
                @foreach($plans as $plan)
                  <th>{{ $plan->name }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Duration</td>
                @foreach($plans as $plan)
                  <td>{{ $plan->duration_days }} days</td>
                @endforeach
              </tr>
              <tr>
                <td>Price</td>
                @foreach($plans as $plan)
                  <td>{{ (float) $plan->rate > 0 ? '₹'.number_format((float) $plan->rate, 0) : 'Free' }}</td>
                @endforeach
              </tr>
              <tr>
                <td>Video Limit</td>
                @foreach($plans as $plan)
                  <td>{{ $plan->max_video_count ?: '—' }}</td>
                @endforeach
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection
