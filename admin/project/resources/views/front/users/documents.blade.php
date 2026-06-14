@extends('front.layouts.user')

@section('title', 'My Document — Just Goom')
@section('page_title', 'My Document')
@section('body_attrs', 'class="user-panel-body" data-page="documents" data-title="My Document"')

@section('content')
<div class="user-content">
      <div class="user-toolbar"><span></span><a href="{{ route('front.users.document-form') }}" class="user-btn user-btn-primary">+ Upload Document</a></div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Document Name</th><th>Type</th><th>Uploaded</th><th>Action</th></tr></thead>
          <tbody>
            <tr><td>GST Registration Certificate</td><td>PDF</td><td>Jan 15, 2026</td><td><a href="document-form.html?id=1" class="user-table-action">Edit</a> · <a href="delete.html?module=document&id=1&return=documents.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>BIS Hallmark License</td><td>PDF</td><td>Jan 15, 2026</td><td><a href="document-form.html?id=2" class="user-table-action">Edit</a> · <a href="delete.html?module=document&id=2&return=documents.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>Business Registration</td><td>PDF</td><td>Dec 10, 2025</td><td><a href="document-form.html?id=3" class="user-table-action">Edit</a> · <a href="delete.html?module=document&id=3&return=documents.html" class="user-table-action-muted">Delete</a></td></tr>
            <tr><td>Product Catalogue 2026</td><td>PDF</td><td>May 1, 2026</td><td><a href="document-form.html?id=4" class="user-table-action">Edit</a> · <a href="delete.html?module=document&id=4&return=documents.html" class="user-table-action-muted">Delete</a></td></tr>
          </tbody>
        </table>
      </div>
    </div>
@endsection
