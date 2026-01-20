<?php get_header(); ?>

<main class="max-w-6xl mx-auto px-5 py-20 grow w-full">
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="mb-8">
                    <h1 class="text-4xl font-bold text-text-dark dark:text-dark-text"><?php the_title(); ?></h1>
                </header>

                <div class="prose prose-lg max-w-none text-text-light dark:text-dark-text-muted dark:prose-invert">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>