@php
  $frontRoutes = [
    'allProfiles' => route('front.all-profiles'),
    'profileBase' => url('/'),
    'categoryBase' => url('/category'),
  ];
@endphp
<script>
  window.FRONT_ROUTES = @json($frontRoutes);
  window.FRONT_ASSETS = @json(asset('front/assets/images'));
  @isset($categorySectors)
  window.CATEGORY_SECTORS = @json($categorySectors);
  @endisset
  @isset($companyProfiles)
  window.COMPANY_PROFILES = @json($companyProfiles);
  @endisset
</script>
