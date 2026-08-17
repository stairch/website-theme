<?php
/**
 * Template Name: Bewerbung
 *
 * Application page template with 2-column layout.
 * Left:  Open positions pulled from the "stair_position" CPT
 * Right: Contact Form 7 with dynamic role dropdown ([stair_positions])
 *
 * @package STAIR
 */

get_header();

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

$page_content  = trim(get_post()->post_content);
$title_semester    = function_exists('get_field') ? get_field('bewerbung_title_semester') : '';
?>

<?php
/**
 * Tailwind Classes Safelist (for CF7 form content)
 * grid grid-cols-1 md:grid-cols-2 gap-5 gap-6 space-y-6 space-y-1.5 space-y-3
 * w-full md:w-auto pt-4 flex-col md:flex-row justify-between items-center
 * text-sm font-semibold text-gray-800 ml-1 mb-0
 */
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-5">

        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <!-- Left Column: single card with title, positions, optional text -->
            <div class="lg:col-span-6 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-dark-surface rounded-lg shadow-md border border-border-color dark:border-dark-border p-6 md:p-8 transition-colors duration-300 space-y-6">

                    <h2 class="text-xl font-bold text-text-dark dark:text-dark-text">
                        Offene Positionen <?php echo esc_html($title_semester); ?>
                    </h2>

                    <?php if (!empty($positions)): ?>
                        <div class="space-y-5">
                            <?php foreach ($positions as $position): ?>
                                <?php $description = function_exists('get_field') ? get_field('position_description', $position->ID) : ''; ?>
                                <div class="border-l-2 border-primary pl-4">
                                    <h3 class="font-semibold text-text-dark dark:text-dark-text">
                                        <?php echo esc_html($position->post_title); ?>
                                    </h3>
                                    <?php if (!empty($description)): ?>
                                        <p class="text-text-light dark:text-dark-text-muted text-sm leading-relaxed mt-1">
                                            <?php echo nl2br(esc_html($description)); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-text-light dark:text-dark-text-muted text-sm">
                            Aktuell sind keine Positionen ausgeschrieben.
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($page_content)): ?>
                        <div class="prose prose-sm text-text-light dark:text-dark-text-muted dark:prose-invert border-t border-border-color dark:border-dark-border pt-6">
                            <?php
                            if (have_posts()) {
                                while (have_posts()) {
                                    the_post();
                                    the_content();
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Right Column: Application Form -->
            <div class="lg:col-span-6">
                <div class="bg-white dark:bg-dark-surface w-full rounded-lg shadow-md overflow-hidden border border-border-color dark:border-dark-border p-6 sm:p-8 md:p-10 transition-colors duration-300">
                    <?php
                    if (shortcode_exists('contact-form-7')) {
                        echo do_shortcode('[contact-form-7 title="Bewerbung"]');
                    } else {
                        ?>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
                            <i data-lucide="alert-triangle" class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mx-auto mb-3"></i>
                            <p class="text-yellow-800 dark:text-yellow-200">
                                Bitte installiere das <strong>Contact Form 7</strong> Plugin.
                            </p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>
