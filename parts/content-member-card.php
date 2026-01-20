<?php
/**
 * Template part for displaying a member card (current or former)
 * 
 * Usage:
 *   get_template_part('parts/content-member-card', null, ['type' => 'current']);
 *   get_template_part('parts/content-member-card', null, ['type' => 'former']);
 * 
 * @package STAIR
 */

$type = $args['type'] ?? 'current';
$is_former = ($type === 'former');

// Meta field prefixes differ between post types
$meta_prefix = $is_former ? '_stair_former_member' : '_stair_member';
$position = get_post_meta(get_the_ID(), $meta_prefix . '_position', true);

// Secondary info: study status for current, active time for former
$secondary_info = $is_former 
    ? get_post_meta(get_the_ID(), $meta_prefix . '_active_time', true)
    : get_post_meta(get_the_ID(), $meta_prefix . '_study_status', true);
$secondary_icon = $is_former ? 'calendar' : 'graduation-cap';

// Styling differences
$card_classes = $is_former 
    ? 'bg-white dark:bg-dark-surface rounded-xl shadow-sm overflow-hidden transition-all duration-300 grayscale hover:grayscale-0'
    : 'bg-white dark:bg-dark-surface rounded-xl shadow-md overflow-hidden';

$gradient_classes = $is_former
    ? 'bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800'
    : 'bg-gradient-to-br from-primary to-primary-light';

$placeholder_icon_classes = $is_former
    ? 'w-24 h-24 text-text-lighter/50 dark:text-dark-text-muted/50'
    : 'w-24 h-24 text-white/50';

$position_classes = $is_former
    ? 'text-primary font-medium mb-2 opacity-80'
    : 'text-primary font-semibold mb-2';
?>

<article class="<?php echo esc_attr($card_classes); ?>">
    <div class="aspect-square <?php echo esc_attr($gradient_classes); ?> overflow-hidden">
        <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('stair-member-thumb', array(
                'class' => 'w-full h-full object-cover',
            )); ?>
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <i data-lucide="user" class="<?php echo esc_attr($placeholder_icon_classes); ?>"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 text-center">
        <h3 class="text-xl font-bold text-text-dark dark:text-dark-text mb-1"><?php the_title(); ?></h3>

        <?php if ($position): ?>
            <p class="<?php echo esc_attr($position_classes); ?>"><?php echo esc_html($position); ?></p>
        <?php endif; ?>

        <?php if ($secondary_info): ?>
            <p class="text-text-light dark:text-dark-text-muted text-sm flex items-center justify-center gap-2">
                <i data-lucide="<?php echo esc_attr($secondary_icon); ?>" class="w-4 h-4 shrink-0"></i>
                <?php echo esc_html($secondary_info); ?>
            </p>
        <?php endif; ?>
    </div>
</article>
