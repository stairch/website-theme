<?php

/**
 * STAIR Theme functions and definitions
 */

// Composer autoload (TGM Plugin Activation)
require get_template_directory() . '/vendor/autoload.php';

// TGM Plugin Activation configuration
require get_template_directory() . '/inc/tgm-config.php';

// Theme setup and scripts
require get_template_directory() . '/inc/setup.php';

// Custom Post Types
require get_template_directory() . '/inc/cpt-members.php';
require get_template_directory() . '/inc/cpt-former-members.php';
require get_template_directory() . '/inc/cpt-sponsors.php';

// ACF field definitions
require get_template_directory() . '/inc/acf-fields.php';

// Theme activation setup (pages and menu)
require get_template_directory() . '/inc/theme-activation.php';

// Dummy content generator (Developer Tool)
require get_template_directory() . '/inc/dummy-content.php';

// Customizer settings
require get_template_directory() . '/inc/customizer.php';

// Helper functions
require_once get_template_directory() . '/inc/helpers.php';

/**
 * Disable Contact Form 7 Auto Paragraph
 * This prevents CF7 from wrapping fields in <p> tags, which breaks the grid layout.
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Get obfuscation attributes for a link.
 *
 * @param string $email The email to protect.
 * @param string|null $replace_selector CSS selector to replace text content in (optional).
 * @param bool $replace_self Whether to replace the link's own text content (if selector is null).
 * @return array<string, string> Link attributes.
 */
function stair_get_email_obfuscation_attrs($email, $replace_selector = null, $replace_self = false) {
    if (!is_email($email)) {
        return [];
    }

    $encoded = base64_encode($email);
    $attrs = [
        'href' => '#',
        'class' => 'email-protect',
        'data-email' => $encoded,
    ];

    if ($replace_selector) {
        $attrs['data-replace-selector'] = $replace_selector;
    } elseif ($replace_self) {
        $attrs['data-replace-text'] = 'true';
    }

    return $attrs;
}

/**
 * Output an obfuscated email link.
 *
 * @param string $email The email address.
 * @param string $class Optional CSS classes.
 * @param bool $display_email Whether to display the email as text (true) or keep existing text (false).
 */
function stair_email_link($email, $class = '', $display_email = true) {
    if (!is_email($email)) {
    return;
    }

    // If display_email is true, we replace the text content with the decoded email.
    $attrs = stair_get_email_obfuscation_attrs($email, null, $display_email);

    // Add extra classes if provided.
    if ($class !== '') {
        $attrs['class'] .= ' ' . $class;
    }

    $content = $display_email ? '...' : ''; // Placeholder

    echo '<a';
    foreach ($attrs as $key => $value) {
        echo ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
    }
    echo '>' . esc_html($content) . '</a>';
}
