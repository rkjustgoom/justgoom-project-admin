/* JustGoom — Project form client validation */
(function () {
  function setError(group, message) {
    if (!group) return;
    var input = group.querySelector('input, select, textarea');
    var errorEl = group.querySelector('.user-field-error');
    if (input) input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
  }

  function clearError(group) {
    if (!group) return;
    var input = group.querySelector('input, select, textarea');
    var errorEl = group.querySelector('.user-field-error');
    if (input) input.classList.remove('is-invalid');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="projects"]');
    if (!form) return;

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    var typeSelect = document.getElementById('projectType');
    var fileGroup = document.getElementById('fileGroup');
    var urlGroup = document.getElementById('urlGroup');
    var isEdit = !!form.querySelector('input[name="_method"]');

    function syncTypeFields() {
      if (!typeSelect) return;
      if (typeSelect.value === 'link') {
        if (fileGroup) fileGroup.style.display = 'none';
        if (urlGroup) urlGroup.style.display = 'block';
      } else {
        if (fileGroup) fileGroup.style.display = 'block';
        if (urlGroup) urlGroup.style.display = 'none';
      }
    }

    if (typeSelect) {
      typeSelect.addEventListener('change', syncTypeFields);
      syncTypeFields();
    }

    form.addEventListener('submit', function (e) {
      var valid = true;
      var titleGroup = form.querySelector('[data-field="title"]');
      var title = form.querySelector('[name="title"]');
      var type = typeSelect ? typeSelect.value : '';
      var url = form.querySelector('[name="external_url"]');
      var file = form.querySelector('[name="file"]');

      if (!title || !title.value.trim()) {
        setError(titleGroup, 'Project title is required.');
        valid = false;
      } else if (title.value.trim().length > 200) {
        setError(titleGroup, 'Project title must not exceed 200 characters.');
        valid = false;
      } else if (!/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(title.value.trim())) {
        setError(titleGroup, 'Project title may only contain letters, numbers, and spaces.');
        valid = false;
      } else {
        clearError(titleGroup);
      }

      if (type === 'link') {
        var urlVal = url ? url.value.trim() : '';
        if (!urlVal) {
          setError(form.querySelector('[data-field="external_url"]'), 'External video URL is required for link projects.');
          valid = false;
        } else if (!/^https?:\/\/.+/i.test(urlVal)) {
          setError(form.querySelector('[data-field="external_url"]'), 'Enter a valid URL starting with http:// or https://.');
          valid = false;
        } else {
          clearError(form.querySelector('[data-field="external_url"]'));
        }
      } else if (!isEdit) {
        if (!file || !file.files || !file.files.length) {
          setError(form.querySelector('[data-field="file"]'), 'Please upload a project file.');
          valid = false;
        } else {
          clearError(form.querySelector('[data-field="file"]'));
        }
      }

      if (!valid) {
        e.preventDefault();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
