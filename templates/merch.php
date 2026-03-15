<?php
/**
 * Template Name: Merch
 * 
 * Placeholder page for STAIR merchandise.
 * 
 * @package STAIR
 */

get_header();
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-5">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
            <p class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto">
               <?php 
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if (get_the_content()) {
                            the_content();
                        } else {
                            echo 'STAIR Merchandise - Zeig deinen Spirit!';
                        }
                    }
                }
                ?>
            </p>
        </div>

        <!-- Placeholder Content -->
        <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-12 text-center transition-colors duration-300">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-primary/10 rounded-full mb-6">
                <i data-lucide="shirt" class="w-12 h-12 text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-3">Shop kommt bald</h2>
            <p class="text-text-light dark:text-dark-text-muted max-w-md mx-auto">
                Unser Merchandise-Shop ist in Vorbereitung. Bald kannst du hier coole STAIR-Artikel entdecken!
            </p>
        </div>

        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <?php if (get_the_content()): ?>
                    <div class="mt-12 bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 transition-colors duration-300">
                        <div class="prose max-w-none text-text-light dark:text-dark-text-muted">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>