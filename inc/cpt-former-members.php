<?php
/**
 * Custom Post Type: STAIR Former Members (Ahnengallerie)
 *
 * Registers the stair_former_member CPT and associated meta boxes
 * for displaying previous board members.
 */

/**
 * Register the STAIR Former Member custom post type
 */
function stair_register_former_member_cpt() {
    $labels = [
        'name' => 'Former Members',
        'singular_name' => 'Former Member',
        'menu_name' => 'Former Members',
        'add_new' => 'Add New Former Member',
        'add_new_item' => 'Add New Former Member',
        'edit_item' => 'Edit Former Member',
        'new_item' => 'New Former Member',
        'view_item' => 'View Former Member',
        'search_items' => 'Search Former Members',
        'not_found' => 'No former members found',
        'not_found_in_trash' => 'No former members found in trash',
        'all_items' => 'All Former Members',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'ahnengallerie'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-clock', // Distinct icon from current members
        'supports' => ['title', 'thumbnail'], // Title = Name, Thumbnail = Photo
    ];

    register_post_type('stair_former_member', $args);
}
add_action('init', 'stair_register_former_member_cpt');

/**
 * Add meta boxes for former member details
 */
function stair_former_member_meta_boxes() {
    add_meta_box(
        'stair_former_member_details',
        'Former Member Details',
        'stair_former_member_details_callback',
        'stair_former_member',
        'normal',
        'high',
    );
}
add_action('add_meta_boxes', 'stair_former_member_meta_boxes');

/**
 * Render the meta box fields
 */
function stair_former_member_details_callback($post) {
    wp_nonce_field('stair_former_member_details_nonce', 'stair_former_member_nonce');

    $position = get_post_meta($post->ID, '_stair_former_member_position', true);
    $active_time = get_post_meta($post->ID, '_stair_former_member_active_time', true);
    $display_order = get_post_meta($post->ID, '_stair_former_member_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="stair_former_member_position">Position</label></th>
            <td>
                <input type="text" id="stair_former_member_position" name="stair_former_member_position"
                    value="<?php echo esc_attr($position); ?>" class="regular-text"
                    placeholder="e.g., Präsident, Vizepräsident">
                <p class="description">The role they held</p>
            </td>
        </tr>
        <tr>
            <th><label for="stair_former_member_active_time">Active Time</label></th>
            <td>
                <input type="text" id="stair_former_member_active_time" name="stair_former_member_active_time"
                    value="<?php echo esc_attr($active_time); ?>" class="regular-text" placeholder="e.g., FS16-FS17">
                <p class="description">Time period when they were active</p>
            </td>
        </tr>
        <tr>
            <th><label for="stair_former_member_order">Display Order</label></th>
            <td>
                <input type="number" id="stair_former_member_order" name="stair_former_member_order"
                    value="<?php echo esc_attr($display_order); ?>" class="small-text" min="0">
                <p class="description">Lower numbers appear first</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function stair_save_former_member_meta($post_id) {
    // Security checks
    if (!isset($_POST['stair_former_member_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['stair_former_member_nonce']));
    if (!wp_verify_nonce($nonce, 'stair_former_member_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save fields
    if (isset($_POST['stair_former_member_position'])) {
        update_post_meta($post_id, '_stair_former_member_position', sanitize_text_field(wp_unslash($_POST['stair_former_member_position'])));
    }

    if (isset($_POST['stair_former_member_active_time'])) {
        update_post_meta($post_id, '_stair_former_member_active_time', sanitize_text_field(wp_unslash($_POST['stair_former_member_active_time'])));
    }

    if (isset($_POST['stair_former_member_order'])) {
        update_post_meta($post_id, '_stair_former_member_order', absint(wp_unslash($_POST['stair_former_member_order'])));
    }
}
add_action('save_post_stair_former_member', 'stair_save_former_member_meta');

/**
 * Add custom columns to the former members list in admin
 */
function stair_former_member_admin_columns($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['position'] = 'Position';
            $new_columns['active_time'] = 'Active Time';
            $new_columns['order'] = 'Order';
        }
    }
    return $new_columns;
}
add_filter('manage_stair_former_member_posts_columns', 'stair_former_member_admin_columns');

/**
 * Populate custom columns
 */
function stair_former_member_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'position':
            echo esc_html(get_post_meta($post_id, '_stair_former_member_position', true));
            break;
        case 'active_time':
            echo esc_html(get_post_meta($post_id, '_stair_former_member_active_time', true));
            break;
        case 'order':
            echo esc_html(get_post_meta($post_id, '_stair_former_member_order', true));
            break;
    }
}
add_action('manage_stair_former_member_posts_custom_column', 'stair_former_member_admin_column_content', 10, 2);

/**
 * Make order column sortable
 */
function stair_former_member_sortable_columns($columns) {
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-stair_former_member_sortable_columns', 'stair_former_member_sortable_columns');

/**
 * Sort former members archive by order field
 */
function stair_former_member_archive_sort($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('stair_former_member')) {
        $query->set('meta_key', '_stair_former_member_order');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts', 'stair_former_member_archive_sort');
