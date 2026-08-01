/* JustGoom — Register form client validation */
(function () {
  var rules = {
    company_name: {
      test: function (v) {
        var value = v.trim();
        return value.length >= 4 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(value);
      },
      message: function (v) {
        if (v.trim().length < 4) return 'Company name must be at least 4 characters.';
        return 'Company name may only contain letters, numbers, and spaces.';
      }
    },
    category_id: {
      test: function (v) { return v !== ''; },
      message: 'Please select a category.'
    },
    sub_category_id: {
      test: function (v) { return v !== ''; },
      message: 'Please select at least one sub category.'
    },
    fname: {
      test: function (v) {
        var value = v.trim();
        return value.length >= 2 && /^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/.test(value);
      },
      message: function (v) {
        if (v.trim().length < 2) return 'First name must be at least 2 characters.';
        return 'First name may only contain letters and spaces.';
      }
    },
    lname: {
      test: function (v) {
        var value = v.trim();
        return value.length >= 2 && /^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/.test(value);
      },
      message: function (v) {
        if (v.trim().length < 2) return 'Last name must be at least 2 characters.';
        return 'Last name may only contain letters and spaces.';
      }
    },
    mobile: {
      test: function (v) { return /^\d{10}$/.test(v); },
      message: 'Mobile number must be exactly 10 digits.'
    },
    email: {
      test: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); },
      message: 'Enter a valid email address.'
    },
    password: {
      test: function (v) { return v.length >= 6; },
      message: 'Password must be at least 6 characters.'
    },
    password_confirmation: {
      test: function () { return true; },
      message: 'Password confirmation does not match.'
    },
    terms: {
      test: function () { return true; },
      message: 'You must agree to the Terms and Privacy Policy.'
    }
  };

  function makeSlug(value) {
    return value.toString().toLowerCase().trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  }

  function normalizeMobile(value) {
    return value.replace(/\D+/g, '').slice(0, 10);
  }

  function setFieldError(fieldName, message) {
    var group = document.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    var input = group.querySelector('.ms-trigger') || group.querySelector('input, select');
    var errorEl = group.querySelector('.field-error');
    if (input) input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
  }

  function clearFieldError(fieldName) {
    var group = document.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    var input = group.querySelector('.ms-trigger') || group.querySelector('input, select');
    var errorEl = group.querySelector('.field-error');
    if (input) input.classList.remove('is-invalid');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('registerForm');
    if (!form) return;

    var companyInput = document.getElementById('regCompany');
    var slugInput = document.getElementById('regSlug');
    var categorySelect = document.getElementById('regCategory');
    var subCategoryList = document.getElementById('regSubCategory');
    var phoneInput = document.getElementById('regPhone');
    var termsCheckbox = form.querySelector('.auth-terms input[type="checkbox"]');
    var slugErrorEl = document.getElementById('regSlugError');
    var slugHintEl = document.getElementById('regSlugHint');

    document.querySelectorAll('.field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      }
    });

    var slugCheckTimer = null;
    var slugAvailable = null;
    var slugCheckRequestId = 0;

    function syncSlugFromCompany() {
      if (!companyInput || !slugInput) return;
      slugInput.value = makeSlug(companyInput.value);
    }

    function setSlugHint(message) {
      if (!slugHintEl) return;
      slugHintEl.textContent = message || '';
      slugHintEl.style.display = message ? 'block' : 'none';
    }

    function getFieldValue(fieldName) {
      switch (fieldName) {
        case 'company_name': return companyInput ? companyInput.value : '';
        case 'company_slug': return slugInput ? slugInput.value : '';
        case 'category_id': return categorySelect ? categorySelect.value : '';
        case 'sub_category_id':
          return Array.from(document.querySelectorAll('#regSubCategoryInputs input[name="sub_category_id[]"]'))
            .map(function (input) { return input.value; })
            .join(',');
        case 'fname': return document.getElementById('regFname')?.value || '';
        case 'lname': return document.getElementById('regLname')?.value || '';
        case 'mobile': return phoneInput ? phoneInput.value : '';
        case 'email': return document.getElementById('regEmail')?.value.trim() || '';
        case 'password': return document.getElementById('regPassword')?.value || '';
        case 'password_confirmation': return document.getElementById('regConfirm')?.value || '';
        case 'terms': return termsCheckbox && termsCheckbox.checked ? '1' : '';
        default: return '';
      }
    }

    function validateField(fieldName, showError) {
      if (fieldName === 'company_slug') {
        var slug = getFieldValue('company_slug');
        if (slug === '') {
          if (showError) setFieldError('company_slug', 'Company slug is required.');
          return false;
        }
        if (!/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(slug)) {
          if (showError) setFieldError('company_slug', 'Slug may only contain lowercase letters, numbers, and hyphens.');
          return false;
        }
        if (slugAvailable === false) {
          if (showError) setFieldError('company_slug', 'Unable to generate a unique company slug.');
          return false;
        }
        if (!slugErrorEl || !slugErrorEl.dataset.serverError) clearFieldError('company_slug');
        return true;
      }

      if (fieldName === 'password_confirmation') {
        var pass = getFieldValue('password');
        var confirm = getFieldValue('password_confirmation');
        if (confirm !== pass) {
          if (showError) setFieldError('password_confirmation', 'Password confirmation does not match.');
          return false;
        }
        clearFieldError('password_confirmation');
        return true;
      }

      if (fieldName === 'terms') {
        if (!termsCheckbox || !termsCheckbox.checked) {
          if (showError) setFieldError('terms', rules.terms.message);
          return false;
        }
        clearFieldError('terms');
        return true;
      }

      var rule = rules[fieldName];
      if (!rule) return true;

      var value = fieldName === 'mobile' ? normalizeMobile(getFieldValue('mobile')) : getFieldValue(fieldName);
      if (fieldName === 'mobile' && phoneInput) phoneInput.value = value;

      if (!rule.test(value)) {
        if (showError) {
          if (fieldName === 'mobile' && value.length === 0) {
            setFieldError('mobile', 'Mobile number is required.');
          } else {
            var message = typeof rule.message === 'function' ? rule.message(value) : rule.message;
            setFieldError(fieldName, message);
          }
        }
        return false;
      }

      clearFieldError(fieldName);
      return true;
    }

    function scheduleSlugCheck() {
      if (!slugInput || !window.REGISTER_CHECK_SLUG_URL) return;
      clearTimeout(slugCheckTimer);
      slugCheckTimer = setTimeout(function () {
        checkSlugAvailability();
      }, 400);
    }

    function checkSlugAvailability() {
      if (!slugInput) return Promise.resolve(true);

      syncSlugFromCompany();
      var baseSlug = slugInput.value;

      if (baseSlug === '') {
        slugAvailable = null;
        setSlugHint('');
        return Promise.resolve(false);
      }

      if (!/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(baseSlug)) {
        slugAvailable = false;
        setSlugHint('');
        setFieldError('company_slug', 'Slug may only contain lowercase letters, numbers, and hyphens.');
        return Promise.resolve(false);
      }

      var requestId = ++slugCheckRequestId;
      return fetch(window.REGISTER_CHECK_SLUG_URL + '?slug=' + encodeURIComponent(baseSlug), {
        headers: { Accept: 'application/json' }
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (requestId !== slugCheckRequestId) return slugAvailable !== false;

          if (data.slug) {
            slugInput.value = data.slug;
          }

          slugAvailable = !!data.available;

          if (data.available) {
            if (slugErrorEl) slugErrorEl.dataset.serverError = '';
            clearFieldError('company_slug');
            setSlugHint(data.adjusted ? (data.message || 'Slug updated automatically because it was already taken.') : '');
          } else {
            setSlugHint('');
            setFieldError('company_slug', data.message || 'Unable to generate a unique company slug.');
          }

          return slugAvailable;
        })
        .catch(function () {
          slugAvailable = null;
          return true;
        });
    }

    if (companyInput && slugInput) {
      syncSlugFromCompany();
      if (slugInput.value.length > 0) {
        scheduleSlugCheck();
      }
      companyInput.addEventListener('input', function () {
        syncSlugFromCompany();
        validateField('company_name', false);
        if (slugErrorEl) slugErrorEl.dataset.serverError = '';
        setSlugHint('');
        if (slugInput.value.length > 0) {
          scheduleSlugCheck();
        } else {
          slugAvailable = null;
        }
      });
      companyInput.addEventListener('blur', function () {
        validateField('company_name', true);
        checkSlugAvailability().then(function () {
          validateField('company_slug', true);
        });
      });
    }

    ['category_id', 'fname', 'lname', 'email', 'password', 'password_confirmation'].forEach(function (field) {
      var group = document.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, select');
      if (!input) return;
      input.addEventListener('blur', function () {
        validateField(field, true);
        if (field === 'password' || field === 'password_confirmation') {
          validateField('password_confirmation', true);
        }
      });
      input.addEventListener('change', function () {
        validateField(field, false);
      });
      input.addEventListener('input', function () {
        validateField(field, false);
      });
    });

    // ---- Sub-category multiselect ----
    var subWrap = document.getElementById('regSubCategoryWrap');
    var subTrigger = document.getElementById('regSubCategoryTrigger');
    var subText = document.getElementById('regSubCategoryText');
    var subDropdown = document.getElementById('regSubCategoryDropdown');
    var subInputs = document.getElementById('regSubCategoryInputs');
    var subFetchId = 0;

    function setSubPlaceholder(text) {
      if (subText) subText.textContent = text;
    }

    function openSubDropdown() {
      if (!subWrap || subWrap.classList.contains('is-disabled')) return;
      subWrap.classList.add('is-open');
      if (subTrigger) subTrigger.setAttribute('aria-expanded', 'true');
      if (subDropdown) subDropdown.hidden = false;
    }

    function closeSubDropdown() {
      if (!subWrap) return;
      subWrap.classList.remove('is-open');
      if (subTrigger) subTrigger.setAttribute('aria-expanded', 'false');
      if (subDropdown) subDropdown.hidden = true;
    }

    function toggleSubDropdown() {
      if (!subWrap) return;
      if (subWrap.classList.contains('is-open')) closeSubDropdown();
      else openSubDropdown();
    }

    function getSelectedSubOptions() {
      if (!subCategoryList) return [];
      return Array.from(subCategoryList.querySelectorAll('input[type="checkbox"][data-sub-id]'))
        .filter(function (cb) { return cb.checked; });
    }

    function syncSubHiddenInputs() {
      if (!subInputs || !subWrap) return;
      subInputs.innerHTML = '';
      var selected = getSelectedSubOptions();
      selected.forEach(function (cb) {
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'sub_category_id[]';
        hidden.value = cb.getAttribute('data-sub-id');
        subInputs.appendChild(hidden);
      });

      if (selected.length === 0) {
        setSubPlaceholder('None selected');
        subWrap.classList.remove('has-value');
      } else {
        setSubPlaceholder(selected.map(function (cb) { return cb.getAttribute('data-sub-name'); }).join(', '));
        subWrap.classList.add('has-value');
      }

      var allCb = subCategoryList.querySelector('input[data-sub-all]');
      if (allCb) {
        var total = subCategoryList.querySelectorAll('input[data-sub-id]').length;
        allCb.checked = total > 0 && selected.length === total;
      }
    }

    function renderSubOptions(items, preselectedIds) {
      if (!subCategoryList || !subWrap) return;
      subCategoryList.innerHTML = '';
      if (subInputs) subInputs.innerHTML = '';

      if (!items || items.length === 0) {
        var empty = document.createElement('div');
        empty.className = 'ms-empty';
        empty.textContent = 'No sub categories available.';
        subCategoryList.appendChild(empty);
        setSubPlaceholder('None selected');
        subWrap.classList.remove('has-value');
        return;
      }

      var allLabel = document.createElement('label');
      allLabel.className = 'ms-option ms-option-all';
      var allInput = document.createElement('input');
      allInput.type = 'checkbox';
      allInput.setAttribute('data-sub-all', '1');
      var allSpan = document.createElement('span');
      allSpan.textContent = 'Select all';
      allLabel.appendChild(allInput);
      allLabel.appendChild(allSpan);
      subCategoryList.appendChild(allLabel);

      items.forEach(function (item) {
        var label = document.createElement('label');
        label.className = 'ms-option';
        var input = document.createElement('input');
        input.type = 'checkbox';
        input.setAttribute('data-sub-id', String(item.id));
        input.setAttribute('data-sub-name', item.name);
        if (preselectedIds && preselectedIds.indexOf(String(item.id)) !== -1) {
          input.checked = true;
        }
        var span = document.createElement('span');
        span.textContent = item.name;
        label.appendChild(input);
        label.appendChild(span);
        subCategoryList.appendChild(label);
      });

      syncSubHiddenInputs();
    }

    function loadSubCategories(categoryId, preselectedIds) {
      if (!subCategoryList || !subWrap || !window.REGISTER_SUBCATEGORIES_URL) return;

      if (!categoryId) {
        subCategoryList.innerHTML = '';
        if (subInputs) subInputs.innerHTML = '';
        subWrap.classList.add('is-disabled');
        subWrap.classList.remove('has-value');
        setSubPlaceholder('Select a category first');
        closeSubDropdown();
        return;
      }

      subWrap.classList.remove('is-disabled');
      setSubPlaceholder('Loading…');
      var requestId = ++subFetchId;

      fetch(window.REGISTER_SUBCATEGORIES_URL + '/' + encodeURIComponent(categoryId), {
        headers: { Accept: 'application/json' }
      })
        .then(function (res) { return res.json(); })
        .then(function (items) {
          if (requestId !== subFetchId) return;
          renderSubOptions(items, preselectedIds);
        })
        .catch(function () {
          if (requestId !== subFetchId) return;
          renderSubOptions([], null);
        });
    }

    if (subWrap && subTrigger && subCategoryList) {
      subTrigger.addEventListener('click', function (e) {
        e.preventDefault();
        toggleSubDropdown();
      });

      subCategoryList.addEventListener('change', function (e) {
        var target = e.target;
        if (target && target.hasAttribute('data-sub-all')) {
          var checked = target.checked;
          subCategoryList.querySelectorAll('input[data-sub-id]').forEach(function (cb) {
            cb.checked = checked;
          });
        }
        syncSubHiddenInputs();
        validateField('sub_category_id', true);
      });

      document.addEventListener('click', function (e) {
        if (!subWrap.contains(e.target)) closeSubDropdown();
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSubDropdown();
      });
    }

    if (categorySelect) {
      categorySelect.addEventListener('change', function () {
        loadSubCategories(categorySelect.value, null);
      });
    }

    (function initSubCategories() {
      if (!subWrap) return;
      var old = window.REGISTER_OLD || {};
      var initialCategory = (old.category_id != null && old.category_id !== '')
        ? String(old.category_id)
        : (categorySelect ? categorySelect.value : '');
      var preselected = Array.isArray(old.sub_category_id)
        ? old.sub_category_id.map(function (v) { return String(v); })
        : (old.sub_category_id ? [String(old.sub_category_id)] : []);

      if (initialCategory) {
        loadSubCategories(initialCategory, preselected);
      } else {
        subWrap.classList.add('is-disabled');
        setSubPlaceholder('Select a category first');
      }
    })();

    if (phoneInput) {
      phoneInput.addEventListener('input', function () {
        phoneInput.value = normalizeMobile(phoneInput.value);
        validateField('mobile', false);
      });
      phoneInput.addEventListener('blur', function () {
        validateField('mobile', true);
      });
    }

    if (termsCheckbox) {
      termsCheckbox.addEventListener('change', function () {
        validateField('terms', true);
      });
    }

    function validateForm() {
      var fields = [
        'company_name', 'company_slug', 'category_id', 'sub_category_id',
        'fname', 'lname', 'mobile', 'email', 'password', 'password_confirmation', 'terms'
      ];
      var valid = true;

      fields.forEach(function (field) {
        if (!validateField(field, true)) valid = false;
      });

      if (!valid) {
        var firstInvalid = form.querySelector('.form-input.is-invalid, select.is-invalid, .ms-trigger.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (phoneInput) phoneInput.value = normalizeMobile(phoneInput.value);

      if (!validateForm()) return;

      checkSlugAvailability().then(function (available) {
        if (!available) {
          document.querySelector('[data-field="company_slug"]')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
          return;
        }
        if (validateForm()) form.submit();
      });
    });
  });
})();
