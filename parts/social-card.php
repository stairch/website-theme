<!-- Social Card -->
<div class="bg-white dark:bg-dark-surface rounded-xl shadow-md p-6 transition-colors duration-300">
    <h3 class="font-semibold text-text-dark dark:text-dark-text mb-4">Folge uns</h3>
    <div class="flex gap-3">
        <?php
        // get social links from individual ACF fields
        $social_links = [];

        if (function_exists('get_field')) {
            for ($i = 1; $i <= 5; $i++) {
                $url = get_field('social_' . $i . '_url');
                $icon = get_field('social_' . $i . '_icon');

                // only add if url and icon are set
                if ($url && $icon) {
                    // derive platform name from URL for accessibility
                    $host = parse_url($url, PHP_URL_HOST);
                    $host = str_replace('www.', '', $host);
                    $platform = ucfirst(explode('.', $host)[0]);

                    $social_links[] = [
                        'platform' => $platform,
                        'url' => $url,
                        'icon' => $icon,
                    ];
                }
            }
        }

        // fallback defaults if no social links configured
        if (empty($social_links)) {
            $social_links = [
                ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/stairhslu/', 'icon' => 'linkedin'],
                ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/stairhslu/', 'icon' => 'instagram'],
                ['platform' => 'Facebook', 'url' => 'https://www.facebook.com/stairhslu/', 'icon' => 'facebook'],
            ];
        }

        foreach ($social_links as $social) :
            $platform = esc_attr($social['platform']);
            $url = esc_url($social['url']);
            $icon = trim($social['icon']);

            $is_svg = str_starts_with($icon, '<svg');
            $is_url = filter_var($icon, FILTER_VALIDATE_URL) || str_starts_with($icon, '/');
            ?>
            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"
               title="<?php echo esc_attr($platform); ?>"
               class="w-10 h-10 bg-bg-light dark:bg-dark-bg rounded-full flex items-center justify-center text-text-light dark:text-dark-text-muted hover:bg-primary hover:text-white transition-colors">
                <?php if ($is_svg) : ?>
                    <div class="w-5 h-5 [&>svg]:w-full [&>svg]:h-full [&>svg]:fill-current">
                        <?php echo $icon; // phpcs:ignore?>
                    </div>
                <?php elseif ($is_url) : ?>
                    <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($platform); ?>" class="w-5 h-5">
                <?php else : ?>
                    <i data-lucide="<?php echo esc_attr($icon); ?>" class="w-5 h-5"></i>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>