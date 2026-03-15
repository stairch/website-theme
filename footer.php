</div>

<!-- Footer -->
<footer
    class="bg-text-dark dark:bg-dark-surface text-white py-12 transition-colors duration-300 dark:border-t dark:border-dark-border">
    <div class="max-w-6xl mx-auto px-5">
        <div
            class="flex flex-col md:flex-row justify-between items-center md:items-center gap-8 text-center md:text-left">
            <div class="flex flex-col gap-4">
                <a href="https://stair.ch" class="flex items-center hover:opacity-80 transition-opacity duration-300">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/01_STAIR_Logo_original.png"
                        alt="STAIR Logo" class="h-10 w-auto object-contain">
                </a>
                <p class="text-text-lighter dark:text-dark-text-muted text-sm">© <span id="copyright-year">2016-today</span>
                    STAIR. Alle Rechte vorbehalten. <?php
                    $impressum = get_page_by_path('impressum');
                    if ($impressum): ?>
                        <a href="<?php echo esc_url(get_permalink($impressum->ID)); ?>"
                            class="ml-2 underline hover:opacity-80 transition-opacity duration-300">Impressum</a>
                    <?php endif; ?> <?php
                    $informationen = get_page_by_path('informationen');
                    if ($informationen): ?>
                        <a href="<?php echo esc_url(get_permalink($informationen->ID)); ?>"
                            class="ml-2 underline hover:opacity-80 transition-opacity duration-300">Informationen</a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex flex-col items-center md:items-end gap-2">
                <p class="text-text-lighter dark:text-dark-text-muted text-sm">Mit Unterstützung von:</p>
                <?php
                // Fetch all sponsors
                $carousel_sponsors = new WP_Query(array(
                    'post_type' => 'sponsor',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ));

                $sponsors_data = [];
                if ($carousel_sponsors->have_posts()) {
                    while ($carousel_sponsors->have_posts()) {
                        $carousel_sponsors->the_post();
                        // Only add if it has a logo
                        if (has_post_thumbnail()) {
                            $sponsors_data[] = [
                                'url' => function_exists('get_field') ? get_field('sponsor_url') : '',
                                'logo_id' => get_post_thumbnail_id(),
                                'title' => get_the_title(),
                            ];
                        }
                    }
                    wp_reset_postdata();
                }

                // If no sponsors, show HSLU fallback (no scrolling)
                if (empty($sponsors_data)) {
                    ?>
                    <a href="https://hslu.ch" target="_blank" rel="noopener noreferrer"
                        class="shrink-0 grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/HSLU_2022_logo.svg"
                            alt="HSLU Logo" class="h-8 w-auto">
                    </a>
                    <?php
                } elseif (count($sponsors_data) === 1) {
                    // If only one sponsor, show it statically (no scrolling)
                    $sponsor = $sponsors_data[0];
                    ?>
                    <a href="<?php echo esc_url($sponsor['url']); ?>" target="_blank" rel="noopener noreferrer"
                        class="shrink-0 grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100"
                        title="<?php echo esc_attr($sponsor['title']); ?>">
                        <?php echo wp_get_attachment_image($sponsor['logo_id'], 'medium', false, array('class' => 'h-8 w-auto object-contain')); ?>
                    </a>
                    <?php
                } else {
                    // If multiple sponsors, show scrolling carousel with greyscale effect
                    ?>
                    <div class="w-full max-w-sm overflow-hidden relative pause-on-hover mask-gradient">
                        <div class="flex animate-scroll gap-8 items-center">
                            <?php
                            // If few sponsors, duplicate them enough times to fill width and scroll smoothly
                            // For simplicity, we just duplicate the set twice to ensure the loop works
                            $loop_count = count($sponsors_data) < 5 ? 4 : 2;

                            for ($i = 0; $i < $loop_count; $i++) {
                                foreach ($sponsors_data as $sponsor) {
                                    ?>
                                    <a href="<?php echo esc_url($sponsor['url']); ?>" target="_blank" rel="noopener noreferrer"
                                        class="shrink-0 grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100"
                                        title="<?php echo esc_attr($sponsor['title']); ?>">
                                        <?php echo wp_get_attachment_image($sponsor['logo_id'], 'medium', false, array('class' => 'h-8 w-auto object-contain')); ?>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>