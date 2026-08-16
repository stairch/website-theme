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

/**
 * Automatically apply the subpage-overview template to any page that is a
 * parent menu item with children (nav menu) or has WordPress child pages,
 * unless the page already has an explicit template set.
 */
add_filter('template_include', function ($template) {
    if (!is_page()) {
        return $template;
    }

    $post_id = get_queried_object_id();

    // Don't override if a specific template is already set
    if (get_page_template_slug($post_id)) {
        return $template;
    }

    // stair_get_subpage_overview_children() is defined in inc/helpers.php,
    // which is required after this file – but the callback fires later, so it's available.
    if (!function_exists('stair_get_subpage_overview_children')) {
        return $template;
    }

    $children = stair_get_subpage_overview_children($post_id);
    if (empty($children)) {
        return $template;
    }

    $overview = get_template_directory() . '/templates/subpage-overview.php';
    if (file_exists($overview)) {
        return $overview;
    }

    return $template;
});

function stair_load_assets() {
    // this tells wp_head() to output the link to style.css
    wp_enqueue_style('stair-main-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'), 'all');
    wp_enqueue_script('lucide-icons', 'https://unpkg.com/lucide@latest', [], null, true);
    wp_add_inline_script('lucide-icons', 'window.addEventListener("DOMContentLoaded", () => { lucide.createIcons(); });');

    wp_enqueue_script('stair-theme-toggle', get_template_directory_uri() . '/assets/theme-toggle.js', [], '1.0', true);

    wp_enqueue_script('stair-mobile-nav', get_template_directory_uri() . '/assets/mobile-nav.js', [], filemtime(get_stylesheet_directory() . '/assets/mobile-nav.js'), true);
    wp_enqueue_script('stair-email-protect', get_template_directory_uri() . '/assets/email-protect.js', [], '1.1', true);
    wp_enqueue_script('stair-cf7-file-input', get_template_directory_uri() . '/assets/cf7-file-input.js', [], '1.0', true);
}
add_action('wp_enqueue_scripts', 'stair_load_assets');
