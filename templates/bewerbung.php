<?php
/**
 * Template Name: Bewerbung
 *
 * Application page template with 2-column layout.
 * Left: Content (Text)
 * Right: Contact Form 7 (assumes form title is "Bewerbung")
 *
 * @package STAIR
 */

get_header();

// Check if there is content in the main editor
$has_content = !empty(trim(get_post()->post_content));
?>

<?php
/**
 * Tailwind Classes Safelist (for CF7 form content)
 * grid grid-cols-1 md:grid-cols-2 gap-5 gap-6 space-y-6 space-y-1.5 space-y-3
 * w-full md:w-auto pt-4 flex-col md:flex-row justify-between items-center
 * text-sm font-semibold text-gray-800 ml-1 mb-0
 */
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-5">

        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?>
            </h1>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <?php if ($has_content): ?>
                <!-- Left Column: Content & Intro -->
                <div class="lg:col-span-6 lg:sticky lg:top-24">
                    <div
                        class="bg-white dark:bg-dark-surface w-full rounded-lg shadow-md overflow-hidden border border-border-color dark:border-dark-border p-8 md:p-10 transition-colors duration-300">

                        <div class="prose prose-lg text-text-light dark:text-dark-text-muted dark:prose-invert">
                            <?php
                            if (have_posts()) {
                                while (have_posts()) {
                                    the_post();
                                    // output the content (text only, user should remove shortcode)
                                    the_content();
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Right Column: Application Form -->
            <div class="<?php echo $has_content ? 'lg:col-span-6' : 'lg:col-span-12'; ?>">
                <div
                    class="bg-white dark:bg-dark-surface w-full rounded-lg shadow-md overflow-hidden border border-border-color dark:border-dark-border p-6 sm:p-8 md:p-10 transition-colors duration-300">
                    <?php
                    // check if CF7 is active
                    if (shortcode_exists('contact-form-7')) {
                        // try to render the form by title "Bewerbung"
                        echo do_shortcode('[contact-form-7 title="Bewerbung"]');
                    } else {
                        ?>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
                            <i data-lucide="alert-triangle"
                                class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mx-auto mb-3"></i>
                            <p class="text-yellow-800 dark:text-yellow-200">
                                Bitte installiere das <strong>Contact Form 7</strong> Plugin.
                            </p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>