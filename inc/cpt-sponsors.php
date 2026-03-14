<?php
/**
 * Register 'Sponsor' Custom Post Type and ACF Fields
 *
 * @package STAIR
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Sponsor Custom Post Type
 */
function stair_register_cpt_sponsor()
{

    $labels = [
        'name' => 'Sponsors',
        'singular_name' => 'Sponsor',
        'menu_name' => 'Sponsors',
        'name_admin_bar' => 'Sponsor',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Sponsor',
        'new_item' => 'New Sponsor',
        'edit_item' => 'Edit Sponsor',
        'view_item' => 'View Sponsor',
        'all_items' => 'All Sponsors',
        'search_items' => 'Search Sponsors',
        'parent_item_colon' => 'Parent Sponsors:',
        'not_found' => 'No sponsors found.',
        'not_found_in_trash' => 'No sponsors found in Trash.',
        'featured_image' => 'Sponsor Logo',
        'set_featured_image' => 'Set logo',
        'remove_featured_image' => 'Remove logo',
        'use_featured_image' => 'Use as logo',
    ];

    $args = [
        'labels' => $labels,
        'public' => false, // No single view needed
        'publicly_queryable' => true,  // But queryable for frontend lists
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'sponsor'],
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-awards',
        'supports' => ['title', 'thumbnail'], // Disable editor
    ];

    register_post_type('sponsor', $args);
}
add_action('init', 'stair_register_cpt_sponsor');

/**
 * Register ACF Fields for Sponsors
 */
function stair_register_sponsor_acf()
{

    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_sponsor_details',
        'title' => 'Sponsor Details',
        'fields' => [
            [
                'key' => 'field_sponsor_url',
                'label' => 'Website URL',
                'name' => 'sponsor_url',
                'type' => 'url',
                'instructions' => 'Link to the sponsor website.',
                'required' => 1,
            ],
            [
                'key' => 'field_sponsor_tier',
                'label' => 'Sponsor Tier',
                'name' => 'sponsor_tier',
                'type' => 'select',
                'instructions' => 'Controls display size and location (e.g. footer relevance).',
                'required' => 0,
                'choices' => [
                    'main_partner' => 'Main Partner',
                    'event_sponsor' => 'Event Sponsor',
                    'supporter' => 'Supporter',
                ],
                'default_value' => 'event_sponsor',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'sponsor',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);
}
add_action('acf/init', 'stair_register_sponsor_acf');

/**
 * Register [sponsor_grid] Shortcode
 *
 * Usage: [sponsor_grid limit="4" tier="main_partner"]
 */
function stair_sponsor_grid_shortcode($atts)
{
    $atts = shortcode_atts([
        'tier' => '', // Optional: 'main_partner', 'event_sponsor', 'supporter'
        'limit' => -1,
    ], $atts, 'sponsor_grid');

    $args = [
        'post_type' => 'sponsor',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => 'title',
        'order' => 'ASC',
    ];

    if (!empty($atts['tier'])) {
        $args['meta_key'] = 'sponsor_tier';
        $args['meta_value'] = sanitize_text_field($atts['tier']);
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 my-8">
        <?php while ($query->have_posts()):
            $query->the_post();
            $sponsor_url = function_exists('get_field') ? get_field('sponsor_url') : '';
            ?>
            <div
                class="bg-white dark:bg-dark-surface p-4 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center h-32">
                <?php if (has_post_thumbnail()): ?>
                    <?php if ($sponsor_url): ?>
                        <a href="<?php echo esc_url($sponsor_url); ?>" target="_blank" rel="noopener noreferrer"
                            class="block w-full h-full flex items-center justify-center transition-all duration-300 opacity-80 hover:opacity-100">
                            <?php the_post_thumbnail('medium', ['class' => 'max-h-20 w-auto object-contain']); ?>
                        </a>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center opacity-80">
                            <?php the_post_thumbnail('medium', ['class' => 'max-h-20 w-auto object-contain']); ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-text-light dark:text-dark-text-muted font-medium text-center">
                        <?php if ($sponsor_url): ?>
                            <a href="<?php echo esc_url($sponsor_url); ?>" target="_blank" rel="noopener noreferrer"
                                class="hover:text-primary transition-colors">
                                <?php the_title(); ?>
                            </a>
                        <?php else: ?>
                            <?php the_title(); ?>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endwhile;
    wp_reset_postdata(); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sponsor_grid', 'stair_sponsor_grid_shortcode');
