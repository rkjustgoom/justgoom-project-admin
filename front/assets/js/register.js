/* JustGoom — Register form client validation */
(function () {
  var rules = {
    company_name: {
      test: function (v) { return v.trim().length >= 4; },
      message: 'Company name must be at least 4 characters.'
    },
    category_id: {
      test: function (v) { return v !== ''; },
      message: 'Please select a category.'
    },
    sub_category_id: {
      test: function (v) { return v !== ''; },
      message: 'Please select a sub category.'
    },
    fname: {
      test: function (v) { return v.trim().length >= 2; },
      message: 'First name must be at least 2 characters.'
    },
    lname: {
      test: function (v) { return v.trim().length >= 2; },
      message: 'Last name must be at least 2 characters.'
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
    var input = group.querySelector('input, select');
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
    var input = group.querySelector('input, select');
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
    var subCategorySelect = document.getElementById('regSubCategory');
    var phoneInput = document.getElementById('regPhone');
    var termsCheckbox = form.querySelector('.auth-terms input[type="checkbox"]');
    var slugErrorEl = document.getElementById('regSlugError');

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

    function getFieldValue(fieldName) {
      switch (fieldName) {
        case 'company_name': return companyInput ? companyInput.value : '';
        case 'company_slug': return slugInput ? slugInput.value : '';
        case 'category_id': return categorySelect ? categorySelect.value : '';
        case 'sub_category_id': return subCategorySelect ? subCategorySelect.value : '';
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
        syncSlugFromCompany();
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
          if (showError) setFieldError('company_slug', 'This company slug is already taken. Please choose another.');
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
            setFieldError(fieldName, rule.message);
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
      var slug = slugInput.value;

      if (slug === '') {
        slugAvailable = null;
        return Promise.resolve(false);
      }

      if (!/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(slug)) {
        slugAvailable = false;
        setFieldError('company_slug', 'Slug may only contain lowercase letters, numbers, and hyphens.');
        return Promise.resolve(false);
      }

      var requestId = ++slugCheckRequestId;
      return fetch(window.REGISTER_CHECK_SLUG_URL + '?slug=' + encodeURIComponent(slug), {
        headers: { Accept: 'application/json' }
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (requestId !== slugCheckRequestId) return slugAvailable !== false;
          slugAvailable = !!data.available;
          if (data.available) {
            if (slugErrorEl) slugErrorEl.dataset.serverError = '';
            clearFieldError('company_slug');
          } else {
            setFieldError('company_slug', data.message || 'This company slug is already taken. Please choose another.');
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
      companyInput.addEventListener('input', function () {
        syncSlugFromCompany();
        validateField('company_name', false);
        if (slugErrorEl) slugErrorEl.dataset.serverError = '';
        if (slugInput.value.length > 0) {
          scheduleSlugCheck();
        } else {
          slugAvailable = null;
        }
      });
      companyInput.addEventListener('blur', function () {
        validateField('company_name', true);
        validateField('company_slug', true);
      });
    }

    ['category_id', 'sub_category_id', 'fname', 'lname', 'email', 'password', 'password_confirmation'].forEach(function (field) {
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
      input.addEventListener('input', function () {
        validateField(field, false);
      });
    });

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
        var firstInvalid = form.querySelector('.form-input.is-invalid, select.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      syncSlugFromCompany();
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
