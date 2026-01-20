/**
 * Theme Toggle - Dark/Light Mode Switcher
 * Detects system preference by default, allows manual override with localStorage persistence
 */

(function() {
    'use strict';

    const STORAGE_KEY = 'theme';
    const DARK_CLASS = 'dark';

    /**
     * Get the current theme preference
     * Priority: localStorage > system preference
     */
    function getThemePreference() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            return stored;
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /**
     * Apply the theme to the document
     */
    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add(DARK_CLASS);
        } else {
            document.documentElement.classList.remove(DARK_CLASS);
        }
    }

    /**
     * Toggle between dark and light themes
     */
    function toggleTheme() {
        const currentTheme = document.documentElement.classList.contains(DARK_CLASS) ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        localStorage.setItem(STORAGE_KEY, newTheme);
        applyTheme(newTheme);
    }

    /**
     * Initialize theme toggle functionality
     */
    function init() {
        // Apply initial theme
        const theme = getThemePreference();
        applyTheme(theme);

        // Set up toggle button click handler
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('theme-toggle');
            if (toggleButton) {
                toggleButton.addEventListener('click', toggleTheme);
            }
        });

        // Listen for system preference changes (when no manual override is set)
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only auto-switch if user hasn't manually set a preference
            if (!localStorage.getItem(STORAGE_KEY)) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    // Initialize immediately
    init();

    // Expose toggle function globally for manual use
    window.toggleTheme = toggleTheme;
})();

