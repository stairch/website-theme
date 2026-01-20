<?php
/**
 * Template Name: 404 Page
 * 
 * Displays a custom 404 page.
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
                <?php the_content(); ?> <!-- Inhalt der Seite vom WordPress-Editor -->
                <br>
            </p>
            <a href="/" class="inline-block px-8 py-3.5 bg-primary text-white rounded no-underline font-semibold transition-all duration-300 hover:bg-[#094d42] hover:-translate-y-0.5 hover:shadow-lg">Zur Startseite</a>
        </div>
    </div>
</main>

<?php get_footer(); ?>