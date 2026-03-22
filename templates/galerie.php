<?php
/**
 * Template Name: Galerie
 * 
 * Gallery page for event photos.
 * Future implementation: Display posts/galleries per event with images.
 * 
 * @package STAIR
 */

get_header();
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-5">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
            <p class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto">
                <?php if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if (get_the_content()) {
                            the_content();
                        } else {
                            echo 'Eindrücke von unseren Events und Aktivitäten.';
                        }
                    }
                } ?>
            </p>
        </div>

        <?php
        $args = array(
            'category_name' => 'galerie',
            'posts_per_page' => 12,
        );
        $gallery_query = new WP_Query($args);

        if ($gallery_query->have_posts()): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($gallery_query->have_posts()):
                    $gallery_query->the_post();
                    // extract images from content
                    $content = get_the_content();
                    $preview_images = array();

                    // match image URLs from wp:image blocks or standard img tags
                    if (preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches)) {
                        $preview_images = array_slice($matches[1], 0, 4);
                    }
                    ?>
                    <article
                        class="bg-white dark:bg-dark-surface rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">
                        <div class="p-6 grow">
                            <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-2">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="text-sm text-text-light dark:text-dark-text-muted mb-4">
                                <?php echo get_the_date(); ?>
                            </div>

                            <?php if ($preview_images): ?>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <?php foreach ($preview_images as $image_url): ?>
                                        <a href="<?php the_permalink(); ?>" class="aspect-square overflow-hidden rounded-md block cursor-pointer">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="Gallery preview"
                                                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="prose prose-sm max-w-none text-text-light dark:text-dark-text-muted line-clamp-3 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>

                        <div class="px-6 pb-6 mt-auto">
                            <a href="<?php the_permalink(); ?>"
                                class="inline-flex items-center text-primary font-semibold hover:underline group">
                                Vollständige Galerie anzeigen
                                <i data-lucide="arrow-right"
                                    class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="mt-12 flex justify-center flex-wrap gap-2">
                <?php
                $links = paginate_links(array(
                    'total' => $gallery_query->max_num_pages,
                    'prev_text' => '<i data-lucide="chevron-left" class="w-5 h-5"></i>',
                    'next_text' => '<i data-lucide="chevron-right" class="w-5 h-5"></i>',
                    'type' => 'array',
                ));

                if ($links) {
                    foreach ($links as $link) {
                        if (strpos($link, 'current') !== false) {
                            $link = str_replace('page-numbers', 'px-4 py-2 bg-primary text-white border border-primary rounded-lg flex items-center justify-center min-w-[40px] shadow-sm', $link);
                        } else {
                            $link = str_replace('page-numbers', 'px-4 py-2 bg-white dark:bg-dark-surface text-text-light dark:text-dark-text border border-gray-200 dark:border-dark-border rounded-lg hover:bg-gray-50 dark:hover:bg-dark-border/50 hover:text-primary dark:hover:text-primary transition-all duration-200 flex items-center justify-center min-w-[40px] shadow-sm', $link);
                        }
                        echo wp_kses_post($link);
                    }
                }
                ?>
            </div>

            <?php wp_reset_postdata(); ?>

        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-12 text-center transition-colors duration-300">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-primary/10 rounded-full mb-6">
                    <i data-lucide="images" class="w-12 h-12 text-primary"></i>
                </div>
                <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-3">Noch keine Galerien</h2>
                <p class="text-text-light dark:text-dark-text-muted max-w-md mx-auto">
                    Es wurden noch keine Galerien veröffentlicht. Schau später wieder vorbei!
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>