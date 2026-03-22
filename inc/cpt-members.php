<?php
/**
 * Custom Post Type: STAIR Members
 *
 * Registers the stair_member CPT and associated meta boxes
 * for displaying board members on the Vorstand page.
 */

/**
 * Register the STAIR Member custom post type
 */
function stair_register_member_cpt() {
    $labels = [
        'name'                  => 'STAIR Members',
        'singular_name'         => 'STAIR Member',
        'menu_name'             => 'STAIR Members',
        'add_new'               => 'Add New Member',
        'add_new_item'          => 'Add New Member',
        'edit_item'             => 'Edit Member',
        'new_item'              => 'New Member',
        'view_item'             => 'View Member',
        'search_items'          => 'Search Members',
        'not_found'             => 'No members found',
        'not_found_in_trash'    => 'No members found in trash',
        'all_items'             => 'All Members',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'member'],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => ['title', 'thumbnail'], // Title = Name, Thumbnail = Photo
    ];

    register_post_type('stair_member', $args);
}
add_action('init', 'stair_register_member_cpt');

/**
 * Add meta boxes for member details
 */
function stair_member_meta_boxes() {
    add_meta_box(
        'stair_member_details',
        'Member Details',
        'stair_member_details_callback',
        'stair_member',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'stair_member_meta_boxes');

/**
 * Render the meta box fields
 */
function stair_member_details_callback($post) {
    wp_nonce_field('stair_member_details_nonce', 'stair_member_nonce');

    $position = get_post_meta($post->ID, '_stair_member_position', true);
    $study_status = get_post_meta($post->ID, '_stair_member_study_status', true);
    $display_order = get_post_meta($post->ID, '_stair_member_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="stair_member_position">Position</label></th>
            <td>
                <input type="text" id="stair_member_position" name="stair_member_position" 
                       value="<?php echo esc_attr($position); ?>" class="regular-text"
                       placeholder="e.g., Präsident, Vizepräsident, Kassier">
                <p class="description">The member's role in STAIR</p>
            </td>
        </tr>
        <tr>
            <th><label for="stair_member_study_status">Study Status</label></th>
            <td>
                <input type="text" id="stair_member_study_status" name="stair_member_study_status" 
                       value="<?php echo esc_attr($study_status); ?>" class="regular-text"
                       placeholder="e.g., seit 2024 Informatik">
                <p class="description">Study program and start year</p>
            </td>
        </tr>
        <tr>
            <th><label for="stair_member_order">Display Order</label></th>
            <td>
                <input type="number" id="stair_member_order" name="stair_member_order" 
                       value="<?php echo esc_attr($display_order); ?>" class="small-text" min="0">
                <p class="description">Lower numbers appear first (0, 1, 2...)</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function stair_save_member_meta($post_id) {
    // Security checks
    if (!isset($_POST['stair_member_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['stair_member_nonce']));
    if (!wp_verify_nonce($nonce, 'stair_member_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save fields
    if (isset($_POST['stair_member_position'])) {
        update_post_meta($post_id, '_stair_member_position', sanitize_text_field(wp_unslash($_POST['stair_member_position'])));
    }

    if (isset($_POST['stair_member_study_status'])) {
        update_post_meta($post_id, '_stair_member_study_status', sanitize_text_field(wp_unslash($_POST['stair_member_study_status'])));
    }

    if (isset($_POST['stair_member_order'])) {
        update_post_meta($post_id, '_stair_member_order', absint(wp_unslash($_POST['stair_member_order'])));
    }
}
add_action('save_post_stair_member', 'stair_save_member_meta');

/**
 * Add custom columns to the members list in admin
 */
function stair_member_admin_columns($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['position'] = 'Position';
            $new_columns['study_status'] = 'Study Status';
            $new_columns['order'] = 'Order';
        }
    }
    return $new_columns;
}
add_filter('manage_stair_member_posts_columns', 'stair_member_admin_columns');

/**
 * Populate custom columns
 */
function stair_member_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'position':
            echo esc_html(get_post_meta($post_id, '_stair_member_position', true));
            break;
        case 'study_status':
            echo esc_html(get_post_meta($post_id, '_stair_member_study_status', true));
            break;
        case 'order':
            echo esc_html(get_post_meta($post_id, '_stair_member_order', true));
            break;
    }
}
add_action('manage_stair_member_posts_custom_column', 'stair_member_admin_column_content', 10, 2);

/**
 * Make order column sortable
 */
function stair_member_sortable_columns($columns) {
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-stair_member_sortable_columns', 'stair_member_sortable_columns');
