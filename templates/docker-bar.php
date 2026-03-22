<?php
/**
 * Template Name: Docker Bar
 *
 * Docker Bar page template with opening hours and information.
 * Content is managed through WordPress page editor and ACF fields (if available).
 *
 * @package STAIR
 */

get_header();
// Default values
$default_hours_text = "Dienstag: 17:00 – 19:00\nDonnerstag: 15:00 – 19:00\nFreitag: 15:00 – 19:00";
$default_subtitle = "Dein Treffpunkt an der HSLU";
$default_description = "Die Docker Bar wird von Student:innen und Mitarbeiter:innen der HSLU geführt. Unser Ziel ist es, die Bar während den Unterrichtswochen zu betreiben, exklusive Feiertage.";

// Get ACF fields if available, otherwise use defaults
$subtitle = function_exists('get_field') && get_field('docker_bar_subtitle')
    ? get_field('docker_bar_subtitle')
    : $default_subtitle;

$description = function_exists('get_field') && get_field('docker_bar_description')
    ? get_field('docker_bar_description')
    : $default_description;

// Get ACF fields if available, otherwise use defaults
$opening_hours_text = function_exists('get_field') && get_field('opening_hours_text')
    ? get_field('opening_hours_text')
    : $default_hours_text;

// Parse opening hours text into array for table display
$opening_hours = [];
// Clean up any <br> or <br /> tags that ACF might have added
$opening_hours_text = str_replace(['<br />', '<br/>', '<br>'], "\n", $opening_hours_text);
$lines = explode("\n", $opening_hours_text);
foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) {
        continue;
    }

    // Try to split by colon (but only the first one to preserve time format)
    if (strpos($line, ':') !== false) {
        $parts = explode(':', $line, 2);
        $opening_hours[] = [
            'day' => trim($parts[0]),
            'hours' => trim($parts[1]),
        ];
    } else {
        // Fallback: show entire line as day
        $opening_hours[] = ['day' => $line, 'hours' => ''];
    }
}

$menu_pdf_url = function_exists('get_field') && get_field('menu_pdf')
    ? get_field('menu_pdf')
    : '';

$application_url = function_exists('get_field') && get_field('application_url')
    ? get_field('application_url')
    : '/docker-bar-application/';

?>

<!-- Hero Section -->
<section class="hero relative h-[600px] bg-cover bg-center bg-no-repeat flex items-center justify-center text-center text-white"
    style="background-image: url(<?php echo esc_url(get_template_directory_uri() . "/assets/dockerbar.jpg"); ?>);">
    <div class="hero-overlay absolute inset-0 bg-black/50"></div>
    <div class="hero-content relative z-10 max-w-4xl px-5">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg"><?php the_title(); ?></h1>
        <p class="text-xl drop-shadow-md"><?php echo esc_html($subtitle); ?></p>
    </div>
</section>

<main class="py-20 bg-bg-light dark:bg-dark-bg transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-5">

        <!-- Hero Image / Logo Section -->
        <div
            class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 mb-8 text-center transition-colors duration-300">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-primary/10 rounded-full mb-6">
                <i data-lucide="beer" class="w-12 h-12 text-primary"></i>
            </div>
            <p class="text-text-light dark:text-dark-text-muted max-w-2xl mx-auto text-lg">
                <?php echo esc_html($description); ?>
            </p>
        </div>

        <!-- Opening Hours -->
        <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 mb-8 transition-colors duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="inline-flex items-center justify-center w-10 h-10 bg-primary/10 rounded-lg">
                    <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                </div>
                <h2 class="text-2xl font-bold text-text-dark dark:text-dark-text">Öffnungszeiten</h2>
            </div>

            <?php if (!empty($opening_hours)): ?>
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($opening_hours as $schedule): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-text-dark dark:text-dark-text">
                                        <?php echo esc_html($schedule['day']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-right text-text-light dark:text-dark-text-muted">
                                        <?php echo esc_html($schedule['hours']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <p class="mt-4 text-sm text-text-light dark:text-dark-text-muted">
                <i data-lucide="info" class="w-4 h-4 inline-block mr-1"></i>
                Geöffnet während den Unterrichtswochen, exklusive Feiertage.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="grid <?php echo $menu_pdf_url ? 'md:grid-cols-2' : 'grid-cols-1'; ?> gap-4 mb-8">
            <?php if ($menu_pdf_url): ?>
                <a href="<?php echo esc_url($menu_pdf_url); ?>" target="_blank" rel="noopener noreferrer"
                    class="flex items-center justify-center gap-3 bg-primary hover:bg-primary-dark text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Getränkekarte öffnen
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url($application_url); ?>"
                class="flex items-center justify-center gap-3 bg-white dark:bg-dark-surface hover:bg-gray-50 dark:hover:bg-gray-800 text-text-dark dark:text-dark-text font-semibold py-4 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                An der Bar arbeiten
            </a>
        </div>



        <!-- Additional Content from WordPress Editor -->
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <?php if (get_the_content()): ?>
                    <div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-8 transition-colors duration-300">
                        <div class="prose prose-lg max-w-none dark:prose-invert 
                                prose-headings:text-text-dark dark:prose-headings:text-dark-text
                                prose-p:text-text-light dark:prose-p:text-dark-text-muted
                                prose-a:text-primary hover:prose-a:text-primary-dark">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; endif; ?>

    </div>
</main>

<?php get_footer(); ?>