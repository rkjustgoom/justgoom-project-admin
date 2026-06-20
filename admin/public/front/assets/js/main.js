/* JustGoom Front — Main JS */
document.addEventListener('DOMContentLoaded', async () => {
  if (typeof loadIncludes === 'function') {
    await loadIncludes();
  }
  initActiveNav();
  initMobileNav();
  initTabs();
  initProfileTabs();
  initTeamFilter();
  initGallery();
  initHealthCalculator();
  initContactForm();
  initAuthRegister();
  initAuthForms();
  initFlashToast();
});

function initActiveNav() {
  var page = document.body.getAttribute('data-page');
  if (!page) return;

  document.querySelectorAll('[data-nav="' + page + '"]').forEach(function(link) {
    link.classList.add('active');
  });
}

function initMobileNav() {
  const toggle = document.querySelector('.mobile-toggle');
  const overlay = document.querySelector('.mobile-nav');
  const closeBtn = document.querySelector('.mobile-nav-close');

  if (!toggle || !overlay) return;

  toggle.addEventListener('click', () => overlay.classList.add('open'));
  closeBtn?.addEventListener('click', () => overlay.classList.remove('open'));
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
}

function initTabs() {
  const tabNavs = document.querySelectorAll('.tab-nav');

  tabNavs.forEach(nav => {
    const buttons = nav.querySelectorAll('button');
    const container = nav.closest('.detail-tabs');
    if (!container) return;

    const panes = container.querySelectorAll('.tab-pane');

    buttons.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        panes[i]?.classList.add('active');
      });
    });
  });
}

function initProfileTabs() {
  const tabNav = document.getElementById('profileTabs');
  if (!tabNav) return;

  const buttons = tabNav.querySelectorAll('button[data-tab]');
  const panes = document.querySelectorAll('.profile-tab-pane');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;
      buttons.forEach(b => b.classList.remove('active'));
      panes.forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.querySelector(`.profile-tab-pane[data-pane="${tab}"]`)?.classList.add('active');
    });
  });
}

function initTeamFilter() {
  const filter = document.querySelector('.team-time-filter');
  if (!filter) return;

  filter.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      filter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
}

function initGallery() {
  const mainImg = document.querySelector('.gallery-main img');
  const thumbs = document.querySelectorAll('.gallery-thumb');

  if (!mainImg || !thumbs.length) return;

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      const src = thumb.querySelector('img')?.src;
      if (src) mainImg.src = src;
      thumbs.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });
}

function initHealthCalculator() {
  if (document.querySelector('.calc-hub-layout')) return;
  const form = document.getElementById('bmiForm');
  if (!form) return;

  const heightInput = document.getElementById('height');
  const weightInput = document.getElementById('weight');
  const bmiValue = document.getElementById('bmiValue');
  const bmiCategory = document.getElementById('bmiCategory');
  const bmiMarker = document.getElementById('bmiMarker');
  const bmiTips = document.getElementById('bmiTips');
  const unitBtns = document.querySelectorAll('.unit-toggle button');
  const genderOptions = document.querySelectorAll('.gender-option');
  const heightLabel = document.getElementById('heightLabel');
  const weightLabel = document.getElementById('weightLabel');

  let unit = 'metric';

  unitBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      unitBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      unit = btn.dataset.unit;
      heightLabel.textContent = unit === 'metric' ? 'Height (cm)' : 'Height (inches)';
      weightLabel.textContent = unit === 'metric' ? 'Weight (kg)' : 'Weight (lbs)';
      heightInput.placeholder = unit === 'metric' ? 'e.g. 170' : 'e.g. 67';
      weightInput.placeholder = unit === 'metric' ? 'e.g. 70' : 'e.g. 154';
      heightInput.value = '';
      weightInput.value = '';
      resetResult();
    });
  });

  genderOptions.forEach(opt => {
    opt.addEventListener('click', () => {
      genderOptions.forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    calculateBMI();
  });

  function calculateBMI() {
    let height = parseFloat(heightInput.value);
    let weight = parseFloat(weightInput.value);

    if (!height || !weight || height <= 0 || weight <= 0) {
      alert('Please enter valid height and weight values.');
      return;
    }

    if (unit === 'imperial') {
      height = height * 2.54;
      weight = weight * 0.453592;
    }

    const heightM = height / 100;
    const bmi = weight / (heightM * heightM);
    const rounded = bmi.toFixed(1);

    bmiValue.textContent = rounded;

    let category, tip, colorClass;
    if (bmi < 18.5) {
      category = 'Underweight';
      colorClass = 'underweight';
      tip = 'Your BMI is below the healthy range. Consider consulting a nutritionist for a balanced diet plan.';
    } else if (bmi < 25) {
      category = 'Normal Weight';
      colorClass = 'normal';
      tip = 'Great! Your BMI is in the healthy range. Maintain a balanced diet and regular exercise.';
    } else if (bmi < 30) {
      category = 'Overweight';
      colorClass = 'overweight';
      tip = 'Your BMI is above the healthy range. Regular exercise and a healthy diet can help.';
    } else {
      category = 'Obese';
      colorClass = 'obese';
      tip = 'Your BMI indicates obesity. We recommend consulting a healthcare professional for guidance.';
    }

    bmiCategory.textContent = category;
    bmiCategory.className = 'bmi-category ' + colorClass;
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>' + tip + '</p>';

    const markerPos = Math.min(Math.max((bmi / 40) * 100, 2), 98);
    bmiMarker.style.left = markerPos + '%';
  }

  function resetResult() {
    bmiValue.textContent = '--';
    bmiCategory.textContent = 'Enter details';
    bmiCategory.className = 'bmi-category';
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>Fill in your details and click Calculate to see your BMI result.</p>';
    bmiMarker.style.left = '0%';
  }
}

function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Thank you! Your message has been sent. We will contact you soon.');
    form.reset();
  });
}

function initAuthRegister() {
  initRegisterSlug();
  initRegisterCategoriesFromServer();
}

function initRegisterCategoriesFromServer() {
  var categorySelect = document.getElementById('regCategory');
  var subCategorySelect = document.getElementById('regSubCategory');
  if (!categorySelect || !subCategorySelect) return;

  function fillSubCategoriesFromData(categoryId, selectedSubId, subs) {
    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
    subCategorySelect.disabled = !categoryId;

    if (!categoryId || !subs || !subs.length) return;

    subs.forEach(function(sub) {
      var option = document.createElement('option');
      option.value = sub.id;
      option.textContent = sub.name;
      if (selectedSubId && String(selectedSubId) === String(sub.id)) {
        option.selected = true;
      }
      subCategorySelect.appendChild(option);
    });

    subCategorySelect.disabled = false;
  }

  function fillSubCategoriesFromEmbedded(categoryId, selectedSubId) {
    if (!window.REGISTER_CATEGORIES) return false;

    var category = window.REGISTER_CATEGORIES.find(function(item) {
      return String(item.id) === String(categoryId);
    });

    if (!category) return false;

    fillSubCategoriesFromData(categoryId, selectedSubId, category.subs || []);
    return true;
  }

  function loadSubCategories(categoryId, selectedSubId) {
    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
    subCategorySelect.disabled = true;

    if (!categoryId) return;

    if (window.REGISTER_SUBCATEGORIES_URL) {
      fetch(window.REGISTER_SUBCATEGORIES_URL + '/' + categoryId, {
        headers: { 'Accept': 'application/json' }
      })
        .then(function(res) {
          if (!res.ok) throw new Error('Failed to load sub categories');
          return res.json();
        })
        .then(function(subs) {
          fillSubCategoriesFromData(categoryId, selectedSubId, subs);
        })
        .catch(function(err) {
          console.error(err);
          fillSubCategoriesFromEmbedded(categoryId, selectedSubId);
        });
      return;
    }

    fillSubCategoriesFromEmbedded(categoryId, selectedSubId);
  }

  categorySelect.addEventListener('change', function() {
    loadSubCategories(categorySelect.value, null);
  });

  if (categorySelect.value) {
    var oldSubId = window.REGISTER_OLD ? window.REGISTER_OLD.sub_category_id : null;
    loadSubCategories(categorySelect.value, oldSubId);
  }
}

function makeSlug(value) {
  return value
    .toString()
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
}

function initRegisterSlug() {
  if (window.REGISTER_CHECK_SLUG_URL) return;

  var companyInput = document.getElementById('regCompany');
  var slugInput = document.getElementById('regSlug');
  if (!companyInput || !slugInput) return;

  var slugManuallyEdited = slugInput.value.length > 0 &&
    slugInput.value !== makeSlug(companyInput.value);

  function syncSlugFromCompany() {
    if (!slugManuallyEdited) {
      slugInput.value = makeSlug(companyInput.value);
    }
  }

  syncSlugFromCompany();

  companyInput.addEventListener('input', syncSlugFromCompany);

  slugInput.addEventListener('input', function() {
    slugManuallyEdited = true;
    slugInput.value = makeSlug(slugInput.value);
  });

  slugInput.addEventListener('blur', function() {
    if (!slugInput.value.trim()) {
      slugManuallyEdited = false;
      syncSlugFromCompany();
    }
  });
}

function initRegisterCategories() {
  var categorySelect = document.getElementById('regCategory');
  var subCategorySelect = document.getElementById('regSubCategory');
  if (!categorySelect || !subCategorySelect || typeof CATEGORY_SECTORS === 'undefined') return;

  CATEGORY_SECTORS.forEach(function(sector) {
    var option = document.createElement('option');
    option.value = sector.slug;
    option.textContent = sector.name;
    categorySelect.appendChild(option);
  });

  categorySelect.addEventListener('change', function() {
    var slug = categorySelect.value;
    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
    subCategorySelect.disabled = !slug;

    if (!slug) return;

    var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === slug; });
    if (!sector) return;

    sector.subs.forEach(function(sub) {
      var option = document.createElement('option');
      option.value = sub.slug;
      option.textContent = sub.name;
      subCategorySelect.appendChild(option);
    });
  });
}

function initAuthForms() {
  var loginForm = document.getElementById('loginForm');
  if (loginForm && loginForm.getAttribute('method') !== 'POST') {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Login successful! (Demo — connect to backend API)');
    });
  }

  var registerForm = document.getElementById('registerForm');
  if (registerForm && registerForm.getAttribute('method') !== 'POST') {
    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var pass = document.getElementById('regPassword');
      var confirm = document.getElementById('regConfirm');
      if (pass && confirm && pass.value !== confirm.value) {
        alert('Passwords do not match.');
        return;
      }
      alert('Account created successfully! (Demo — connect to backend API)');
      registerForm.reset();
      var slugPreview = document.getElementById('regSlugPreview');
      if (slugPreview) slugPreview.textContent = 'your-slug';
      var subCategorySelect = document.getElementById('regSubCategory');
      if (subCategorySelect) {
        subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
        subCategorySelect.disabled = true;
      }
    });
  }
}

function showToast(message, type) {
  if (!message) return;

  var container = document.getElementById('jgToastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'jgToastContainer';
    container.className = 'jg-toast-container';
    container.setAttribute('aria-live', 'polite');
    container.setAttribute('aria-atomic', 'true');
    document.body.appendChild(container);
  }

  var toast = document.createElement('div');
  toast.className = 'jg-toast jg-toast-' + (type || 'success');
  toast.innerHTML = '<span class="jg-toast-message"></span><button type="button" class="jg-toast-close" aria-label="Close">&times;</button>';
  toast.querySelector('.jg-toast-message').textContent = message;

  var closeBtn = toast.querySelector('.jg-toast-close');
  var removeToast = function() {
    toast.classList.remove('show');
    setTimeout(function() { toast.remove(); }, 300);
  };

  closeBtn.addEventListener('click', removeToast);
  container.appendChild(toast);
  requestAnimationFrame(function() { toast.classList.add('show'); });
  setTimeout(removeToast, 6000);
}

function initFlashToast() {
  if (!window.JG_FLASH) return;

  if (window.JG_FLASH.success) {
    showToast(window.JG_FLASH.success, 'success');
  } else if (window.JG_FLASH.error) {
    showToast(window.JG_FLASH.error, 'error');
  } else if (window.JG_FLASH.info) {
    showToast(window.JG_FLASH.info, 'info');
  }

  window.JG_FLASH = null;
}
