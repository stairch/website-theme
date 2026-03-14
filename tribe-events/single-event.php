<?php
/**
 *
 * A custom template for The Events Calendar single event view,
 * styled with Tailwind CSS to match the STAIR theme.
 *
 */

if (!defined('ABSPATH')) {
    die('-1');
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$event_id = get_the_ID();
$has_venue = tribe_has_venue($event_id);
$has_organizer = tribe_has_organizer($event_id);

get_header();
?>

<div id="tribe-events-content"
    class="tribe-events-single min-h-screen bg-bg-light dark:bg-dark-bg transition-colors duration-300 py-12 w-full max-w-none mx-0!">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Main Rounded Card Container -->
        <div
            class="bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">

            <!-- Header Section -->
            <div
                class="bg-gray-50/50 dark:bg-dark-surface border-b border-gray-100 dark:border-dark-border p-8 md:p-12">
                <div class="max-w-4xl">
                    <!-- Navigation Back Link -->
                    <a href="<?php echo esc_url(tribe_get_events_link()); ?>"
                        class="inline-flex items-center text-sm font-medium text-text-light dark:text-dark-text-muted hover:text-primary dark:hover:text-primary mb-8 transition-colors duration-200">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        <?php printf(esc_html__('All %s', 'the-events-calendar'), $events_label_plural); ?>
                    </a>

                    <h1
                        class="text-4xl md:text-5xl lg:text-6xl font-bold text-text-dark dark:text-dark-text mb-8 leading-tight tracking-tight">
                        <?php the_title(); ?>
                    </h1>

                    <div
                        class="flex flex-wrap items-center gap-4 text-text-light dark:text-dark-text-muted text-base md:text-lg">
                        <div
                            class="flex items-center bg-white dark:bg-dark-bg px-5 py-2.5 rounded-2xl border border-gray-200 dark:border-dark-border shadow-sm">
                            <i data-lucide="calendar" class="w-5 h-5 mr-3 text-primary"></i>
                            <span class="font-medium text-text-dark dark:text-dark-text">
                                <?php echo tribe_get_start_date($event_id, false, 'l, j. F Y'); ?>
                            </span>
                        </div>
                        <?php if (tribe_get_cost()): ?>
                            <div
                                class="flex items-center bg-white dark:bg-dark-bg px-5 py-2.5 rounded-2xl border border-gray-200 dark:border-dark-border shadow-sm">
                                <i data-lucide="tag" class="w-5 h-5 mr-3 text-primary"></i>
                                <span
                                    class="font-medium text-text-dark dark:text-dark-text"><?php echo tribe_get_cost(null, true); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Content Grid Section -->
            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                    <!-- Left Column: Content (7 cols) -->
                    <div class="lg:col-span-7 xl:col-span-8">
                        <div class="prose prose-lg dark:prose-invert max-w-none marker:text-primary">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Right Column: Sidebar Details (5 cols) -->
                    <div class="lg:col-span-5 xl:col-span-4 space-y-8">

                        <!-- Date & Time Card -->
                        <div
                            class="bg-gray-50 dark:bg-dark-bg/50 rounded-2xl p-8 border border-gray-100 dark:border-dark-border">
                            <h3 class="text-xl font-bold mb-6 text-text-dark dark:text-dark-text flex items-center">
                                <i data-lucide="clock" class="w-6 h-6 mr-3 text-primary"></i>
                                <?php esc_html_e('Date & Time', 'the-events-calendar'); ?>
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div
                                        class="w-20 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-dark-text-muted mt-1.5">
                                        Start</div>
                                    <div class="flex-1">
                                        <div class="text-text-dark dark:text-dark-text font-bold text-lg">
                                            <?php echo tribe_get_start_date($event_id, false, 'D, d.m.Y'); ?>
                                        </div>
                                        <div class="text-gray-500 dark:text-dark-text-muted font-medium">
                                            <?php echo tribe_get_start_date($event_id, false, 'H:i'); ?> Uhr
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div
                                        class="w-20 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-dark-text-muted mt-1.5">
                                        End</div>
                                    <div class="flex-1">
                                        <div class="text-text-dark dark:text-dark-text font-bold text-lg">
                                            <?php echo tribe_get_end_date($event_id, false, 'D, d.m.Y'); ?>
                                        </div>
                                        <div class="text-gray-500 dark:text-dark-text-muted font-medium">
                                            <?php echo tribe_get_end_date($event_id, false, 'H:i'); ?> Uhr
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (tribe_get_single_ical_link()): ?>
                                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-dark-border/50">
                                    <a href="<?php echo tribe_get_single_ical_link(); ?>"
                                        class="flex items-center justify-center w-full py-3.5 px-4 bg-primary text-white font-bold rounded-xl hover:bg-primary-light transition-all duration-200 shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5">
                                        <i data-lucide="calendar-plus" class="w-5 h-5 mr-2"></i>
                                        Add to Calendar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($has_venue): ?>
                            <!-- Venue Card -->
                            <div
                                class="bg-gray-50 dark:bg-dark-bg/50 rounded-2xl p-8 border border-gray-100 dark:border-dark-border">
                                <h3 class="text-xl font-bold mb-6 text-text-dark dark:text-dark-text flex items-center">
                                    <i data-lucide="map-pin" class="w-6 h-6 mr-3 text-primary"></i>
                                    <?php esc_html_e('Venue', 'the-events-calendar'); ?>
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <div class="font-bold text-text-dark dark:text-dark-text text-xl mb-2">
                                            <?php echo tribe_get_venue(); ?>
                                        </div>
                                        <?php if (tribe_address_exists()): ?>
                                            <div class="text-gray-600 dark:text-dark-text-muted leading-relaxed font-medium">
                                                <?php echo tribe_get_full_address(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (tribe_show_google_map_link()): ?>
                                        <div>
                                            <a href="<?php echo tribe_get_map_link(); ?>" target="_blank"
                                                class="inline-flex items-center text-sm text-primary hover:text-primary-light font-bold uppercase tracking-wide transition-colors">
                                                Open in Maps <i data-lucide="external-link" class="w-4 h-4 ml-1"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_organizer): ?>
                            <!-- Organizer Card -->
                            <div
                                class="bg-gray-50 dark:bg-dark-bg/50 rounded-2xl p-8 border border-gray-100 dark:border-dark-border">
                                <h3 class="text-xl font-bold mb-6 text-text-dark dark:text-dark-text flex items-center">
                                    <i data-lucide="user" class="w-6 h-6 mr-3 text-primary"></i>
                                    <?php esc_html_e('Organizer', 'the-events-calendar'); ?>
                                </h3>
                                <div class="space-y-4">
                                    <div class="font-bold text-text-dark dark:text-dark-text text-lg">
                                        <?php echo tribe_get_organizer(); ?>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <?php
                                        $phone = tribe_get_organizer_phone();
                            $email = tribe_get_organizer_email();
                            $website = tribe_get_organizer_website_link();
                            ?>
                                        <?php if ($phone): ?>
                                            <div
                                                class="flex items-center text-sm text-gray-600 dark:text-dark-text-muted font-medium">
                                                <i data-lucide="phone" class="w-4 h-4 mr-3 opacity-70"></i>
                                                <?php echo esc_html($phone); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($email): ?>
                                            <div
                                                class="flex items-center text-sm text-gray-600 dark:text-dark-text-muted font-medium">
                                                <i data-lucide="mail" class="w-4 h-4 mr-3 opacity-70"></i>
                                                <?php echo esc_html($email); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($website): ?>
                                            <div class="text-sm pt-1">
                                                <?php echo $website; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>