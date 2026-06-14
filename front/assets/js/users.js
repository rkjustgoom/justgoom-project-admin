/* JustGoom User Panel JS */
document.addEventListener('DOMContentLoaded', async function() {
  if (typeof loadUserIncludes === 'function') {
    await loadUserIncludes();
  }
  initUserSidebar();
  initUserTheme();
  initUserSession();
  initUserModals();
  initUserUpload();
  initUserTabs();
});

function initUserModals() {
  document.querySelectorAll('[data-modal-open]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var overlay = document.getElementById(btn.getAttribute('data-modal-open'));
      if (overlay) overlay.classList.add('open');
    });
  });
  document.querySelectorAll('[data-modal-close]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var overlay = btn.closest('.user-modal-overlay');
      if (overlay) overlay.classList.remove('open');
    });
  });
  document.querySelectorAll('.user-modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });
  document.querySelectorAll('[data-toast]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      alert(btn.getAttribute('data-toast') || 'Saved successfully!');
      var overlay = btn.closest('.user-modal-overlay');
      if (overlay) overlay.classList.remove('open');
    });
  });
}

function initUserUpload() {
  document.querySelectorAll('.user-upload-zone').forEach(function(zone) {
    var input = zone.querySelector('input[type="file"]');
    if (!input) return;
    zone.addEventListener('click', function() { input.click(); });
    input.addEventListener('change', function() {
      if (input.files.length) {
        var p = zone.querySelector('p');
        if (p) p.innerHTML = '<strong>' + input.files[0].name + '</strong> selected';
      }
    });
  });
}

function initUserTabs() {
  document.querySelectorAll('.user-tabs').forEach(function(bar) {
    var tabs = bar.querySelectorAll('.user-tab');
    var panelWrap = bar.parentElement;
    tabs.forEach(function(tab) {
      tab.addEventListener('click', function() {
        var target = tab.getAttribute('data-tab');
        tabs.forEach(function(t) { t.classList.remove('active'); });
        tab.classList.add('active');
        panelWrap.querySelectorAll('.user-tab-panel').forEach(function(p) {
          p.style.display = p.id === 'tab-' + target ? 'block' : 'none';
        });
      });
    });
  });
}

function userConfirmDelete(btn) {
  if (confirm('Are you sure you want to delete this item?')) {
    var row = btn.closest('tr');
    if (row) row.remove();
  }
}

function userCloseSidebar() {
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (sidebar) sidebar.classList.remove('open');
  if (overlay) overlay.classList.remove('open');
  document.body.classList.remove('user-sidebar-open');
}

function userOpenSidebar() {
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (sidebar) sidebar.classList.add('open');
  if (overlay) overlay.classList.add('open');
  document.body.classList.add('user-sidebar-open');
}

function initUserSidebar() {
  var toggle = document.querySelector('.user-menu-btn');
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (!toggle || !sidebar) return;

  toggle.addEventListener('click', function() {
    if (sidebar.classList.contains('open')) {
      userCloseSidebar();
    } else {
      userOpenSidebar();
    }
  });

  if (overlay) {
    overlay.addEventListener('click', userCloseSidebar);
  }

  var closeBtn = document.querySelector('.user-sidebar-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', userCloseSidebar);
  }

  sidebar.querySelectorAll('.user-nav-link, .user-sidebar-footer a').forEach(function(link) {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) userCloseSidebar();
    });
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') userCloseSidebar();
  });

  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) userCloseSidebar();
  });
}

function initUserTheme() {
  var btn = document.querySelector('.user-theme-btn');
  var root = document.documentElement;

  function applyTheme(theme) {
    if (theme === 'dark') {
      root.setAttribute('data-theme', 'dark');
      if (btn) btn.textContent = '☀️';
    } else {
      root.removeAttribute('data-theme');
      if (btn) btn.textContent = '🌙';
    }
    localStorage.setItem('jg_user_theme', theme);
  }

  applyTheme(localStorage.getItem('jg_user_theme') === 'dark' ? 'dark' : 'light');

  if (btn) {
    btn.addEventListener('click', function() {
      applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
  }
}

function initUserSession() {
  var name = sessionStorage.getItem('jg_user_name') || 'Shree Gold Jewellers';
  document.querySelectorAll('[data-user-name]').forEach(function(el) {
    el.textContent = name;
  });
  var parts = name.split(/\s+/).filter(Boolean);
  var initials = parts.length >= 2
    ? (parts[0][0] + parts[1][0]).toUpperCase()
    : name.slice(0, 2).toUpperCase();
  document.querySelectorAll('.user-topbar-avatar').forEach(function(el) {
    el.textContent = initials;
  });
}

function userLogout() {
  sessionStorage.removeItem('jg_user_name');
  sessionStorage.removeItem('jg_user_email');
  sessionStorage.removeItem('jg_user_logged_in');
  window.location.href = '../login.html';
}

function userSaveLogin(email) {
  var name = email.split('@')[0].replace(/[._]/g, ' ');
  name = name.replace(/\b\w/g, function(c) { return c.toUpperCase(); });
  if (email.toLowerCase().indexOf('shree') !== -1 || email.toLowerCase().indexOf('gold') !== -1) {
    name = 'Shree Gold Jewellers';
  }
  sessionStorage.setItem('jg_user_email', email);
  sessionStorage.setItem('jg_user_name', name);
  sessionStorage.setItem('jg_user_logged_in', '1');
}
