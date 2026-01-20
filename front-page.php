<?php get_header(); ?>

<main class="grow">
    <!-- Hero Section -->
    <?php get_template_part('parts/section', 'hero'); ?>

    <!-- Unsere Ziele Section -->
    <?php get_template_part('parts/section', 'goals'); ?>

    <!-- News & Events Section -->
    <?php get_template_part('parts/content', 'news'); ?>
</main>

<?php get_footer(); ?>