<?php

/**
 * theme setup and custom configuration.
 */

function stair_config() {
    register_nav_menus([
        'main_menu' => 'Main Menu',
    ]);
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('stair-member-thumb', 500, 500, true);
}
add_action('after_setup_theme', 'stair_config');

function stair_load_assets() {
    // this tells wp_head() to output the link to style.css
    wp_enqueue_style('stair-main-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'), 'all');
    wp_enqueue_script('lucide-icons', 'https://unpkg.com/lucide@latest', [], null, true);
    wp_add_inline_script('lucide-icons', 'window.addEventListener("DOMContentLoaded", () => { lucide.createIcons(); });');

    wp_enqueue_script('stair-theme-toggle', get_template_directory_uri() . '/assets/theme-toggle.js', [], '1.0', true);

    wp_enqueue_script('stair-mobile-nav', get_template_directory_uri() . '/assets/mobile-nav.js', [], '1.0', true);
    wp_enqueue_script('stair-email-protect', get_template_directory_uri() . '/assets/email-protect.js', [], '1.1', true);
}
add_action('wp_enqueue_scripts', 'stair_load_assets');
