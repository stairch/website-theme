<?php
/**
 * List View Template
 *
 * A custom template for The Events Calendar list view.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
 *
 * @package TribeEventsCalendar
 *
 */

if (!defined('ABSPATH')) {
    die('-1');
}

use Tribe\Events\Views\V2\iCalendar\Links\Google_Calendar;
use Tribe\Events\Views\V2\iCalendar\Links\Outlook_365;
use Tribe\Events\Views\V2\iCalendar\Links\Outlook_Live;
use Tribe\Events\Views\V2\View;

get_header();
?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-5">
        <!-- Page Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4">Events</h1>
            <p class="text-lg text-text-light dark:text-dark-text-muted max-w-2xl mx-auto mb-8">
                Entdecke unsere kommenden Veranstaltungen und sei dabei!
            </p>
        </div>

        <?php if (function_exists('tribe_get_events')): ?>
            <?php
            // Custom Queries to replicate the logic from the custom page template
            // Note: We are ignoring the global query to enforce this specific layout

            $upcoming_events = tribe_get_events([
                'posts_per_page' => -1,
                'start_date' => 'now',
                'orderby' => 'event_date',
                'order' => 'ASC',
            ]);

            $past_events = tribe_get_events([
                'posts_per_page' => 3,
                'end_date' => 'now',
                'orderby' => 'event_date',
                'order' => 'DESC',
            ]);
            ?>

            <!-- Upcoming Events -->
            <section class="mb-20">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text flex items-center gap-3">
                        <span class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-white"></i>
                        </span>
                        Kommende Events
                    </h2>

                    <?php if (function_exists('tribe_get_ical_link') && class_exists('Tribe\Events\Views\V2\View')): ?>
                        <div class="relative inline-block text-left">
                            <button type="button" id="subscribe-dropdown-btn"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-lg text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors shadow-sm"
                                aria-expanded="false" aria-haspopup="true">
                                <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                                Kalender abonnieren
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                            </button>

                            <div id="subscribe-dropdown-menu"
                                class="hidden absolute right-0 mt-2 w-72 origin-top-right bg-white dark:bg-dark-surface rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 transform opacity-0 scale-95 transition-all duration-200 ease-out">
                                <div class="py-1" role="menu" aria-orientation="vertical"
                                    aria-labelledby="subscribe-dropdown-btn">
                                    <?php
                                    try {
                                        // create a list view instance to generate feed links
                                        $view = View::make('list');

                                        $google = new Google_Calendar();
                                        $outlook365 = new Outlook_365();
                                        $outlookLive = new Outlook_Live();

                                        $google_link = $google->get_uri($view);
                                        $outlook_365_link = $outlook365->get_uri($view);
                                        $outlook_live_link = $outlookLive->get_uri($view);

                                        // iCal link (standard)
                                        $ical_link = tribe_get_ical_link();

                                        // iCalendar (webcal)
                                        $webcal_link = str_replace(['http://', 'https://'], 'webcal://', $ical_link);

                                        // outlook .ics (append outlook-ical=1)
                                        $separator = (strpos($ical_link, '?') === false) ? '?' : '&';
                                        $outlook_ical_link = $ical_link . $separator . 'outlook-ical=1';

                                    } catch (Exception $e) {
                                        // fallback if something goes wrong
                                        $ical_link = tribe_get_ical_link();
                                        $google_link = 'https://www.google.com/calendar/render?cid=' . urlencode($ical_link);
                                        $webcal_link = str_replace(['http://', 'https://'], 'webcal://', $ical_link);
                                        $outlook_365_link = '#'; // disable or hide if failed
                                        $outlook_live_link = '#';
                                        $outlook_ical_link = $ical_link;
                                    }
                                    ?>

                                    <?php if ($google_link): ?>
                                        <a href="<?php echo esc_url($google_link); ?>" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                            role="menuitem">
                                            <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                                            Zu Google Kalender hinzufügen
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_url($webcal_link); ?>"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                        role="menuitem">
                                        <i data-lucide="calendar-check" class="w-4 h-4 text-primary"></i>
                                        Zu iCalendar hinzufügen
                                    </a>

                                    <?php if ($outlook_365_link && $outlook_365_link !== '#'): ?>
                                        <a href="<?php echo esc_url($outlook_365_link); ?>" target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                            role="menuitem">
                                            <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                                            Zu Outlook 365 hinzufügen
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($outlook_live_link && $outlook_live_link !== '#'): ?>
                                        <a href="<?php echo esc_url($outlook_live_link); ?>" target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                            role="menuitem">
                                            <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                                            Zu Outlook Live hinzufügen
                                        </a>
                                    <?php endif; ?>

                                    <div class="border-t border-gray-100 dark:border-dark-border my-1"></div>

                                    <a href="<?php echo esc_url($ical_link); ?>"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                        role="menuitem">
                                        <i data-lucide="download" class="w-4 h-4 text-primary"></i>
                                        Als .ics exportieren
                                    </a>

                                    <a href="<?php echo esc_url($outlook_ical_link); ?>"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-text-dark dark:text-dark-text hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                                        role="menuitem">
                                        <i data-lucide="download" class="w-4 h-4 text-primary"></i>
                                        Als Outlook .ics exportieren
                                    </a>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const btn = document.getElementById('subscribe-dropdown-btn');
                                const menu = document.getElementById('subscribe-dropdown-menu');

                                if (btn && menu) {
                                    btn.addEventListener('click', function (e) {
                                        e.stopPropagation();
                                        const isExpanded = btn.getAttribute('aria-expanded') === 'true';

                                        if (isExpanded) {
                                            closeMenu();
                                        } else {
                                            openMenu();
                                        }
                                    });

                                    // close when clicking outside
                                    document.addEventListener('click', function (e) {
                                        if (!menu.contains(e.target) && !btn.contains(e.target)) {
                                            closeMenu();
                                        }
                                    });

                                    // close on escape key
                                    document.addEventListener('keydown', function (e) {
                                        if (e.key === 'Escape') {
                                            closeMenu();
                                        }
                                    });
                                }

                                function openMenu() {
                                    menu.classList.remove('hidden');
                                    // small delay to allow display:block to apply before opacity transition
                                    requestAnimationFrame(() => {
                                        menu.classList.remove('opacity-0', 'scale-95');
                                        menu.classList.add('opacity-100', 'scale-100');
                                    });
                                    btn.setAttribute('aria-expanded', 'true');
                                }

                                function closeMenu() {
                                    menu.classList.remove('opacity-100', 'scale-100');
                                    menu.classList.add('opacity-0', 'scale-95');
                                    // wait for transition to finish before hiding
                                    setTimeout(() => {
                                        menu.classList.add('hidden');
                                    }, 200);
                                    btn.setAttribute('aria-expanded', 'false');
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>

                <?php if (!empty($upcoming_events)): ?>
                    <div class="space-y-6">
                        <?php foreach ($upcoming_events as $event): ?>
                            <?php
                            $event_id = $event->ID;
                            $start_date = tribe_get_start_date($event_id, false, 'd. F Y');
                            $start_time = tribe_get_start_date($event_id, false, 'H:i');
                            $end_time = tribe_get_end_date($event_id, false, 'H:i');
                            $venue = tribe_get_venue($event_id);
                            $permalink = get_permalink($event_id);
                            ?>
                            <article
                                class="bg-white dark:bg-dark-surface rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Date Badge -->
                                    <div
                                        class="bg-primary text-white p-6 md:w-40 shrink-0 flex flex-col items-center justify-center text-center">
                                        <span
                                            class="text-3xl font-bold"><?php echo esc_html(tribe_get_start_date($event_id, false, 'd')); ?></span>
                                        <span
                                            class="text-sm uppercase tracking-wider"><?php echo esc_html(tribe_get_start_date($event_id, false, 'M Y')); ?></span>
                                    </div>

                                    <!-- Event Details -->
                                    <div class="p-6 grow">
                                        <h3 class="text-xl font-bold text-text-dark dark:text-dark-text mb-2">
                                            <a href="<?php echo esc_url($permalink); ?>"
                                                class="hover:text-primary transition-colors">
                                                <?php echo esc_html($event->post_title); ?>
                                            </a>
                                        </h3>

                                        <div class="flex flex-wrap gap-4 text-text-light dark:text-dark-text-muted text-sm mb-3">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="clock" class="w-4 h-4"></i>
                                                <?php echo esc_html($start_time); ?> - <?php echo esc_html($end_time); ?>
                                            </span>
                                            <?php if ($venue): ?>
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                    <?php echo esc_html($venue); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-text-light dark:text-dark-text-muted line-clamp-2">
                                            <?php echo esc_html(wp_trim_words(wp_strip_all_tags($event->post_content), 30)); ?>
                                        </p>
                                    </div>

                                    <!-- Action -->
                                    <div class="p-6 flex items-center">
                                        <a href="<?php echo esc_url($permalink); ?>"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-primary text-white rounded-lg hover:bg-primary-light transition-colors">
                                            Details
                                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-white dark:bg-dark-surface rounded-xl p-12 text-center transition-colors duration-300">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-bg-light dark:bg-dark-bg rounded-full mb-4">
                            <i data-lucide="calendar-off" class="w-8 h-8 text-text-lighter dark:text-dark-text-muted"></i>
                        </div>
                        <p class="text-text-light dark:text-dark-text-muted">Keine kommenden Events geplant. Schau bald wieder
                            vorbei!</p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Past Events -->
            <?php if (!empty($past_events)): ?>
                <section>
                    <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-8 flex items-center gap-3">
                        <span
                            class="w-10 h-10 bg-text-lighter dark:bg-dark-border rounded-full flex items-center justify-center">
                            <i data-lucide="history" class="w-5 h-5 text-white"></i>
                        </span>
                        Vergangene Events
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($past_events as $event): ?>
                            <?php
                            $event_id = $event->ID;
                            $start_date = tribe_get_start_date($event_id, false, 'd. F Y');
                            $permalink = get_permalink($event_id);
                            ?>
                            <article
                                class="bg-white dark:bg-dark-surface rounded-lg shadow-md overflow-hidden opacity-75 hover:opacity-100 transition-all duration-300">
                                <div class="p-5">
                                    <p class="text-text-lighter dark:text-dark-text-muted text-sm mb-2">
                                        <?php echo esc_html($start_date); ?>
                                    </p>
                                    <h3 class="text-lg font-semibold text-text-dark dark:text-dark-text">
                                        <a href="<?php echo esc_url($permalink); ?>" class="hover:text-primary transition-colors">
                                            <?php echo esc_html($event->post_title); ?>
                                        </a>
                                    </h3>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <?php
                        // Find the page with the "Vergangene Events" template
                        $past_events_page = get_pages([
                            'meta_key' => '_wp_page_template',
                            'meta_value' => 'templates/past-events.php',
                        ]);
                        $past_events_url = !empty($past_events_page) ? get_permalink($past_events_page[0]->ID) : '#';
                        ?>
                        <a href="<?php echo esc_url($past_events_url); ?>" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-dark-surface border-2 border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                            Alle vergangenen Events anzeigen
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </section>
            <?php endif; ?>

        <?php else: ?>
            <!-- Plugin Not Installed Message -->
            <div class="bg-white dark:bg-dark-surface rounded-xl p-12 text-center transition-colors duration-300">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-6">
                    <i data-lucide="alert-triangle" class="w-10 h-10 text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text mb-3">Plugin nicht installiert</h2>
                <p class="text-text-light dark:text-dark-text-muted max-w-md mx-auto">
                    Bitte installiere und aktiviere das <strong>"The Events Calendar"</strong> Plugin,
                    um Events auf dieser Seite anzuzeigen.
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>