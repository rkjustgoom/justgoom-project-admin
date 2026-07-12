/* Company profiles — home + all profiles pages */
const PROFILES_PER_PAGE = 12;
const HOME_PROFILE_LIMIT = PROFILES_PER_PAGE;
const ALL_PROFILES_LIMIT = 100;

const COMPANY_BANNER_FILES = [
  'movie-1.jpg', 'movie-2.jpg', 'movie-3.jpg',
  'movie-4.jpg', 'movie-5.jpg', 'movie-6.jpg',
  'blog-1.jpg', 'blog-2.jpg', 'blog-3.jpg',
  'cat-real-estate.jpg', 'cat-health.jpg', 'cat-entertainment.jpg',
  'cat-education.jpg', 'cat-shopping.jpg', 'cat-automobile.jpg',
  'cat-food.jpg', 'cat-business.jpg', 'hero-banner.jpg'
];

function getCompanyBanner(index) {
  var base = window.FRONT_ASSETS || 'front/assets/images';
  return base + '/' + COMPANY_BANNER_FILES[index % COMPANY_BANNER_FILES.length];
}

const COMPANY_LOGO_COLORS = ['#1A428A', '#F7941D', '#16a34a', '#7c3aed', '#0891b2', '#dc2626', '#ca8a04', '#059669'];

const STATIC_COMPANY_PROFILES = [
  { name: 'Fixmycars', category: 'Travel Agency', projects: 225, tasks: 197, city: 'Ahmedabad', verified: true, featured: true, addedDaysAgo: 2 },
  { name: 'Plasma Graphics', category: 'Packaging Service', projects: 164, tasks: 182, city: 'Mumbai', verified: true, featured: true, addedDaysAgo: 5 },
  { name: 'Devada Jewelry', category: 'Metal-crafts', projects: 352, tasks: 376, city: 'Madurai', verified: true, featured: true, addedDaysAgo: 1 },
  { name: 'Pradhan Mantri Jan Aushdhi', category: 'Medical Store', projects: 241, tasks: 204, city: 'Delhi', verified: true, featured: false, addedDaysAgo: 12 },
  { name: 'Sunrise Realty', category: 'Real Estate', projects: 89, tasks: 54, city: 'Ahmedabad', verified: true, addedDaysAgo: 45 },
  { name: 'HealthFirst Clinic', category: 'Health & Wellness', projects: 62, tasks: 38, city: 'Chennai', verified: false, addedDaysAgo: 8 },
  { name: 'CineMax Entertainment', category: 'Entertainment', projects: 115, tasks: 72, city: 'Mumbai', verified: true, addedDaysAgo: 22 },
  { name: 'BrightMinds Academy', category: 'Education', projects: 48, tasks: 31, city: 'Bangalore', verified: false, addedDaysAgo: 60 },
  { name: 'ShopEasy Mart', category: 'Shopping', projects: 203, tasks: 145, city: 'Delhi', verified: true, addedDaysAgo: 3 },
  { name: 'AutoCare Motors', category: 'Automobile', projects: 77, tasks: 49, city: 'Ahmedabad', verified: false, addedDaysAgo: 90 },
  { name: 'SpiceRoute Dining', category: 'Food & Dining', projects: 56, tasks: 33, city: 'Madurai', verified: true, addedDaysAgo: 18 },
  { name: 'BizGrow Consultants', category: 'Business Services', projects: 34, tasks: 22, city: 'Bangalore', verified: false, addedDaysAgo: 120 },
  { name: 'GreenBuild Contractors', category: 'Home Construction', projects: 91, tasks: 60, city: 'Delhi', verified: true, addedDaysAgo: 35 },
  { name: 'PixelSoft IT', category: 'Software Development', projects: 128, tasks: 95, city: 'Bangalore', verified: true, addedDaysAgo: 6 },
  { name: 'LegalEase Associates', category: 'Legal Services', projects: 29, tasks: 18, city: 'Chennai', verified: false, addedDaysAgo: 200 },
  { name: 'FitLife Gym', category: 'Fitness Center', projects: 44, tasks: 27, city: 'Mumbai', verified: false, addedDaysAgo: 14 },
  { name: 'TravelMate Tours', category: 'Tours & Travels', projects: 67, tasks: 41, city: 'Ahmedabad', verified: true, addedDaysAgo: 28 },
  { name: 'PrintPro Solutions', category: 'Printing Services', projects: 52, tasks: 36, city: 'Delhi', verified: false, addedDaysAgo: 75 },
  { name: 'AgroFresh Farms', category: 'Agriculture', projects: 38, tasks: 24, city: 'Madurai', verified: false, addedDaysAgo: 150 },
  { name: 'StyleHub Fashion', category: 'Fashion Retail', projects: 73, tasks: 48, city: 'Mumbai', verified: true, addedDaysAgo: 9 },
  { name: 'SafeGuard Security', category: 'Security Services', projects: 61, tasks: 39, city: 'Chennai', verified: false, addedDaysAgo: 40 },
  { name: 'CleanHome Services', category: 'Home Services', projects: 85, tasks: 58, city: 'Bangalore', verified: true, addedDaysAgo: 11 },
  { name: 'TechRepair Hub', category: 'Electronics Repair', projects: 47, tasks: 30, city: 'Ahmedabad', verified: false, addedDaysAgo: 55 },
  { name: 'PetCare Paradise', category: 'Pet Services', projects: 33, tasks: 19, city: 'Delhi', verified: false, addedDaysAgo: 100 },
  { name: 'EventCraft Planners', category: 'Event Management', projects: 58, tasks: 35, city: 'Mumbai', verified: true, addedDaysAgo: 7 },
  { name: 'MediPlus Pharmacy', category: 'Pharmacy', projects: 42, tasks: 26, city: 'Madurai', verified: false, addedDaysAgo: 65 },
  { name: 'UrbanDecor Interiors', category: 'Interior Design', projects: 69, tasks: 44, city: 'Bangalore', verified: true, addedDaysAgo: 20 },
  { name: 'SwiftLogistics', category: 'Logistics', projects: 96, tasks: 63, city: 'Chennai', verified: true, addedDaysAgo: 4 },
  { name: 'PhotoFrame Studio', category: 'Photography', projects: 51, tasks: 32, city: 'Ahmedabad', verified: false, addedDaysAgo: 180 },
  { name: 'EcoSolar Energy', category: 'Renewable Energy', projects: 74, tasks: 47, city: 'Delhi', verified: true, addedDaysAgo: 25 },
  { name: 'CraftBox Handmade', category: 'Handicrafts', projects: 39, tasks: 21, city: 'Madurai', verified: false, addedDaysAgo: 320 }
];

var COMPANY_PROFILES = (typeof window.COMPANY_PROFILES !== 'undefined')
  ? window.COMPANY_PROFILES
  : STATIC_COMPANY_PROFILES;

var HOME_PROFILES = COMPANY_PROFILES.slice(0, HOME_PROFILE_LIMIT);
var ALL_PROFILES_LIST = COMPANY_PROFILES.slice(0, Math.min(COMPANY_PROFILES.length, ALL_PROFILES_LIMIT));

function getCompanyInitials(name) {
  return name.split(/\s+/).map(w => w[0]).join('').slice(0, 2).toUpperCase();
}

function formatAddedTime(days) {
  days = Math.max(0, Math.floor(Number(days) || 0));
  if (days === 0) return 'Added today';
  if (days === 1) return 'Added yesterday';
  if (days <= 7) return 'Added ' + days + ' days ago';
  if (days <= 30) return 'Added ' + Math.floor(days / 7) + ' week' + (Math.floor(days / 7) > 1 ? 's' : '') + ' ago';
  if (days <= 365) return 'Added ' + Math.floor(days / 30) + ' month' + (Math.floor(days / 30) > 1 ? 's' : '') + ' ago';
  return 'Added over a year ago';
}

function getActiveChips(group) {
  return Array.from(group.querySelectorAll('.chip.active')).map(c =>
    (c.dataset.value || c.textContent).trim().toLowerCase()
  );
}

function initFilterChipsLocal(container) {
  container.querySelectorAll('.filter-chips').forEach(group => {
    const isSingle = group.dataset.single === 'true';
    group.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', () => {
        if (isSingle) {
          group.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
          chip.classList.add('active');
        } else {
          chip.classList.toggle('active');
        }
      });
    });
  });
}

function renderCompanyCard(company, index) {
  const banner = getCompanyBanner(index);
  const logoColor = COMPANY_LOGO_COLORS[index % COMPANY_LOGO_COLORS.length];
  const initials = getCompanyInitials(company.name);
  const starClass = company.featured ? ' is-starred' : '';
  const addedDays = company.addedDaysAgo ?? 0;
  const logoHtml = company.logoUrl
    ? `<div class="company-logo company-logo-image"><img src="${company.logoUrl}" alt="${company.name}"></div>`
    : `<div class="company-logo" style="background:${logoColor}">${initials}</div>`;

  return `
    <article class="company-card"
      data-name="${company.name.toLowerCase()}"
      data-category="${company.category.toLowerCase()}"
      data-category-slug="${(company.categorySlug || '').toLowerCase()}"
      data-subcategory="${(company.subCategory || '').toLowerCase()}"
      data-subcategory-slug="${(company.subCategorySlug || '').toLowerCase()}"
      data-locality="${company.city.toLowerCase()}"
      data-country="${(company.country || '').toLowerCase()}"
      data-verified="${company.verified ? 'yes' : 'no'}"
      data-added-days="${addedDays}">
      <div class="company-card-banner">
        <img src="${banner}" alt="${company.name}">
        <button type="button" class="company-star${starClass}" aria-label="Favorite">★</button>
        <div class="company-menu-wrap">
          <button type="button" class="company-menu-btn" aria-label="More options">⋯</button>
          <div class="company-menu-dropdown">
            <button type="button">Download Profile PDF</button>
            <button type="button">Share Profile Link</button>
          </div>
        </div>
      </div>
      <div class="company-card-body">
        ${logoHtml}
        <h3 class="company-name">${company.name}</h3>
        <p class="company-category">${company.category}</p>
        <p class="company-location">📍 ${company.city}</p>
        ${company.verified ? '<span class="company-verified-badge">✓ Verified</span>' : ''}
        <span class="company-added-time">🕐 ${formatAddedTime(addedDays)}</span>
        <div class="company-stats">
          <div class="company-stat">
            <span class="company-stat-num">${company.projects}</span>
            <span class="company-stat-label">Projects</span>
          </div>
          <div class="company-stat-divider"></div>
          <div class="company-stat">
            <span class="company-stat-num">${company.tasks}</span>
            <span class="company-stat-label">Tasks</span>
          </div>
        </div>
        <a href="${company.profileUrl || '#'}" class="btn btn-view-profile">View Profile</a>
      </div>
    </article>
  `;
}

function bindCardInteractions(grid) {
  grid.querySelectorAll('.company-menu-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const dropdown = btn.nextElementSibling;
      grid.querySelectorAll('.company-menu-dropdown.open').forEach(d => {
        if (d !== dropdown) d.classList.remove('open');
      });
      dropdown?.classList.toggle('open');
    });
  });

  grid.querySelectorAll('.company-star').forEach(star => {
    star.addEventListener('click', (e) => {
      e.stopPropagation();
      star.classList.toggle('is-starred');
    });
  });

  document.addEventListener('click', () => {
    grid.querySelectorAll('.company-menu-dropdown.open').forEach(d => d.classList.remove('open'));
  });
}

function bindViewToggle(grid, viewGridBtn, viewListBtn) {
  viewGridBtn?.addEventListener('click', () => {
    grid.classList.remove('list-view');
    viewGridBtn.classList.add('active');
    viewListBtn?.classList.remove('active');
  });

  viewListBtn?.addEventListener('click', () => {
    grid.classList.add('list-view');
    viewListBtn.classList.add('active');
    viewGridBtn?.classList.remove('active');
  });
}

function initHomeCompanyProfiles() {
  const grid = document.getElementById('homeCompanyGrid');
  const searchInput = document.getElementById('homeCompanySearch');
  const countEl = document.getElementById('homeCompanyCount');
  const viewGridBtn = document.getElementById('homeViewGrid');
  const viewListBtn = document.getElementById('homeViewList');

  if (!grid) return;

  const subtitleEl = document.getElementById('homeCompanySubtitle');
  if (subtitleEl) {
    subtitleEl.textContent = `Browse verified business profiles — showing ${HOME_PROFILES.length} featured listings`;
  }

  grid.innerHTML = HOME_PROFILES.map(renderCompanyCard).join('');

  function updateCount() {
    const visible = grid.querySelectorAll('.company-card:not([style*="display: none"])').length;
    const total = COMPANY_PROFILES.length;
    if (countEl) countEl.textContent = `Showing ${visible} of ${total} company profiles`;
  }

  searchInput?.addEventListener('input', () => {
    const q = searchInput.value.trim().toLowerCase();
    grid.querySelectorAll('.company-card').forEach(card => {
      const name = card.dataset.name || '';
      const cat = card.dataset.category || '';
      const loc = card.dataset.locality || '';
      const match = !q || name.includes(q) || cat.includes(q) || loc.includes(q);
      card.style.display = match ? '' : 'none';
    });
    updateCount();
  });

  bindViewToggle(grid, viewGridBtn, viewListBtn);
  bindCardInteractions(grid);
  updateCount();

  document.getElementById('homeFiltersBtn')?.addEventListener('click', function() {
    var q = searchInput?.value.trim() || '';
    var base = (window.FRONT_ROUTES && window.FRONT_ROUTES.allProfiles) || '/all-profiles';
    var params = new URLSearchParams();
    if (q) params.set('q', q);
    window.location.href = params.toString() ? base + '?' + params.toString() : base;
  });
}

function matchesTimeRange(days, range) {
  if (range === 'all') return true;
  if (range === 'today') return days === 0;
  if (range === 'week') return days <= 7;
  if (range === 'month') return days <= 30;
  if (range === 'year') return days <= 365;
  return true;
}

const CATEGORY_MENU_KEYWORDS = {
  restaurants: ['food', 'dining', 'restaurant'],
  hotels: ['travel', 'tours', 'hotel'],
  'beauty spa': ['fashion', 'style', 'beauty'],
  'home decor': ['interior', 'decor', 'home'],
  wedding: ['event', 'wedding'],
  education: ['education', 'academy'],
  rent: ['real estate', 'automobile', 'rent'],
  hospitals: ['health', 'pharmacy', 'clinic', 'medical'],
  contractors: ['construction', 'contractor'],
  pet: ['pet'],
  pg: ['home services'],
  estate: ['real estate'],
  dentists: ['health', 'pharmacy', 'medical'],
  gym: ['fitness', 'gym'],
  loans: ['business', 'consult'],
  event: ['event'],
  driving: ['automobile', 'travel', 'motor'],
  packers: ['logistics', 'moving'],
  courier: ['logistics', 'courier'],
  jewellery: ['jewelry', 'metal', 'crafts'],
  supermarkets: ['shopping', 'mart', 'retail'],
  'coffee shops': ['food', 'dining', 'cafe'],
  salons: ['beauty', 'fashion', 'spa'],
  tailors: ['fashion', 'retail'],
  laundry: ['home services', 'clean'],
  'ac repair': ['electronics', 'repair', 'tech'],
  plumbers: ['home construction', 'home services', 'contractor'],
  electricians: ['electronics', 'repair', 'construction'],
  painters: ['home construction', 'interior', 'contractor'],
  astrologers: ['business', 'consult'],
  insurance: ['business', 'legal', 'consult'],
  'chartered accountants': ['business', 'legal', 'consult'],
  'book stores': ['education', 'shopping'],
  'mobile shops': ['electronics', 'automobile', 'shopping'],
  'computer repair': ['electronics', 'software', 'tech'],
  furniture: ['interior', 'home', 'decor'],
  'hardware stores': ['home construction', 'shopping'],
  catering: ['food', 'dining', 'event']
};

function resolveCategoryFilter(slug) {
  if (!slug || slug === 'all') return null;

  slug = slug.toLowerCase();

  if (typeof CATEGORY_SECTORS !== 'undefined') {
    var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === slug; });
    if (sector) {
      return { type: 'sector', sectorSlug: slug, label: sector.name };
    }

    for (var i = 0; i < CATEGORY_SECTORS.length; i++) {
      var s = CATEGORY_SECTORS[i];
      for (var j = 0; j < s.subs.length; j++) {
        if (s.subs[j].slug === slug) {
          return {
            type: 'sub',
            sectorSlug: s.slug,
            subSlug: slug,
            label: s.subs[j].name
          };
        }
      }
    }
  }

  return { type: 'unknown', sectorSlug: slug, label: slug.replace(/-/g, ' ') };
}

function getCategoryFilterLabel(slug) {
  var resolved = resolveCategoryFilter(slug);
  if (!resolved) return '';
  if (resolved.label) {
    return resolved.label.replace(/\b\w/g, function(c) { return c.toUpperCase(); });
  }
  return slug.replace(/-/g, ' ').replace(/\b\w/g, function(c) { return c.toUpperCase(); });
}

function populateSubCategoryOptions(sectorSlug, selectedSub) {
  var group = document.getElementById('subCategoryFilterGroup');
  var select = document.getElementById('profileSubCategorySelect');
  if (!group || !select) return;

  if (!sectorSlug || sectorSlug === 'all') {
    group.hidden = true;
    select.innerHTML = '<option value="all">All Subcategories</option>';
    select.value = 'all';
    return;
  }

  if (typeof CATEGORY_SECTORS === 'undefined') {
    group.hidden = true;
    return;
  }

  var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === sectorSlug; });
  if (!sector || !sector.subs.length) {
    group.hidden = true;
    select.innerHTML = '<option value="all">All Subcategories</option>';
    select.value = 'all';
    return;
  }

  group.hidden = false;
  select.innerHTML = '<option value="all">All Subcategories</option>' +
    sector.subs.map(function(sub) {
      return '<option value="' + sub.slug + '">' + sub.name + '</option>';
    }).join('');
  select.value = (selectedSub && selectedSub !== 'all') ? selectedSub : 'all';
}

function syncProfilesFilterUrl(sectorSlug, subSlug) {
  var params = new URLSearchParams(window.location.search);
  params.delete('category');
  params.delete('subcategory');

  if (subSlug && subSlug !== 'all') {
    params.set('category', subSlug);
  } else if (sectorSlug && sectorSlug !== 'all') {
    params.set('category', sectorSlug);
  }

  var query = params.toString();
  window.history.replaceState({}, '', query ? window.location.pathname + '?' + query : window.location.pathname);
}

function getProfilesFilterSubtitle(sectorSlug, subSlug) {
  if (subSlug && subSlug !== 'all') {
    return 'Showing profiles for ' + getCategoryFilterLabel(subSlug);
  }
  if (sectorSlug && sectorSlug !== 'all') {
    return 'Showing profiles for ' + getCategoryFilterLabel(sectorSlug);
  }
  return 'Browse verified business profiles across all categories';
}

function cardMatchesSectorAndSub(card, sectorSlug, subSlug) {
  var cardCatSlug = (card.dataset.categorySlug || '').toLowerCase();
  var cardSubSlug = (card.dataset.subcategorySlug || '').toLowerCase();

  if (subSlug && subSlug !== 'all') {
    return cardSubSlug === subSlug.toLowerCase();
  }
  if (sectorSlug && sectorSlug !== 'all') {
    return cardCatSlug === sectorSlug.toLowerCase();
  }
  return true;
}

function setActiveCategoryChip(sidebar, slug) {
  var categoryGroup = sidebar?.querySelector('[data-filter-group="category"]');
  if (!categoryGroup) return;

  var chipSlug = 'all';
  if (slug && slug !== 'all') {
    var resolved = resolveCategoryFilter(slug);
    chipSlug = resolved ? resolved.sectorSlug : slug;
  }

  var matched = false;
  categoryGroup.querySelectorAll('.chip').forEach(function(chip) {
    var isActive = chip.dataset.value === chipSlug;
    if (isActive) matched = true;
    chip.classList.toggle('active', isActive);
  });

  if (!matched) {
    categoryGroup.querySelectorAll('.chip').forEach(function(chip) {
      chip.classList.toggle('active', chip.dataset.value === 'all');
    });
  }
}

function initCategoryCompanyProfiles() {
  const grid = document.getElementById('categoryCompanyGrid');
  const searchInput = document.getElementById('categoryCompanySearch');
  const countEl = document.getElementById('categoryCompanyCount');
  const viewGridBtn = document.getElementById('categoryViewGrid');
  const viewListBtn = document.getElementById('categoryViewList');
  const filtersPanel = document.getElementById('categoryFiltersPanel');
  const filtersToggle = document.getElementById('categoryFiltersToggle');
  const sidebar = document.getElementById('profileFilters');
  const timeSelect = document.getElementById('categoryTimeFilterSelect');
  const subCategorySelect = document.getElementById('profileSubCategorySelect');
  const subtitleEl = document.getElementById('profilesSubtitle');
  const emptyEl = document.getElementById('profilesEmpty');
  const paginationEl = document.getElementById('profilesPagination');
  const pageInfoEl = document.getElementById('profilesPageInfo');

  if (!grid) return;

  grid.innerHTML = ALL_PROFILES_LIST.map(renderCompanyCard).join('');
  bindCardInteractions(grid);
  bindViewToggle(grid, viewGridBtn, viewListBtn);

  if (sidebar) initFilterChipsLocal(sidebar);

  let timeRange = 'all';
  let currentPage = 1;
  const urlParams = new URLSearchParams(window.location.search);
  const urlCategory = urlParams.get('category');
  const urlSubCategory = urlParams.get('subcategory');
  const urlSearch = (urlParams.get('q') || '').trim();
  const urlCity = (urlParams.get('city') || '').trim();
  const urlCountry = (urlParams.get('country') || '').trim();
  let activeSectorFilter = 'all';
  let activeSubCategoryFilter = 'all';
  let urlCityFilter = '';
  let urlCountryFilter = urlCountry ? urlCountry.toLowerCase() : '';

  if (urlCategory) {
    var resolvedUrlCategory = resolveCategoryFilter(urlCategory);
    if (resolvedUrlCategory && resolvedUrlCategory.type === 'sub') {
      activeSectorFilter = resolvedUrlCategory.sectorSlug;
      activeSubCategoryFilter = resolvedUrlCategory.subSlug;
    } else {
      activeSectorFilter = urlCategory;
    }
  }
  if (urlSubCategory) {
    activeSubCategoryFilter = urlSubCategory;
    if (activeSectorFilter === 'all') {
      var resolvedSub = resolveCategoryFilter(urlSubCategory);
      if (resolvedSub && resolvedSub.sectorSlug) {
        activeSectorFilter = resolvedSub.sectorSlug;
      }
    }
  }

  if (urlSearch && searchInput) {
    searchInput.value = urlSearch;
  }

  if (urlCity && sidebar) {
    const localitySelect = sidebar.querySelector('[data-filter="locality"]');
    const cityLower = urlCity.toLowerCase();
    const matchedOption = localitySelect
      ? Array.from(localitySelect.options).find(function(option) {
          return option.value.toLowerCase() === cityLower
            || option.textContent.trim().toLowerCase() === cityLower;
        })
      : null;

    if (matchedOption) {
      localitySelect.value = matchedOption.value;
    } else {
      urlCityFilter = cityLower;
    }
  }

  if (sidebar && activeSectorFilter !== 'all') {
    setActiveCategoryChip(sidebar, activeSectorFilter);
    populateSubCategoryOptions(activeSectorFilter, activeSubCategoryFilter);
  }

  if (subtitleEl) {
    if (urlCategory || urlSubCategory || activeSectorFilter !== 'all') {
      subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
    } else if (urlSearch || urlCity || urlCountry) {
      const parts = [];
      if (urlSearch) parts.push('"' + urlSearch + '"');
      if (urlCountry) parts.push('in ' + urlCountry);
      else if (urlCity) parts.push('in ' + urlCity);
      subtitleEl.textContent = 'Search results for ' + parts.join(' ');
    }
  }

  function cardMatchesFilters(card) {
    const q = searchInput?.value.trim().toLowerCase() || '';
    const locality = sidebar?.querySelector('[data-filter="locality"]')?.value.toLowerCase() || '';
    const categoryGroup = sidebar?.querySelector('[data-filter-group="category"]');
    const verifiedGroup = sidebar?.querySelector('[data-filter-group="verified"]');

    let sectorSlug = activeSectorFilter;
    let subSlug = activeSubCategoryFilter;

    if (categoryGroup) {
      const chips = getActiveChips(categoryGroup).filter(function(v) { return v !== 'all'; });
      if (chips.length) {
        sectorSlug = chips[0];
      }
    }

    if (subCategorySelect) {
      var subGroup = document.getElementById('subCategoryFilterGroup');
      if (subGroup && !subGroup.hidden) {
        const selectedSub = (subCategorySelect.value || 'all').toLowerCase();
        subSlug = selectedSub;
      }
    }

    const verified = verifiedGroup ? getActiveChips(verifiedGroup).filter(function(v) { return v !== 'all'; }) : [];
    const name = card.dataset.name || '';
    const cat = card.dataset.category || '';
    const sub = card.dataset.subcategory || '';
    const loc = card.dataset.locality || '';
    const country = card.dataset.country || '';
    const ver = card.dataset.verified || '';
    const days = parseInt(card.dataset.addedDays || '0', 10);

    if (q && !name.includes(q) && !cat.includes(q) && !sub.includes(q) && !loc.includes(q) && !country.includes(q)) return false;
    if (!cardMatchesSectorAndSub(card, sectorSlug, subSlug)) return false;
    if (locality && locality !== 'all cities' && loc !== locality && !loc.includes(locality)) return false;
    if (urlCityFilter && !loc.includes(urlCityFilter)) return false;
    if (urlCountryFilter && country !== urlCountryFilter && !country.includes(urlCountryFilter)) return false;
    if (verified.indexOf('verified only') !== -1 && ver !== 'yes') return false;
    if (!matchesTimeRange(days, timeRange)) return false;
    return true;
  }

  function getMatchedCards() {
    return Array.from(grid.querySelectorAll('.company-card')).filter(cardMatchesFilters);
  }

  function renderPagination(totalPages) {
    if (!paginationEl) return;
    if (totalPages <= 1) {
      paginationEl.innerHTML = '';
      paginationEl.hidden = true;
      return;
    }
    paginationEl.hidden = false;

    var html = '';
    html += '<button type="button" class="pagination-btn" data-page="prev" aria-label="Previous page"' +
      (currentPage === 1 ? ' disabled' : '') + '>‹</button>';

    for (var p = 1; p <= totalPages; p++) {
      if (totalPages > 7 && p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
        if (p === 3 || p === totalPages - 2) html += '<span class="pagination-ellipsis">…</span>';
        continue;
      }
      html += '<button type="button" class="pagination-btn' + (p === currentPage ? ' active' : '') +
        '" data-page="' + p + '">' + p + '</button>';
    }

    html += '<button type="button" class="pagination-btn" data-page="next" aria-label="Next page"' +
      (currentPage === totalPages ? ' disabled' : '') + '>›</button>';

    paginationEl.innerHTML = html;

    paginationEl.querySelectorAll('.pagination-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (btn.disabled) return;
        var page = btn.dataset.page;
        if (page === 'prev') currentPage--;
        else if (page === 'next') currentPage++;
        else currentPage = parseInt(page, 10);
        applyFilters(false);
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  function applyFilters(resetPage) {
    if (resetPage !== false) currentPage = 1;

    var matched = getMatchedCards();
    var totalPages = Math.max(1, Math.ceil(matched.length / PROFILES_PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;

    var start = (currentPage - 1) * PROFILES_PER_PAGE;
    var end = start + PROFILES_PER_PAGE;

    grid.querySelectorAll('.company-card').forEach(function(card) {
      card.style.display = 'none';
    });

    matched.slice(start, end).forEach(function(card) {
      card.style.display = '';
    });

    if (countEl) {
      if (matched.length === 0) {
        countEl.textContent = 'No profiles found';
      } else {
        countEl.textContent = 'Showing ' + (start + 1) + '–' + Math.min(end, matched.length) +
          ' of ' + matched.length + ' profiles';
      }
    }

    if (pageInfoEl) {
      pageInfoEl.textContent = matched.length > 0
        ? 'Page ' + currentPage + ' of ' + totalPages + ' · ' + PROFILES_PER_PAGE + ' per page · 3 per row'
        : '';
    }

    if (emptyEl) {
      emptyEl.hidden = matched.length > 0;
      grid.hidden = matched.length === 0;
    }

    renderPagination(totalPages);
  }

  searchInput?.addEventListener('input', applyFilters);

  filtersToggle?.addEventListener('click', () => {
    const sidebarEl = document.querySelector('.profiles-sidebar');
    const overlay = document.getElementById('profilesFilterOverlay');
    sidebarEl?.classList.toggle('mobile-open');
    if (overlay) overlay.hidden = !sidebarEl?.classList.contains('mobile-open');
    if (filtersPanel) {
      const open = filtersPanel.hasAttribute('hidden');
      if (open) filtersPanel.removeAttribute('hidden');
      else filtersPanel.setAttribute('hidden', '');
    }
  });

  document.getElementById('profilesFilterOverlay')?.addEventListener('click', () => {
    document.querySelector('.profiles-sidebar')?.classList.remove('mobile-open');
    document.getElementById('profilesFilterOverlay').hidden = true;
  });

  timeSelect?.addEventListener('change', function() {
    timeRange = timeSelect.value || 'all';
    applyFilters();
  });

  sidebar?.querySelector('.filter-reset')?.addEventListener('click', resetProfileFilters);
  document.querySelector('.profiles-sidebar-reset')?.addEventListener('click', resetProfileFilters);

  function resetProfileFilters() {
    const filterRoot = document.getElementById('profileFilters');
    if (!filterRoot) return;
    filterRoot.querySelectorAll('.filter-select').forEach(s => { s.selectedIndex = 0; });
    filterRoot.querySelectorAll('.filter-chips').forEach(group => {
      group.querySelectorAll('.chip').forEach((c, i) => c.classList.toggle('active', i === 0));
    });
    if (timeSelect) timeSelect.value = 'all';
    timeRange = 'all';
    activeSectorFilter = 'all';
    activeSubCategoryFilter = 'all';
    populateSubCategoryOptions('all', 'all');
    urlCityFilter = '';
    if (searchInput) searchInput.value = '';
    if (subtitleEl) subtitleEl.textContent = 'Browse verified business profiles across all categories';
    if (window.location.search) {
      window.history.replaceState({}, '', window.location.pathname);
    }
    applyFilters();
  }

  sidebar?.querySelector('.btn-apply-filters')?.addEventListener('click', applyFilters);
  sidebar?.querySelector('[data-filter="locality"]')?.addEventListener('change', function() {
    urlCityFilter = '';
    applyFilters();
  });
  sidebar?.querySelectorAll('[data-filter-group="category"] .chip').forEach(chip => {
    chip.addEventListener('click', function() {
      activeSectorFilter = chip.dataset.value || 'all';
      activeSubCategoryFilter = 'all';
      populateSubCategoryOptions(activeSectorFilter, 'all');
      syncProfilesFilterUrl(activeSectorFilter, activeSubCategoryFilter);
      if (subtitleEl) {
        subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
      }
      applyFilters();
    });
  });

  subCategorySelect?.addEventListener('change', function() {
    activeSubCategoryFilter = subCategorySelect.value || 'all';
    if (activeSubCategoryFilter !== 'all' && activeSectorFilter === 'all' && typeof CATEGORY_SECTORS !== 'undefined') {
      var resolved = resolveCategoryFilter(activeSubCategoryFilter);
      if (resolved && resolved.sectorSlug) {
        activeSectorFilter = resolved.sectorSlug;
        setActiveCategoryChip(sidebar, activeSectorFilter);
      }
    }
    syncProfilesFilterUrl(activeSectorFilter, activeSubCategoryFilter);
    if (subtitleEl) {
      subtitleEl.textContent = getProfilesFilterSubtitle(activeSectorFilter, activeSubCategoryFilter);
    }
    applyFilters();
  });

  sidebar?.querySelectorAll('[data-filter-group="verified"] .chip').forEach(chip => {
    chip.addEventListener('click', function() {
      applyFilters();
    });
  });

  applyFilters();
}

document.addEventListener('DOMContentLoaded', () => {
  initHomeCompanyProfiles();
  initCategoryCompanyProfiles();
});
