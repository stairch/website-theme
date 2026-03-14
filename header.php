<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png"
        href="<?php echo get_template_directory_uri(); ?>/assets/STAIR-Logo-transparent-150x150.png">
    <!-- Prevent flash of wrong theme by setting dark class before CSS loads -->
    <script>
        (function () {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <?php wp_head(); ?>
</head>

<!-- body_class() helps plugins target specific pages -->

<body <?php body_class('font-sans text-text-dark dark:text-dark-text bg-white dark:bg-dark-bg leading-relaxed transition-colors duration-300 flex flex-col min-h-screen'); ?>>

    <header
        class="bg-white dark:bg-dark-surface shadow-md dark:shadow-dark-border/20 sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-5">
            <div class="flex justify-between items-center py-4">
                <a href="<?php echo home_url(); ?>"
                    class="flex items-center hover:opacity-80 transition-opacity duration-300">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/01_STAIR_Logo_original.png"
                        alt="STAIR Logo" class="h-12 w-auto object-contain">
                </a>

                <div class="flex items-center gap-4">

                    <nav class="nav">
                        <button
                            class="mobile-menu-toggle md:hidden flex items-center justify-center bg-transparent border-none cursor-pointer p-3 -mr-2 z-50 relative"
                            aria-label="Toggle menu"
                            aria-expanded="false">
                            <i data-lucide="menu"
                                class="menu-icon w-8 h-8 text-text-dark dark:text-dark-text pointer-events-none"></i>
                            <i data-lucide="x"
                                class="close-icon w-8 h-8 text-text-dark dark:text-dark-text pointer-events-none"></i>
                        </button>

                        <?php
                        wp_nav_menu([
                            'theme_location' => 'main_menu',
                            'container' => false,
                            'menu_class' => 'nav-menu hidden md:flex list-none gap-8 items-center',
                            'fallback_cb' => false, // Don't show garbage if no menu is assigned yet
                        ]);
?>
                    </nav>

                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark/light mode"
                        title="Toggle dark/light mode">
                        <i data-lucide="sun" class="sun-icon w-5 h-5 pointer-events-none"></i>
                        <i data-lucide="moon" class="moon-icon w-5 h-5 pointer-events-none"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div id="site-content" class="site-content grow flex flex-col">