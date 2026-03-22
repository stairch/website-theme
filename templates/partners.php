<?php
/**
 * Template Name: Partners
 *
 * A dedicated landing page for existing partners and potential new sponsors.
 * Displays an acquisition section and a grid of all sponsors.
 *
 * @package STAIR
 */

get_header(); ?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-5">

        <header class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-6">Partner und Sponsoren
            </h1>
            <p class="text-lg text-text-light dark:text-dark-text-muted max-w-3xl mx-auto">
                STAIR verbindet Studierende mit Unternehmen. Entdecken Sie unsere aktuellen Partner oder werden Sie
                selbst Teil unseres Netzwerks.
            </p>
        </header>

        <!-- Section A: Acquisition -->
        <section
            class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 md:p-12 mb-16 transition-colors duration-300">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-text-dark dark:text-dark-text mb-4">Partner werden</h2>
                    <p class="text-lg text-text-light dark:text-dark-text-muted mb-6">
                        Unterstützen Sie STAIR und erreichen Sie die nächste Generation von IT-Talenten direkt an der
                        Hochschule Luzern. Wir bieten Ihnen Sichtbarkeit, Networking-Events und direkten Zugang zu
                        motivierten Studierenden.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="<?php echo home_url('/kontakt'); ?>"
                            class="inline-flex items-center justify-center px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition-colors duration-300">
                            Kontakt aufnehmen
                        </a>
                    </div>
                </div>
                <div class="w-full md:w-1/3 flex justify-center">
                    <div class="w-32 h-32 md:w-48 md:h-48 bg-primary/10 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 md:w-24 md:h-24 text-primary"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section B: The Grid -->
        <section>
            <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-8 text-center">Unsere Partner</h2>

            <?php
            $sponsors_query = new WP_Query([
                'post_type' => 'sponsor',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            ]);

            if ($sponsors_query->have_posts()): ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <?php while ($sponsors_query->have_posts()):
                        $sponsors_query->the_post();
                        $sponsor_url = get_field('sponsor_url');
                        $sponsor_tier = get_field('sponsor_tier');
                        ?>
                        <div
                            class="bg-white dark:bg-dark-surface p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center h-40 group">
                            <?php if (has_post_thumbnail()): ?>
                                <?php if ($sponsor_url): ?>
                                    <a href="<?php echo esc_url($sponsor_url); ?>" target="_blank" rel="noopener noreferrer"
                                        class="block w-full h-full flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 opacity-80 hover:opacity-100 transform hover:scale-105">
                                        <?php the_post_thumbnail('medium', ['class' => 'max-h-24 w-auto object-contain']); ?>
                                    </a>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center grayscale opacity-80">
                                        <?php the_post_thumbnail('medium', ['class' => 'max-h-24 w-auto object-contain']); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-text-light dark:text-dark-text-muted font-medium text-center">
                                    <?php if ($sponsor_url): ?>
                                        <a href="<?php echo esc_url($sponsor_url); ?>" target="_blank" rel="noopener noreferrer"
                                            class="hover:text-primary transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php the_title(); ?>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 bg-white dark:bg-dark-surface rounded-xl shadow-sm">
                    <p class="text-text-light dark:text-dark-text-muted">Aktuell sind keine Partner gelistet.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php get_footer(); ?>
