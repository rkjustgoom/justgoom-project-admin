/* JustGoom — Profile form: multi sub-categories, logo preview, client validation */
(function () {
  var ALLOWED_LOGO_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  var MAX_LOGO_BYTES = 2 * 1024 * 1024;

  var rules = {
    company_name: {
      test: function (v) {
        var value = v.trim();
        return value.length >= 4 && value.length <= 200 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(value);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Business name is required.';
        if (v.trim().length < 4) return 'Business name must be at least 4 characters.';
        if (v.trim().length > 200) return 'Business name must not exceed 200 characters.';
        return 'Business name may only contain letters, numbers, and spaces.';
      }
    },
    category_id: {
      test: function (v) { return v.length > 0; },
      message: function () { return 'Please select a category.'; }
    },
    sub_category_id: {
      test: function (v) { return v.length > 0; },
      message: function () { return 'Please select at least one sub category.'; }
    },
    tagline: {
      test: function (v) {
        var trimmed = v.trim();
        if (trimmed.length === 0) return true;
        return trimmed.length <= 255 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length > 255) return 'Tagline must not exceed 255 characters.';
        return 'Tagline may only contain letters, numbers, and spaces.';
      }
    },
    business_desc: {
      test: function (v) { return v.trim().length >= 20 && v.trim().length <= 5000; },
      message: function (v) {
        if (v.trim().length === 0) return 'About business is required.';
        if (v.trim().length < 20) return 'About business must be at least 20 characters.';
        return 'About business must not exceed 5000 characters.';
      }
    },
    phone: {
      test: function (v) { return /^\d{10}$/.test(v); },
      message: function (v) {
        if (v.length === 0) return 'Phone number is required.';
        return 'Phone number must be exactly 10 digits.';
      }
    },
    email: {
      test: function (v) { return v.length > 0 && v.length <= 191 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); },
      message: function (v) {
        if (v.length === 0) return 'Email is required.';
        if (v.length > 191) return 'Email must not exceed 191 characters.';
        return 'Enter a valid email address.';
      }
    },
    address: {
      test: function (v) { return v.length <= 500; },
      message: function () { return 'Address must not exceed 500 characters.'; }
    },
    city: {
      test: function (v) {
        var trimmed = v.trim();
        return trimmed.length > 0 && trimmed.length <= 100 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'City is required.';
        if (v.trim().length > 100) return 'City must not exceed 100 characters.';
        return 'City may only contain letters, numbers, and spaces.';
      }
    },
    logo: {
      test: function (file) {
        if (!file) return true;
        if (ALLOWED_LOGO_TYPES.indexOf(file.type) === -1) return false;
        return file.size <= MAX_LOGO_BYTES;
      },
      message: function (file) {
        if (!file) return '';
        if (ALLOWED_LOGO_TYPES.indexOf(file.type) === -1) {
          return 'Logo must be JPG, PNG, WebP, or GIF.';
        }
        return 'Logo must not be larger than 2 MB.';
      }
    }
  };

  function normalizePhone(value) {
    return value.replace(/\D+/g, '').slice(0, 10);
  }

  function normalizeSelectedIds(selectedSubId) {
    if (Array.isArray(selectedSubId)) {
      return selectedSubId.map(String).filter(Boolean);
    }
    if (selectedSubId) {
      return String(selectedSubId).split(',').map(function (id) {
        return id.trim();
      }).filter(Boolean);
    }
    return [];
  }

  function setFieldError(form, fieldName, message) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]):not([readonly]), select, textarea, .ms-trigger').forEach(function (el) {
      el.classList.add('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
  }

  function clearFieldError(form, fieldName) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]):not([readonly]), select, textarea, .ms-trigger').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  function initBusinessHours() {
    var rows = document.querySelectorAll('.bh-row');
    rows.forEach(function (row) {
      var checkbox = row.querySelector('input[type="checkbox"]');
      var selects = row.querySelectorAll('.bh-select');
      var closedLabel = row.querySelector('.bh-closed-label');
      var timesDiv = row.querySelector('.bh-times');

      if (!checkbox) return;

      function toggle() {
        var isOpen = checkbox.checked;
        selects.forEach(function (s) { s.disabled = !isOpen; });
        if (timesDiv) timesDiv.style.display = isOpen ? 'flex' : 'none';
        if (closedLabel) closedLabel.style.display = isOpen ? 'none' : '';
      }

      checkbox.addEventListener('change', toggle);
      toggle();
    });
  }

  function initSubCategoryMultiSelect(form) {
    var categorySelect = document.getElementById('profileCategory');
    var listEl = document.getElementById('profileSubCategory');
    var wrap = document.getElementById('profileSubCategoryWrap');
    var trigger = document.getElementById('profileSubCategoryTrigger');
    var dropdown = document.getElementById('profileSubCategoryDropdown');
    var textEl = document.getElementById('profileSubCategoryText');
    var inputsEl = document.getElementById('profileSubCategoryInputs');
    if (!categorySelect || !listEl || !wrap || !trigger || !dropdown || !textEl) return null;

    var selectedMap = {};

    function closeDropdown() {
      wrap.classList.remove('is-open');
      dropdown.hidden = true;
      trigger.setAttribute('aria-expanded', 'false');
    }

    function openDropdown() {
      if (wrap.classList.contains('is-disabled')) return;
      wrap.classList.add('is-open');
      dropdown.hidden = false;
      trigger.setAttribute('aria-expanded', 'true');
    }

    function toggleDropdown() {
      if (dropdown.hidden) openDropdown();
      else closeDropdown();
    }

    function setDisabled(disabled) {
      wrap.classList.toggle('is-disabled', !!disabled);
      trigger.disabled = !!disabled;
    }

    function syncHiddenInputs() {
      if (!inputsEl) return;
      inputsEl.innerHTML = '';
      Object.keys(selectedMap).forEach(function (id) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sub_category_id[]';
        input.value = id;
        inputsEl.appendChild(input);
      });
    }

    function syncTriggerText() {
      var ids = Object.keys(selectedMap);
      var count = ids.length;
      wrap.classList.toggle('has-value', count > 0);

      if (!categorySelect.value) {
        textEl.textContent = 'Select category first';
      } else if (count === 0) {
        textEl.textContent = 'None selected';
      } else if (count === 1) {
        textEl.textContent = selectedMap[ids[0]];
      } else if (count <= 2) {
        textEl.textContent = ids.map(function (id) { return selectedMap[id]; }).join(', ');
      } else {
        textEl.textContent = count + ' selected';
      }

      syncHiddenInputs();
    }

    function syncSelectAllState() {
      var selectAll = listEl.querySelector('.ms-option-all input');
      if (!selectAll) return;
      var itemChecks = listEl.querySelectorAll('.ms-option-item input');
      var total = itemChecks.length;
      var checked = 0;
      itemChecks.forEach(function (cb) {
        if (cb.checked) checked += 1;
      });
      selectAll.checked = total > 0 && checked === total;
      selectAll.indeterminate = checked > 0 && checked < total;
    }

    function fillSubCategories(subs, selectedSubId) {
      listEl.innerHTML = '';
      selectedMap = {};
      closeDropdown();

      if (!categorySelect.value) {
        setDisabled(true);
        syncTriggerText();
        return;
      }

      setDisabled(false);

      if (!subs || !subs.length) {
        listEl.innerHTML = '<div class="ms-empty">No sub categories found</div>';
        syncTriggerText();
        return;
      }

      var selectedIds = normalizeSelectedIds(selectedSubId);

      var allLabel = document.createElement('label');
      allLabel.className = 'ms-option ms-option-all';
      var allCheckbox = document.createElement('input');
      allCheckbox.type = 'checkbox';
      var allText = document.createElement('span');
      allText.textContent = 'Select all';
      allLabel.appendChild(allCheckbox);
      allLabel.appendChild(allText);
      listEl.appendChild(allLabel);

      allCheckbox.addEventListener('change', function () {
        var checked = allCheckbox.checked;
        listEl.querySelectorAll('.ms-option-item input').forEach(function (cb) {
          cb.checked = checked;
          if (checked) selectedMap[cb.value] = cb.getAttribute('data-name');
          else delete selectedMap[cb.value];
        });
        syncTriggerText();
        syncSelectAllState();
        listEl.dispatchEvent(new Event('change', { bubbles: true }));
      });

      subs.forEach(function (sub) {
        var id = String(sub.id);
        var label = document.createElement('label');
        label.className = 'ms-option ms-option-item';

        var checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = id;
        checkbox.setAttribute('data-name', sub.name);

        var text = document.createElement('span');
        text.textContent = sub.name;

        if (selectedIds.indexOf(id) !== -1) {
          checkbox.checked = true;
          selectedMap[id] = sub.name;
        }

        checkbox.addEventListener('change', function () {
          if (checkbox.checked) selectedMap[id] = sub.name;
          else delete selectedMap[id];
          syncTriggerText();
          syncSelectAllState();
          listEl.dispatchEvent(new Event('change', { bubbles: true }));
        });

        label.appendChild(checkbox);
        label.appendChild(text);
        listEl.appendChild(label);
      });

      syncTriggerText();
      syncSelectAllState();
    }

    function loadSubCategories(categoryId, selectedId) {
      listEl.innerHTML = '';
      selectedMap = {};
      closeDropdown();
      setDisabled(true);
      syncTriggerText();

      if (!categoryId) return;

      textEl.textContent = 'Loading...';

      if (window.PROFILE_SUBCATEGORIES_URL) {
        fetch(window.PROFILE_SUBCATEGORIES_URL + '/' + categoryId, {
          headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (response) { return response.json(); })
          .then(function (data) { fillSubCategories(data, selectedId); })
          .catch(function () { fillSubCategories([], ''); });
        return;
      }

      fillSubCategories(window.PROFILE_SUBCATEGORIES || [], selectedId);
    }

    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      toggleDropdown();
    });

    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) closeDropdown();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeDropdown();
    });

    categorySelect.addEventListener('change', function () {
      loadSubCategories(categorySelect.value, '');
    });

    if (categorySelect.value) {
      var initialSelected = window.PROFILE_OLD ? window.PROFILE_OLD.sub_category_id : [];
      if (window.PROFILE_SUBCATEGORIES && window.PROFILE_SUBCATEGORIES.length) {
        fillSubCategories(window.PROFILE_SUBCATEGORIES, initialSelected);
      } else {
        loadSubCategories(categorySelect.value, initialSelected);
      }
    } else {
      setDisabled(true);
      syncTriggerText();
    }

    return {
      getValue: function () {
        return Object.keys(selectedMap).join(',');
      },
      listEl: listEl
    };
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('profileForm');
    if (!form) return;

    initBusinessHours();

    var categorySelect = document.getElementById('profileCategory');
    var subMulti = initSubCategoryMultiSelect(form);
    var phoneInput = form.querySelector('input[name="phone"]');
    var logoInput = form.querySelector('input[name="logo"]');
    var logoPreview = document.getElementById('profileLogoPreview');

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    function getFieldValue(fieldName) {
      switch (fieldName) {
        case 'company_name': return form.querySelector('input[name="company_name"]')?.value || '';
        case 'category_id': return categorySelect ? categorySelect.value : '';
        case 'sub_category_id': return subMulti ? subMulti.getValue() : '';
        case 'tagline': return form.querySelector('input[name="tagline"]')?.value || '';
        case 'business_desc': return form.querySelector('textarea[name="business_desc"]')?.value || '';
        case 'phone': return phoneInput ? phoneInput.value : '';
        case 'email': return (form.querySelector('input[name="email"]')?.value || '').trim();
        case 'address': return form.querySelector('input[name="address"]')?.value || '';
        case 'city': return form.querySelector('input[name="city"]')?.value || '';
        case 'logo': return logoInput && logoInput.files.length ? logoInput.files[0] : null;
        default: return '';
      }
    }

    function validateField(fieldName, showError) {
      var rule = rules[fieldName];
      if (!rule) return true;

      var value = fieldName === 'phone'
        ? normalizePhone(getFieldValue('phone'))
        : getFieldValue(fieldName);

      if (fieldName === 'phone' && phoneInput) phoneInput.value = value;

      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = [
        'company_name', 'category_id', 'sub_category_id', 'tagline',
        'business_desc', 'phone', 'email', 'address', 'city', 'logo'
      ];
      var valid = true;

      fields.forEach(function (field) {
        if (!validateField(field, true)) valid = false;
      });

      if (!valid) {
        var firstInvalid = form.querySelector('.user-form-control.is-invalid, .ms-trigger.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    if (logoInput && logoPreview) {
      logoInput.addEventListener('change', function () {
        if (logoInput.files.length) {
          var reader = new FileReader();
          reader.onload = function (event) {
            logoPreview.src = event.target.result;
            logoPreview.style.display = 'block';
          };
          reader.readAsDataURL(logoInput.files[0]);
        }
        validateField('logo', true);
      });
    }

    if (phoneInput) {
      phoneInput.addEventListener('input', function () {
        phoneInput.value = normalizePhone(phoneInput.value);
        validateField('phone', false);
      });
      phoneInput.addEventListener('blur', function () { validateField('phone', true); });
    }

    ['company_name', 'tagline', 'business_desc', 'email', 'address', 'city'].forEach(function (field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, textarea');
      if (!input) return;
      input.addEventListener('blur', function () { validateField(field, true); });
      input.addEventListener('input', function () { validateField(field, false); });
    });

    if (categorySelect) {
      categorySelect.addEventListener('change', function () {
        validateField('category_id', true);
        validateField('sub_category_id', true);
      });
      categorySelect.addEventListener('blur', function () { validateField('category_id', true); });
    }

    if (subMulti && subMulti.listEl) {
      subMulti.listEl.addEventListener('change', function () {
        validateField('sub_category_id', true);
      });
    }

    form.addEventListener('submit', function (e) {
      if (phoneInput) phoneInput.value = normalizePhone(phoneInput.value);
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  });
})();
