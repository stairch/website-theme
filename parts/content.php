<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
    <header class="mb-4">
        <h2 class="text-3xl font-bold text-text-dark dark:text-dark-text">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
        </h2>
    </header>
    <div class="prose max-w-none text-text-light dark:text-dark-text-muted mb-4">
        <?php the_excerpt(); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="text-primary font-semibold hover:underline">Read more &rarr;</a>
</article>
