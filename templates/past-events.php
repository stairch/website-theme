<?php
/**
 * Template Name: Vergangene Events
 * 
 * Displays all past events in a grid layout.
 * Create a page and select this template to display all past events.
 * 
 * @package STAIR
 */

get_header();
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-5">
        <!-- Page Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
            <div class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto mb-8">
                <?php 
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if (get_the_content()) {
                            the_content();
                        } else {
                            echo 'Schau dir unsere vergangenen Veranstaltungen an und erlebe, was wir bereits erreicht haben.';
                        }
                    }
                }
                ?>
            </div>
            <a href="<?php echo esc_url(tribe_get_events_link()); ?>" 
                class="inline-flex items-center gap-2 text-primary hover:text-primary-light transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Zurück zu allen Events
            </a>
        </div>

        <?php if (function_exists('tribe_get_events')): ?>
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            
            $past_events = tribe_get_events(array(
                'posts_per_page' => 12,
                'end_date' => 'now',
                'orderby' => 'event_date',
                'order' => 'DESC',
                'paged' => $paged,
            ));

            $total_past_events = tribe_get_events(array(
                'posts_per_page' => -1,
                'end_date' => 'now',
                'fields' => 'ids',
            ));
            $total_count = count($total_past_events);
            $total_pages = ceil($total_count / 12);
            ?>

            <?php if (!empty($past_events)): ?>
                <section>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($past_events as $event): ?>
                            <?php
                            $event_id = $event->ID;
                            $start_date = tribe_get_start_date($event_id, false, 'd. F Y');
                            $start_time = tribe_get_start_date($event_id, false, 'H:i');
                            $end_time = tribe_get_end_date($event_id, false, 'H:i');
                            $venue = tribe_get_venue($event_id);
                            $permalink = get_permalink($event_id);
                            ?>
                            <article
                                class="bg-white dark:bg-dark-surface rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                                <div class="p-5">
                                    <p class="text-text-lighter dark:text-dark-text-muted text-sm mb-2">
                                        <?php echo esc_html($start_date); ?>
                                    </p>
                                    <h3 class="text-lg font-semibold text-text-dark dark:text-dark-text mb-2">
                                        <a href="<?php echo esc_url($permalink); ?>" class="hover:text-primary transition-colors">
                                            <?php echo esc_html($event->post_title); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="flex flex-wrap gap-3 text-text-light dark:text-dark-text-muted text-xs mb-3">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            <?php echo esc_html($start_time); ?>
                                        </span>
                                        <?php if ($venue): ?>
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                <?php echo esc_html($venue); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="text-text-light dark:text-dark-text-muted text-sm line-clamp-2">
                                        <?php echo esc_html(wp_trim_words(wp_strip_all_tags($event->post_content), 20)); ?>
                                    </p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="mt-12 flex justify-center items-center gap-2">
                            <?php if ($paged > 1): ?>
                                <a href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-lg text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    Zurück
                                </a>
                            <?php endif; ?>

                            <div class="flex items-center gap-1">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i == $paged): ?>
                                        <span class="px-4 py-2 bg-primary text-white rounded-lg font-semibold">
                                            <?php echo esc_html((string) $i); ?>
                                        </span>
                                    <?php else: ?>
                                        <a href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                                            class="px-4 py-2 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-lg text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors">
                                            <?php echo esc_html((string) $i); ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>

                            <?php if ($paged < $total_pages): ?>
                                <a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-lg text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors">
                                    Weiter
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php else: ?>
                <div class="bg-white dark:bg-dark-surface rounded-xl p-12 text-center transition-colors duration-300">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-bg-light dark:bg-dark-bg rounded-full mb-4">
                        <i data-lucide="calendar-off" class="w-8 h-8 text-text-lighter dark:text-dark-text-muted"></i>
                    </div>
                    <p class="text-text-light dark:text-dark-text-muted">Keine vergangenen Events gefunden.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Plugin Not Installed Message -->
            <div class="bg-white dark:bg-dark-surface rounded-xl p-12 text-center transition-colors duration-300">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-6">
                    <i data-lucide="alert-triangle" class="w-10 h-10 text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-3">Plugin nicht installiert</h2>
                <p class="text-text-light dark:text-dark-text-muted max-w-md mx-auto">
                    Bitte installiere und aktiviere das <strong>"The Events Calendar"</strong> Plugin,
                    um Events auf dieser Seite anzuzeigen.
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
