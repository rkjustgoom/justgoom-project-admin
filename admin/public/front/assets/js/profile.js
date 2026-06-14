(function () {
  var categorySelect = document.getElementById('profileCategory');
  var subCategorySelect = document.getElementById('profileSubCategory');
  var phoneInput = document.querySelector('#profileForm input[name="phone"]');

  if (phoneInput) {
    phoneInput.addEventListener('input', function () {
      phoneInput.value = phoneInput.value.replace(/\D+/g, '').slice(0, 10);
    });
  }

  if (!categorySelect || !subCategorySelect) {
    return;
  }

  function setSubCategories(items, selectedId) {
    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
    items.forEach(function (item) {
      var option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.name;
      if (String(selectedId) === String(item.id)) {
        option.selected = true;
      }
      subCategorySelect.appendChild(option);
    });
    subCategorySelect.disabled = items.length === 0;
  }

  function loadSubCategories(categoryId, selectedId) {
    if (!categoryId) {
      setSubCategories([], '');
      return;
    }

    fetch(window.PROFILE_SUBCATEGORIES_URL + '/' + categoryId, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function (response) { return response.json(); })
      .then(function (data) { setSubCategories(data, selectedId); })
      .catch(function () { setSubCategories([], ''); });
  }

  categorySelect.addEventListener('change', function () {
    loadSubCategories(categorySelect.value, '');
  });

  if (categorySelect.value && subCategorySelect.options.length <= 1) {
    loadSubCategories(categorySelect.value, window.PROFILE_OLD?.sub_category_id || '');
  }
})();
