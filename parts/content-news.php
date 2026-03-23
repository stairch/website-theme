<!-- News & Events Section -->
<section class="py-20 bg-bg-light dark:bg-dark-bg transition-colors duration-300" id="news">
    <div class="max-w-6xl mx-auto px-5">
        <h2 class="text-4xl font-bold text-center mb-4 text-text-dark dark:text-dark-text">News & Events</h2>
        <p class="text-lg text-center text-text-light dark:text-dark-text-muted mb-12 max-w-2xl mx-auto">Bleib auf dem
            Laufenden über unsere Aktivitäten und Ankündigungen.</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- Upcoming Events Column -->
            <div>
                <h3 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-6 flex items-center gap-3">
                    <i data-lucide="calendar" class="w-6 h-6 text-primary"></i>
                    Nächste Events
                </h3>

                <?php if (function_exists('tribe_get_events')): ?>
                    <?php
                    $upcoming_events = tribe_get_events([
                        'posts_per_page' => 3,
                        'start_date' => 'now',
                        'orderby' => 'event_date',
                        'order' => 'ASC',
                    ]);
                    ?>

                    <?php if (!empty($upcoming_events)): ?>
                        <div class="space-y-4">
                            <?php foreach ($upcoming_events as $event): ?>
                                <?php
                                $event_id = $event->ID;
                                $start_date = tribe_get_start_date($event_id, false, 'd. M');
                                $start_time = tribe_get_start_date($event_id, false, 'H:i');
                                $end_time = tribe_get_end_date($event_id, false, 'H:i');
                                $permalink = get_permalink($event_id);
                                ?>
                                <a href="<?php echo esc_url($permalink); ?>"
                                    class="block bg-white dark:bg-dark-surface rounded-lg p-4 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                    <div class="flex gap-4">
                                        <div class="bg-primary text-white rounded-lg px-3 py-2 text-center shrink-0">
                                            <span
                                                class="block text-xl font-bold leading-tight"><?php echo esc_html(tribe_get_start_date($event_id, false, 'd')); ?></span>
                                            <span
                                                class="block text-xs uppercase"><?php echo esc_html(tribe_get_start_date($event_id, false, 'M')); ?></span>
                                        </div>
                                        <div class="grow min-w-0">
                                            <h4 class="font-semibold text-text-dark dark:text-dark-text truncate">
                                                <?php echo esc_html($event->post_title); ?>
                                            </h4>
                                            <p
                                                class="text-sm text-text-light dark:text-dark-text-muted flex items-center gap-1 mt-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <?php echo esc_html($start_time); ?> - <?php echo esc_html($end_time); ?>
                                            </p>
                                        </div>
                                        <div class="flex items-center text-primary">
                                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <a href="<?php echo esc_url(tribe_get_events_link()); ?>"
                            class="inline-flex items-center gap-2 mt-6 text-primary font-semibold hover:gap-3 transition-all">
                            Alle Events anzeigen
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    <?php else: ?>
                        <div class="bg-white dark:bg-dark-surface rounded-lg p-8 text-center">
                            <i data-lucide="calendar-off"
                                class="w-12 h-12 text-text-lighter dark:text-dark-text-muted mx-auto mb-3"></i>
                            <p class="text-text-light dark:text-dark-text-muted">Keine kommenden Events.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-white dark:bg-dark-surface rounded-lg p-8 text-center">
                        <p class="text-text-light dark:text-dark-text-muted text-sm">Events Plugin nicht aktiv.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- News Column -->
            <div>
                <h3 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-6 flex items-center gap-3">
                    <i data-lucide="newspaper" class="w-6 h-6 text-primary"></i>
                    News
                </h3>

                <?php
                $args = [
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                ];
                $query = new WP_Query($args);
                ?>

                <?php if ($query->have_posts()): ?>
                    <div class="space-y-4">
                        <?php while ($query->have_posts()):
                            $query->the_post(); ?>
                            <article
                                class="bg-white dark:bg-dark-surface rounded-lg overflow-hidden shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                <a href="<?php the_permalink(); ?>" class="flex gap-4 p-4">
                                    <div
                                        class="w-20 h-20 shrink-0 bg-linear-to-br from-primary to-primary-light rounded-lg overflow-hidden">
                                        <?php
                                        $has_image = false;
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover']);
                                            $has_image = true;
                                        } else {
                                            // try to find first image in content
                                            $content = get_the_content();
                                            if (preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches)) {
                                                echo '<img src="' . esc_url($matches[1]) . '" class="w-full h-full object-cover" alt="' . esc_attr(get_the_title()) . '">';
                                                $has_image = true;
                                            }
                                        }

                                        if (!$has_image): ?>
                                            <div class="w-full h-full flex items-center justify-center text-3xl">📰</div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grow min-w-0">
                                        <h4
                                            class="font-semibold text-text-dark dark:text-dark-text line-clamp-2 hover:text-primary transition-colors">
                                            <?php the_title(); ?>
                                        </h4>
                                        <p
                                            class="text-sm text-text-lighter dark:text-dark-text-muted mt-1 flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            <?php echo get_the_date('d. M Y'); ?>
                                        </p>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>

                    <?php
                    $news_link = home_url('/news');
                    ?>
                    <a href="<?php echo esc_url($news_link); ?>"
                        class="inline-flex items-center gap-2 mt-6 text-primary font-semibold hover:gap-3 transition-all">
                        Alle News anzeigen
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                <?php else: ?>
                    <div class="bg-white dark:bg-dark-surface rounded-lg p-8 text-center">
                        <i data-lucide="newspaper"
                            class="w-12 h-12 text-text-lighter dark:text-dark-text-muted mx-auto mb-3"></i>
                        <p class="text-text-light dark:text-dark-text-muted">Noch keine News vorhanden.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>