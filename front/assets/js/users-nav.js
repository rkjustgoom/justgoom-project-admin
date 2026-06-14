/* JustGoom — Shared user panel sidebar & header */
(function() {
  var savedTheme = localStorage.getItem('jg_user_theme');
  if (savedTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  }

  document.addEventListener('DOMContentLoaded', async function() {
    if (typeof loadUserIncludes === 'function') {
      await loadUserIncludes();
    }
    renderTopBar();
    renderHeaderExtras();
    setPageTitle();
  });

  function renderTopBar() {
    var main = document.querySelector('.user-main');
    if (!main || main.querySelector('.user-top-bar')) return;

    var bar = document.createElement('div');
    bar.className = 'user-top-bar';
    bar.innerHTML =
      '<div class="user-top-bar-inner">' +
        '<div class="user-top-bar-left">' +
          '<a href="tel:+919876543210">📞 +91 98765 43210</a>' +
          '<a href="mailto:info@justgoom.com">✉ info@justgoom.com</a>' +
        '</div>' +
        '<div class="user-top-bar-right">' +
          '<a href="../index.html">🌐 View Public Site</a>' +
        '</div>' +
      '</div>';

    main.insertBefore(bar, main.firstChild);
  }

  function renderHeaderExtras() {
    var header = document.querySelector('.user-page-header');
    if (!header || header.querySelector('.user-page-header-end')) return;

    var title = header.querySelector('.user-page-title');
    var end = document.createElement('div');
    end.className = 'user-page-header-end';

    var actions = document.createElement('div');
    actions.className = 'user-page-header-actions';
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    actions.innerHTML =
      '<button type="button" class="user-theme-btn" aria-label="Toggle dark mode" title="Toggle dark mode">' +
        (isDark ? '☀️' : '🌙') +
      '</button>' +
      '<span class="user-plan-chip">Platinum</span>' +
      '<a href="profile.html" class="user-topbar-avatar" title="My Profile">SG</a>';

    if (title) end.appendChild(title);
    end.appendChild(actions);
    header.appendChild(end);
  }

  function setPageTitle() {
    var titleEl = document.querySelector('.user-page-title');
    var pageTitle = document.body.getAttribute('data-title');
    if (titleEl && pageTitle) titleEl.textContent = pageTitle;
  }
})();
