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
        $former_members = new WP_Query([
                'post_type' => 'stair_former_member',
                'posts_per_page' => -1,
        ]);

        function semester_value($sem) {
            // 'HS' or 'FS'
            $type = substr($sem, 0, 2);
            $year = (int) substr($sem, 2);

            // Convert semester strings like "FS23" or "HS23" into a comparable numeric value.
            // Each year has two semesters: FS and HS.
            // By mapping them onto a linear timeline (year * 2 + semester offset), we get values like FS23=46, HS23=47, FS24=48, which makes chronological sorting easy
            return $year * 2 + ($type === 'HS' ? 1 : 0);
        }

        if (!empty($former_members->posts)) {
            usort($former_members->posts, function ($a, $b) {
                $a_time = get_field('_stair_former_member_active_time', $a->ID);
                $b_time = get_field('_stair_former_member_active_time', $b->ID);
                [$a_start, $a_end] = explode('-', $a_time);
                [$b_start, $b_end] = explode('-', $b_time);
                $a_end_val = semester_value($a_end);
                $b_end_val = semester_value($b_end);

                if ($a_end_val !== $b_end_val) {
                    // latest exits first
                    return $b_end_val <=> $a_end_val;
                }

                $a_start_val = semester_value($a_start);
                $b_start_val = semester_value($b_start);

                if ($a_start_val !== $b_start_val) {
                    // longest duration first
                    return $a_start_val <=> $b_start_val;
                }

                // finally alphabetically
                return strcmp($a->post_title, $b->post_title);
            });
        }
        ?>

        <?php if (!empty($former_members->posts)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($former_members->posts as $post):
                    setup_postdata($post); ?>
                    <?php get_template_part('parts/content-member-card', null, ['type' => 'former']); ?>
                <?php endforeach; ?>
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
