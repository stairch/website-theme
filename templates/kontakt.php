<?php
/**
 * Template Name: Kontakt
 *
 * Contact page template with Contact Form 7 integration.
 * Requires: Contact Form 7 plugin to be installed and active.
 *
 * After installing CF7, create a form with these fields:
 * - Name (text, required)
 * - Email (email, required)
 * - Topic/Betreff (text, required)
 * - Message/Nachricht (textarea, required)
 *
 * Then paste the CF7 shortcode in the page content.
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
                            echo 'Hast du Fragen, Anregungen oder möchtest du dich bei uns engagieren?
                Wir freuen uns auf deine Nachricht!';
                        }
                    }
                }
                ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Social Card Mobile -->
            <div class="lg:hidden">
                <?php get_template_part('parts/social-card'); ?>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-6 sm:p-8 transition-colors duration-300">
                    <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-6">Schreib uns</h2>

                    <?php
                    // check if CF7 is active
                    if (shortcode_exists('contact-form-7')) {
                        echo do_shortcode('[contact-form-7 title="Kontakt"]');
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

            <!-- Contact Info Sidebar -->
            <div class="space-y-6">
                <!-- Email Card -->
<!--                <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-6 transition-colors duration-300">-->
<!--                    <div class="flex items-center gap-4 mb-3">-->
<!--                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">-->
<!--                            <i data-lucide="mail" class="w-6 h-6 text-primary"></i>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            <h3 class="font-semibold text-text-dark dark:text-dark-text">E-Mail</h3>-->
<!--                            --><?php
//                            $contact_email = get_theme_mod('stair_contact_email', 'info@stair.ch');
//                            stair_email_link($contact_email, 'text-primary hover:underline');
//?>
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <!-- Social Card Desktop -->
                <div class="hidden lg:block">
                    <?php get_template_part('parts/social-card'); ?>
                </div>

                <!-- Location Card -->
                <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-6 transition-colors duration-300">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-text-dark dark:text-dark-text mb-1">Standort</h3>
                            <?php
                            $loc_org = function_exists('get_field') && get_field('location_org') ? get_field('location_org') : 'STAIR';
                            $loc_addr1 = function_exists('get_field') && get_field('location_address1') ? get_field('location_address1') : 'C/O Hochschule Luzern Informatik';
                            $loc_addr2 = function_exists('get_field') && get_field('location_address2') ? get_field('location_address2') : 'Suurstoffi 1';
                            $loc_city = function_exists('get_field') && get_field('location_city') ? get_field('location_city') : '6343 Rotkreuz';
                            ?>
                            <p class="text-text-light dark:text-dark-text-muted text-sm">
                                <?php echo esc_html($loc_org); ?><br>
                                <?php echo esc_html($loc_addr1); ?><br>
                                <?php echo esc_html($loc_addr2); ?><br>
                                <?php echo esc_html($loc_city); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cooperation Section -->
        <div class="mt-16">
            <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 md:p-12 transition-colors duration-300">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="shrink-0">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="handshake" class="w-8 h-8"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-4">Kooperation &
                            Zusammenarbeit</h2>
                        <p class="text-text-light dark:text-dark-text-muted mb-6 leading-relaxed">
                            Sie sind ein Unternehmen und möchten mit uns zusammenarbeiten? Wir prüfen jede Anfrage
                            sorgfältig, um sicherzustellen, dass sie einen echten Mehrwert für unsere Studierenden
                            bietet.
                        </p>

                        <h3 class="text-lg font-semibold text-text-dark dark:text-dark-text mb-3">So schätzen wir
                            Kooperationspotenzial ab:</h3>
                        <ul class="space-y-3 text-text-light dark:text-dark-text-muted">
                            <li class="flex gap-3">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-primary shrink-0 mt-0.5"></i>
                                <span><strong>Relevanz:</strong> Passt das Angebot zu den Interessen und Bedürfnissen
                                    der Informatik-Studierenden?</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-primary shrink-0 mt-0.5"></i>
                                <span><strong>Mehrwert:</strong> Bietet die Kooperation konkrete Vorteile (z.B.
                                    Wissenstransfer, Karrierechancen, Events)?</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-primary shrink-0 mt-0.5"></i>
                                <span><strong>Feedback:</strong> Wir stehen im ständigen Austausch mit der
                                    Studierendenschaft, um aktuelle Interessen abzugleichen.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>