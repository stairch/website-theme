<?php get_header(); ?>

<?php if (have_posts()): ?>
    <main class="max-w-6xl mx-auto px-5 py-20 grow w-full">
        <?php while (have_posts()):
            the_post(); ?>
            <?php get_template_part('parts/content'); ?>
        <?php endwhile; ?>

        <div class="pagination mt-8">
            <?php the_posts_pagination(); ?>
        </div>
    </main>
<?php else: ?>
    <?php
    // Check if a page with 404 template exists
    $error_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => '404.php',
        'number' => 1,
    ));
    
    if (empty($error_page)) {
        $error_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'templates/404.php',
            'number' => 1,
        ));
    }
    
    if (!empty($error_page)) {
        // Display custom 404 page using its template
        $page = $error_page[0];
        global $post;
        $post = $page;
        setup_postdata($post);
        
        $template_file = get_page_template_slug($page->ID);
        if ($template_file && file_exists(get_template_directory() . '/' . $template_file)) {
            include(get_template_directory() . '/' . $template_file);
            wp_reset_postdata();
            get_footer();
            exit;
        }
        wp_reset_postdata();
    }
    
    // Default 404 message
    ?>
    <main class="max-w-6xl mx-auto px-5 py-20 grow w-full">
        <h1 class="text-4xl font-bold text-text-dark dark:text-dark-text">Hoppla, da hat wohl jemand einen Tritt verpasst!</h1>
        <p class="mt-4 text-text-light dark:text-dark-text-muted">Es scheint, als könnten wir die Seite nicht finden, wonach du suchst.</p>
        <br>
        <a href="/" class="inline-block px-8 py-3.5 bg-primary text-white rounded no-underline font-semibold transition-all duration-300 hover:bg-[#094d42] hover:-translate-y-0.5 hover:shadow-lg">Zur Startseite</a>
    </main>
<?php endif; ?>

<?php get_footer(); ?>