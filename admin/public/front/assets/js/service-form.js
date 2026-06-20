/* JustGoom — Service form client validation */
(function () {
  var ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  var MAX_IMAGE_BYTES = 2 * 1024 * 1024;

  var rules = {
    product_name: {
      test: function (v) { return v.trim().length >= 2 && v.trim().length <= 200; },
      message: function (v) {
        if (v.trim().length === 0) return 'Service name is required.';
        if (v.trim().length < 2) return 'Service name must be at least 2 characters.';
        return 'Service name must not exceed 200 characters.';
      }
    },
    product_desc: {
      test: function (v) { return v.length <= 5000; },
      message: function () { return 'Description must not exceed 5000 characters.'; }
    },
    product_image: {
      test: function (file) {
        if (!file) return true;
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) return false;
        return file.size <= MAX_IMAGE_BYTES;
      },
      message: function (file) {
        if (!file) return '';
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) {
          return 'Service image must be JPG, PNG, WebP, or GIF.';
        }
        return 'Service image must not be larger than 2 MB.';
      }
    }
  };

  function setFieldError(form, fieldName, message) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function (el) {
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
    group.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('serviceForm');
    if (!form) return;

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    var imageInput = form.querySelector('input[name="product_image"]');

    function getFieldValue(fieldName) {
      if (fieldName === 'product_name') {
        return form.querySelector('input[name="product_name"]')?.value || '';
      }
      if (fieldName === 'product_desc') {
        return form.querySelector('textarea[name="product_desc"]')?.value || '';
      }
      if (fieldName === 'product_image') {
        return imageInput && imageInput.files.length ? imageInput.files[0] : null;
      }
      return '';
    }

    function validateField(fieldName, showError) {
      var rule = rules[fieldName];
      if (!rule) return true;

      var value = getFieldValue(fieldName);
      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = ['product_name', 'product_desc', 'product_image'];
      var valid = true;

      fields.forEach(function (field) {
        if (!validateField(field, true)) valid = false;
      });

      if (!valid) {
        var firstInvalid = form.querySelector('.user-form-control.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    ['product_name', 'product_desc'].forEach(function (field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, textarea');
      if (!input) return;
      input.addEventListener('blur', function () { validateField(field, true); });
      input.addEventListener('input', function () { validateField(field, false); });
    });

    if (imageInput) {
      imageInput.addEventListener('change', function () { validateField('product_image', true); });
    }

    form.addEventListener('submit', function (e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  });
})();
