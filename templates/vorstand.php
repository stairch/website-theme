<?php
/**
 * Template Name: Vorstand
 * 
 * Displays all STAIR board members in a grid layout.
 * Create a page called "Vorstand" and select this template.
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
                <?php 
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if (get_the_content()) {
                            the_content();
                        } else {
                            echo 'Lerne das Team kennen, das sich für deine Interessen einsetzt und STAIR am Laufen hält.';
                        }
                    }
                }
                ?>
            </p>
        </div>

        <!-- Members Grid -->
        <?php
        $members = new WP_Query(array(
            'post_type' => 'stair_member',
            'posts_per_page' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => '_stair_member_order',
            'order' => 'ASC',
        ));
        ?>

        <?php if ($members->have_posts()): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php while ($members->have_posts()):
                    $members->the_post(); ?>
                    <?php get_template_part('parts/content-member-card', null, ['type' => 'current']); ?>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="text-center py-16">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-white dark:bg-dark-surface rounded-full shadow-md mb-6">
                    <i data-lucide="users" class="w-10 h-10 text-text-lighter dark:text-dark-text-muted"></i>
                </div>
                <p class="text-xl text-text-light dark:text-dark-text-muted">Noch keine Mitglieder hinzugefügt.</p>
                <p class="text-text-lighter dark:text-dark-text-muted mt-2">Füge Mitglieder im WordPress Admin unter "STAIR
                    Members" hinzu.</p>
            </div>
        <?php endif; ?>

        <!-- Former Members Link -->
        <div class="mt-12 text-center">
            <a href="<?php echo home_url('/ahnengalerie'); ?>"
                class="inline-flex items-center text-text-light dark:text-dark-text-muted hover:text-primary dark:hover:text-primary transition-colors font-medium hover:underline decoration-2 underline-offset-4">
                <i data-lucide="history" class="w-4 h-4 mr-2"></i>
                Zu den ehemaligen Mitgliedern
            </a>
        </div>

        <!-- Join button -->
        <div class="mt-20 text-center">
            <p class="text-text-light dark:text-dark-text-muted text-lg">
                Du möchtest auch Teil von STAIR werden?
                <a href="<?php echo home_url('/bewerbung'); ?>"
                    class="text-primary hover:text-primary-dark font-medium inline-flex items-center transition-colors group">
                    Hier bewerben <i data-lucide="arrow-right"
                        class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </p>
        </div>
    </div>
</main>

<?php get_footer(); ?>