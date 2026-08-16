<?php

/**
 * Return the child pages of $page_id as they appear in the main nav menu.
 * Falls back to WordPress page hierarchy if the page is not in the menu.
 *
 * @param int    $page_id       The parent page ID.
 * @param string $menu_location Registered menu location slug.
 * @return WP_Post[]
 */
function stair_get_subpage_overview_children(int $page_id, string $menu_location = 'main_menu'): array {
    // ── 1. Try the nav menu ───────────────────────────────────────────────
    $locations = get_nav_menu_locations();
    if (isset($locations[$menu_location])) {
        $items = wp_get_nav_menu_items($locations[$menu_location]);
        if ($items) {
            // Find the menu item corresponding to this page
            $parent_menu_item_id = null;
            foreach ($items as $item) {
                if ($item->object === 'page' && (int) $item->object_id === $page_id) {
                    $parent_menu_item_id = $item->ID;
                    break;
                }
            }

            if ($parent_menu_item_id) {
                $children = [];
                foreach ($items as $item) {
                    if ((int) $item->menu_item_parent !== $parent_menu_item_id) {
                        continue;
                    }
                    if ($item->object !== 'page') {
                        continue;
                    }
                    $post = get_post($item->object_id);
                    if ($post && $post->post_status === 'publish') {
                        $children[] = $post;
                    }
                }
                if ($children) {
                    return $children;
                }
            }
        }
    }

    // ── 2. Fall back to WordPress page hierarchy ──────────────────────────
    return get_pages([
        'parent'      => $page_id,
        'sort_column' => 'menu_order',
        'sort_order'  => 'ASC',
    ]) ?: [];
}

function normalize_url(string $url): string {
    // remove https://, www. and trailing /
    $url = preg_replace('#^https?://(www\.)?#', '', $url);
    $url = rtrim($url, '/');

    $parts = explode('/', $url);
    if (count($parts) > 2) {
        return 'Website';
    }

    return $url;
}
