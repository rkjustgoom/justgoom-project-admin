/* JustGoom — Shared user panel sidebar & header */
(function() {
  var savedTheme = localStorage.getItem('jg_user_theme');
  if (savedTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  }

  var NAV = [
    {
      heading: 'Overview',
      items: [
        { page: 'dashboard', href: 'index.html', icon: '📊', label: 'Dashboard' },
        { page: 'analytics', href: 'analytics.html', icon: '📈', label: 'Visitor Analytics' }
      ]
    },
    {
      heading: 'My Business',
      items: [
        { page: 'profile', href: 'profile.html', icon: '👤', label: 'My Profile' },
        { page: 'team', href: 'team.html', icon: '👥', label: 'My Team' },
        { page: 'services', href: 'services.html', icon: '💼', label: 'My Services' },
        { page: 'documents', href: 'documents.html', icon: '📄', label: 'My Document' }
      ]
    },
    {
      heading: 'Marketing',
      items: [
        { page: 'banners', href: 'banners.html', icon: '🖼', label: 'My Banner' },
        { page: 'videos', href: 'videos.html', icon: '🎬', label: 'My Video' },
        { page: 'articles', href: 'articles.html', icon: '📝', label: 'My Articles' }
      ]
    },
    {
      heading: 'Engagement',
      items: [
        { page: 'inquiries', href: 'inquiries.html', icon: '💬', label: 'My Inquiry' },
        { page: 'notifications', href: 'notifications.html', icon: '✉', label: 'My Notification' },
        { page: 'reviews', href: 'reviews.html', icon: '⭐', label: 'My Review' }
      ]
    },
    {
      heading: 'Account',
      items: [
        { page: 'subscription', href: 'subscription.html', icon: '💎', label: 'Subscription' },
        { page: 'change-password', href: 'change-password.html', icon: '🔑', label: 'Change Password' }
      ]
    }
  ];

  document.addEventListener('DOMContentLoaded', function() {
    renderTopBar();
    renderSidebar();
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

  function renderSidebar() {
    var el = document.getElementById('userSidebar');
    if (!el) return;

    var current = document.body.getAttribute('data-page') || 'dashboard';
    var html = '';

    html += '<button type="button" class="user-sidebar-close" aria-label="Close menu">✕</button>';
    html += '<div class="user-sidebar-brand"><a href="index.html"><img src="../assets/images/justgoom-logo.png" alt="JustGoom"></a></div>';
    html += '<div class="user-sidebar-plan"><span class="user-sidebar-plan-icon">💎</span><div><strong>Platinum Plan</strong><span>All features unlocked</span></div></div>';
    html += '<nav>';

    NAV.forEach(function(section) {
      html += '<div class="user-nav-section"><div class="user-nav-heading">' + section.heading + '</div>';
      section.items.forEach(function(item) {
        var cls = 'user-nav-link' + (item.page === current ? ' active' : '');
        html += '<a href="' + item.href + '" class="' + cls + '"><span class="nav-icon">' + item.icon + '</span>' + item.label + '</a>';
      });
      html += '</div>';
    });

    html += '</nav>';
    html += '<div class="user-sidebar-footer">';
    html += '<a href="../index.html">🌐 View Public Site</a>';
    html += '<a href="../login.html" onclick="userLogout(); return false;">🚪 Logout</a>';
    html += '</div>';

    el.innerHTML = html;
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
