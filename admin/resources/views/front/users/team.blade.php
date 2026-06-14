@extends('front.layouts.user')

@section('title', 'My Team — Just Goom')
@section('page_title', 'My Team')
@section('body_attrs', 'class="user-panel-body" data-page="team" data-title="My Team"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">👥</span><div class="user-stat-info"><h3>3</h3><span>Team Members</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>3</h3><span>Active</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">⭐</span><div class="user-stat-info"><h3>1</h3><span>Primary Contact</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Manage staff shown on your public profile</span>
        <a href="{{ route('front.users.team-add') }}" class="user-btn user-btn-primary">+ Add Team Member</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Name</th><th>Role</th><th>Department</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <tr>
              <td><strong>Patel Ramesh</strong> <span class="user-badge user-badge-success" style="margin-left:4px;font-size:10px">Primary</span></td>
              <td>Owner</td><td>Management</td><td>ramesh@shreegold.com</td><td>+91 98765 43210</td>
              <td><span class="user-badge user-badge-success">Active</span></td>
              <td><a href="team-edit.html?id=1" class="user-table-action">Edit</a> · <a href="delete.html?module=team&id=1&return=team.html" class="user-table-action-muted">Delete</a></td>
            </tr>
            <tr>
              <td><strong>Shah Priya</strong></td>
              <td>Sales Manager</td><td>Sales</td><td>priya@shreegold.com</td><td>+91 98765 43211</td>
              <td><span class="user-badge user-badge-success">Active</span></td>
              <td><a href="team-edit.html?id=2" class="user-table-action">Edit</a> · <a href="delete.html?module=team&id=2&return=team.html" class="user-table-action-muted">Delete</a></td>
            </tr>
            <tr>
              <td><strong>Mehta Vikram</strong></td>
              <td>Wholesale Lead</td><td>Wholesale</td><td>vikram@shreegold.com</td><td>+91 98765 43212</td>
              <td><span class="user-badge user-badge-success">Active</span></td>
              <td><a href="team-edit.html?id=3" class="user-table-action">Edit</a> · <a href="delete.html?module=team&id=3&return=team.html" class="user-table-action-muted">Delete</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
@endsection
