(function() {
    'use strict';

    function init() {
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Lucide icons to be created
            setTimeout(function() {
                const menuToggle = document.querySelector('.mobile-menu-toggle');
                const navMenu = document.querySelector('.nav-menu');
                const menuIcon = document.querySelector('.menu-icon');
                const closeIcon = document.querySelector('.close-icon');

                if (!menuToggle || !navMenu) {
                    return;
                }

                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    const isExpanded = navMenu.classList.contains('active');
                    menuToggle.setAttribute('aria-expanded', isExpanded);
                });

                const navLinks = navMenu.querySelectorAll('a');
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        navMenu.classList.remove('active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    });
                });

                document.addEventListener('click', function(event) {
                    const isClickInsideMenu = navMenu.contains(event.target);
                    const isClickOnToggle = menuToggle.contains(event.target);

                    if (!isClickInsideMenu && !isClickOnToggle && navMenu.classList.contains('active')) {
                        navMenu.classList.remove('active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && navMenu.classList.contains('active')) {
                        navMenu.classList.remove('active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                        menuToggle.focus();
                    }
                });
            }, 100); // Wait 100ms for Lucide to create icons
        });
    }

    init();
})();
