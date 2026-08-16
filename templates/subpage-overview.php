<?php
/**
 * Template Name: Unterseiten-Übersicht
 *
 * Auto-generates tiles for all WordPress child pages.
 * Assign this template to a parent page, or it is applied automatically
 * when a page has child pages (via the template_include filter in setup.php).
 *
 * @package STAIR
 */

get_header();
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-5">

        <!-- Page Header -->
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <header class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
                    <?php if (get_the_content()): ?>
                        <div class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </header>
            <?php endwhile; endif; ?>

        <!-- Subpage Cards -->
        <?php
        $parent_id  = get_the_ID();
        $child_pages = stair_get_subpage_overview_children($parent_id);

        if ($child_pages): ?>
        <div class="flex flex-col gap-5">
            <?php foreach ($child_pages as $child):
                $child_url   = get_permalink($child->ID);
                $child_title = get_the_title($child->ID);

                // Cover image: WordPress featured image
                $image_url = get_the_post_thumbnail_url($child->ID, 'large');

                // Description: first ~30 words of the child page content
                $raw       = wp_strip_all_tags(strip_shortcodes($child->post_content));
                $description = wp_trim_words($raw, 30, '…');
            ?>
                <a href="<?php echo esc_url($child_url); ?>"
                   class="group flex items-stretch rounded-xl sm:rounded-2xl overflow-hidden bg-dark-surface min-h-[120px] sm:min-h-[160px] shadow-md hover:shadow-xl transition-shadow duration-300 no-underline">

                    <!-- Left: Image -->
                    <div class="relative w-24 sm:w-40 md:w-64 flex-shrink-0 overflow-hidden bg-dark-border">
                        <?php if ($image_url): ?>
                            <img src="<?php echo esc_url($image_url); ?>"
                                 alt=""
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors duration-300"></div>
                        <?php else: ?>
                            <div class="absolute inset-0 flex items-center justify-center text-dark-text-muted">
                                <i data-lucide="image" class="w-8 h-8 sm:w-10 sm:h-10"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Content -->
                    <div class="flex-1 flex items-center px-4 py-4 sm:px-6 sm:py-5 md:px-8 md:py-6">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1">
                                <?php echo esc_html($child_title); ?>
                            </h2>
                            <?php if ($description): ?>
                                <p class="text-white/70 text-xs sm:text-sm md:text-base leading-relaxed">
                                    <?php echo esc_html($description); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- CTA -->
                        <div class="flex-shrink-0 ml-3 sm:ml-6 md:ml-8 flex items-center gap-1.5 sm:gap-2 text-white font-semibold whitespace-nowrap">
                            <span class="hidden sm:inline">Weiter</span>
                            <i data-lucide="move-right" class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-center text-text-light dark:text-dark-text-muted">Keine Unterseiten vorhanden.</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
