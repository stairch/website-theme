<?php
/**
 * Template Name: Study Guide
 *
 * Study Guide page with downloadable PDFs and useful links.
 * The intro text is managed via the WordPress page editor.
 * Cover image, download links, and useful links are managed via ACF fields.
 *
 * @package STAIR
 */

get_header();

$cover_image_url = function_exists('get_field') ? get_field('sg_cover_image') : '';
$download_url_de = function_exists('get_field') ? get_field('sg_download_url_de') : '';
$download_url_en = function_exists('get_field') ? get_field('sg_download_url_en') : '';

$category_labels = [
    'module'   => 'Module/Einschreibungen',
    'alltag'   => 'Studien Alltag',
    'software' => 'Software',
    'hardware' => 'Hardware',
    'partner'  => 'Partner',
];

$links_by_category = array_fill_keys(array_keys($category_labels), []);

$links_query = new WP_Query([
    'post_type'      => 'useful_link',
    'posts_per_page' => -1,
    'meta_key'       => 'link_order',
    'orderby'        => 'meta_value_num title',
    'order'          => 'ASC',
]);

if ($links_query->have_posts()) {
    while ($links_query->have_posts()) {
        $links_query->the_post();
        $category = function_exists('get_field') ? (get_field('link_category') ?: 'alltag') : 'alltag';
        $links_by_category[$category][] = [
            'title'       => get_the_title(),
            'url'         => function_exists('get_field') ? (get_field('link_url') ?: '#') : '#',
            'description' => function_exists('get_field') ? (get_field('link_description') ?: '') : '',
        ];
    }
    wp_reset_postdata();
}

?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-5">

        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <header class="mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-4"><?php the_title(); ?></h1>
                    <div class="text-lg text-text-light dark:text-dark-text-muted">
                        <?php the_content(); ?>
                    </div>
                </header>
            <?php endwhile; endif; ?>

        <section class="mb-16">
            <h2 class="text-3xl font-bold text-text-dark dark:text-dark-text mb-8">Study Guide – Digitale Version</h2>
            <div class="flex flex-col md:flex-row gap-10 items-center md:items-end">
                <?php if ($cover_image_url): ?>
                    <img src="<?php echo esc_url($cover_image_url); ?>" alt="Study Guide Cover"
                        class="w-56 md:w-72 rounded-lg shadow-md object-contain flex-shrink-0" />
                <?php endif; ?>
                <div class="flex flex-col gap-y-4 w-full md:w-60">
                    <?php if ($download_url_de): ?>
                        <a href="<?php echo esc_url($download_url_de); ?>" target="_blank" rel="noopener"
                            class="flex items-center justify-center py-3 px-6 bg-primary text-white font-bold rounded-xl hover:bg-primary-light transition-all duration-200 shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5">
                            <span>Download (DE)</span>
                            <i data-lucide="download" class="w-5 h-5 ml-3 pointer-events-none"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($download_url_en): ?>
                        <a href="<?php echo esc_url($download_url_en); ?>" target="_blank" rel="noopener"
                            class="flex items-center justify-center py-3 px-6 bg-primary text-white font-bold rounded-xl hover:bg-primary-light transition-all duration-200 shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5">
                            <span>Download (EN)</span>
                            <i data-lucide="download" class="w-5 h-5 ml-3 pointer-events-none"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
        $has_links = false;
        foreach ($links_by_category as $cat_links) {
            if (!empty($cat_links)) {
                $has_links = true;
                break;
            }
        }
        if ($has_links): ?>
            <section>
                <h2 class="text-3xl font-bold text-text-dark dark:text-dark-text mb-8">Nützliche Links</h2>
                <?php foreach ($category_labels as $key => $label):
                    if (empty($links_by_category[$key])) {
                        continue;
                    } ?>
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-text-dark dark:text-dark-text mb-4 pb-2 border-b border-border-color dark:border-dark-border">
                            <?php echo esc_html($label); ?>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php foreach ($links_by_category[$key] as $link): ?>
                                <a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener"
                                    class="group block rounded-lg border border-border-color dark:border-dark-border bg-white dark:bg-dark-surface p-4 transition-colors duration-200 hover:border-primary dark:hover:border-primary-light no-underline">
                                    <p class="flex items-center justify-between gap-2 text-sm font-semibold text-primary dark:text-primary-light mb-1">
                                        <?php echo esc_html($link['title']); ?>
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-60 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </p>
                                    <?php if ($link['description']): ?>
                                        <p class="text-sm text-text-light dark:text-dark-text-muted">
                                            <?php echo esc_html($link['description']); ?>
                                        </p>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
