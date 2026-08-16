(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var menuToggle = document.querySelector('.mobile-menu-toggle');
        var navMenu    = document.querySelector('.nav-menu');

        if (!menuToggle || !navMenu) return;

        // ── Mobile menu open / close ──────────────────────────────────────
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            menuToggle.setAttribute('aria-expanded', navMenu.classList.contains('active'));
        });

        // ── Inject chevron inside each parent <a> ─────────────────────────
        // Desktop: chevron inherits the <a> hover colour automatically.
        // Mobile:  the entire <a> (text + chevron) is the toggle; navigation
        //          is prevented so the overview page doesn't open.
        navMenu.querySelectorAll('.menu-item-has-children').forEach(function(item) {
            var link = item.querySelector(':scope > a');
            if (!link) return;

            var chevron = document.createElement('span');
            chevron.className = 'nav-chevron';
            chevron.innerHTML = '<i data-lucide="chevron-down" class="w-5 h-5"></i>';
            link.appendChild(chevron);

            link.addEventListener('click', function(e) {
                // Only intercept when the nav is in mobile (fixed) mode
                if (window.getComputedStyle(navMenu).position === 'fixed') {
                    e.preventDefault();
                    item.classList.toggle('submenu-open');
                }
            });
        });

        // ── Close mobile menu when a leaf link is clicked ─────────────────
        navMenu.querySelectorAll('a').forEach(function(link) {
            var parentItem   = link.closest('.menu-item-has-children');
            var isParentLink = parentItem && parentItem.querySelector(':scope > a') === link;

            if (!isParentLink) {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                });
            }
        });

        // ── Close on outside click ────────────────────────────────────────
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) &&
                !menuToggle.contains(e.target) &&
                navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // ── Close on Escape ───────────────────────────────────────────────
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.focus();
            }
        });
    });
})();
