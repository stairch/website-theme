<?php

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function stair_register_position_cpt() {
    register_post_type('stair_position', [
        'labels' => [
            'name'               => 'Offene Positionen',
            'singular_name'      => 'Position',
            'add_new'            => 'Position hinzufügen',
            'add_new_item'       => 'Neue Position hinzufügen',
            'edit_item'          => 'Position bearbeiten',
            'view_item'          => 'Position anzeigen',
            'search_items'       => 'Positionen suchen',
            'not_found'          => 'Keine Positionen gefunden',
            'not_found_in_trash' => 'Keine Positionen im Papierkorb',
            'all_items'          => 'Alle Positionen',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'supports'      => ['title'],
        'menu_icon'     => 'dashicons-id-alt',
        'has_archive'   => false,
        'rewrite'       => false,
        'menu_position' => 20,
    ]);
}
add_action('init', 'stair_register_position_cpt');

/**
 * Add a column to the positions list showing active/inactive status.
 */
function stair_position_columns($columns) {
    $new = [];
    foreach ($columns as $key => $value) {
        $new[$key] = $value;
        if ($key === 'title') {
            $new['position_status'] = 'Status';
        }
    }
    return $new;
}
add_filter('manage_stair_position_posts_columns', 'stair_position_columns');

function stair_position_column_content($column, $post_id) {
    if ($column === 'position_status') {
        $active = get_post_meta($post_id, 'position_active', true);
        if ($active) {
            echo '<span style="color:#00a32a;font-weight:600;">&#10003; Vakant</span>';
        } else {
            echo '<span style="color:#999;">&#8212; Inaktiv</span>';
        }
    }
}
add_action('manage_stair_position_posts_custom_column', 'stair_position_column_content', 10, 2);

/**
 * Register the [stair_positions] CF7 form tag for the dynamic role dropdown.
 *
 * Usage in CF7 form editor:
 *   [stair_positions* your-position]        (required)
 *   [stair_positions your-position]          (optional)
 */
add_action('wpcf7_init', 'stair_cf7_add_positions_tag');
function stair_cf7_add_positions_tag() {
    if (!function_exists('wpcf7_add_form_tag')) {
        return;
    }
    wpcf7_add_form_tag(
        ['stair_positions', 'stair_positions*'],
        'stair_cf7_positions_tag_handler',
        ['name-attr' => true]
    );
}

function stair_cf7_positions_tag_handler($tag) {
    if (empty($tag->name)) {
        return '';
    }

    $required = strpos($tag->type, '*') !== false;

    $positions = get_posts([
        'post_type'      => 'stair_position',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => 'position_active',
                'value'   => '1',
                'compare' => '=',
            ],
        ],
    ]);

    $classes = ['wpcf7-form-control', 'wpcf7-select'];
    if ($required) {
        $classes[] = 'wpcf7-validates-as-required';
    }

    $id_attr    = $tag->get_id_option() ? ' id="' . esc_attr($tag->get_id_option()) . '"' : '';
    $class_attr = esc_attr(implode(' ', $classes));
    $name_attr  = esc_attr($tag->name);
    $req_attr   = $required ? ' required aria-required="true"' : '';

    $html  = '<span class="wpcf7-form-control-wrap" data-name="' . $name_attr . '">';
    $html .= '<select name="' . $name_attr . '" class="' . $class_attr . '"' . $id_attr . $req_attr . '>';
    $html .= '<option value="">— Position wählen —</option>';

    foreach ($positions as $position) {
        $html .= '<option value="' . esc_attr($position->post_title) . '">'
               . esc_html($position->post_title)
               . '</option>';
    }

    $html .= '</select>';
    $html .= '</span>';

    return $html;
}

add_filter('wpcf7_validate_stair_positions*', 'stair_cf7_validate_positions', 10, 2);
function stair_cf7_validate_positions($result, $tag) {
    // CF7 verifies the nonce before firing validation filters
    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $value = isset($_POST[$tag->name])
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        ? sanitize_text_field(wp_unslash($_POST[$tag->name]))
        : '';

    if (empty($value)) {
        $result->invalidate($tag, wpcf7_get_message('invalid_required'));
    }

    return $result;
}
