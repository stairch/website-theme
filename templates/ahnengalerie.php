<?php
/**
 * Template Name: Ahnengalerie
 * 
 * Displays all former STAIR members in a grid layout.
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
            <p class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto">
                <?php if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if (get_the_content()) {
                            the_content();
                        } else {
                            echo 'Die ehemaligen Mitglieder, die den Verein geprägt haben.';
                        }
                    }
                } ?>
            </p>
        </div>

        <!-- Members Grid -->
        <?php
        $former_members = new WP_Query(array(
            'post_type' => 'stair_former_member',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
        ?>

        <?php if ($former_members->have_posts()): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php while ($former_members->have_posts()):
                    $former_members->the_post(); ?>
                    <?php get_template_part('parts/content-member-card', null, ['type' => 'former']); ?>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white dark:bg-dark-surface rounded-full shadow-md mb-6">
                    <i data-lucide="history" class="w-10 h-10 text-text-lighter dark:text-dark-text-muted"></i>
                </div>
                <p class="text-xl text-text-light dark:text-dark-text-muted">Noch keine ehemaligen Mitglieder eingetragen.</p>
            </div>
        <?php endif; ?>

        <!-- Back Link -->
        <div class="mt-20 text-center">
            <a href="<?php echo home_url('/vorstand'); ?>"
                class="text-primary hover:text-primary-dark font-medium inline-flex items-center transition-colors group">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1 transform group-hover:-translate-x-1 transition-transform"></i>
                Zurück zum Vorstand
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
