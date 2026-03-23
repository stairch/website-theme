<?php

/**
 * Dummy Content Generator for STAIR Theme
 *
 * Usage:
 * 1. Log in as an Administrator.
 * 2. Visit your WordPress Dashboard (e.g., /wp-admin/).
 * 3. Append ?generate_stair_dummy=1 to the URL (e.g., /wp-admin/?generate_stair_dummy=1).
 *
 * Safety: This script only runs if:
 * - The user is an admin ('manage_options').
 * - The environment is NOT 'production' (controlled by WP_ENVIRONMENT_TYPE).
 */

function stair_generate_dummy_content() {
    // Safety: Never run on production
    if (function_exists('wp_get_environment_type') && 'production' === wp_get_environment_type()) {
        return;
    }

    if (!isset($_GET['generate_stair_dummy']) || !current_user_can('manage_options')) {
        return;
    }

    $generate_stair_dummy = sanitize_text_field(wp_unslash($_GET['generate_stair_dummy']));
    if ('1' !== $generate_stair_dummy) {
        return;
    }

    if (
        !isset($_GET['_stair_dummy_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_stair_dummy_nonce'])), 'stair_generate_dummy')
    ) {
        return;
    }

    $num_posts = 10;
    $num_events = 10;

    echo '<h1>Generating Dummy Content...</h1>';

    // Generate Posts
    echo '<h2>Posts:</h2>';
    $galerie_cat = get_category_by_slug('galerie');
    $galerie_cat_id = $galerie_cat ? $galerie_cat->term_id : [];

    for ($i = 1; $i <= $num_posts; $i++) {
        // Stagger posts 1 week apart, ending with today
        $days_ago = ($i - 1) * 7;
        $post_date = date('Y-m-d H:i:s', strtotime("-$days_ago days"));

        $post_args = [
            'post_title'   => "Dummy Post #$i: " . wp_generate_password(8, false),
            'post_content' => 'This is a dummy post content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_date'    => $post_date,
        ];

        if ($galerie_cat_id) {
            $post_args['post_category'] = [$galerie_cat_id];
        }

        $post_id = wp_insert_post($post_args);

        if ($post_id) {
            echo '<p>' . esc_html(sprintf('Created post %d dated %s', (int) $post_id, $post_date)) . '</p>';
        }
    }

    // Generate Events (Tribe Events)
    if (class_exists('Tribe__Events__Main')) {
        echo '<h2>Events:</h2>';

        // Helper to create event
        $create_event_func = function ($title, $start_datetime, $end_datetime) {
            $event_id = null;
            // Try the newer ORM API first (recommended approach)
            if (function_exists('tribe_events')) {
                $event_id = tribe_events()->set_args([
                    'title'       => $title,
                    'content'     => 'This is a dummy event description.',
                    'status'      => 'publish',
                    'start_date'  => $start_datetime,
                    'end_date'    => $end_datetime,
                ])->create()->ID;
            }
            // Fallback to legacy function with correct separate date/time parameters
            elseif (function_exists('tribe_create_event')) {
                $event_date = date('Y-m-d', strtotime($start_datetime));
                $start_hour = date('H', strtotime($start_datetime));
                $start_min  = date('i', strtotime($start_datetime));
                $end_hour   = date('H', strtotime($end_datetime));
                $end_min    = date('i', strtotime($end_datetime));

                $event_id = tribe_create_event([
                    'post_title'        => $title,
                    'post_content'      => 'This is a dummy event description.',
                    'post_status'       => 'publish',
                    'EventStartDate'    => $event_date,
                    'EventEndDate'      => $event_date,
                    'EventStartHour'    => $start_hour,
                    'EventStartMinute'  => $start_min,
                    'EventEndHour'      => $end_hour,
                    'EventEndMinute'    => $end_min,
                    'EventAllDay'       => false,
                ]);
            }
            return $event_id;
        };

        // Past Events
        echo '<h3>Past Events (Last 10 Weeks)</h3>';
        for ($i = 1; $i <= $num_events; $i++) {
            $weeks_ago = $i;
            $timestamp = strtotime("-$weeks_ago weeks");

            $start_datetime = date('Y-m-d 18:00:00', $timestamp);
            $end_datetime   = date('Y-m-d 20:00:00', $timestamp);
            $event_title    = "Past Dummy Event #$i: " . wp_generate_password(8, false);

            $event_id = $create_event_func($event_title, $start_datetime, $end_datetime);

            if ($event_id) {
                echo '<p>' . esc_html(sprintf('Created past event %d (Date: %s)', (int) $event_id, date('Y-m-d', $timestamp))) . '</p>';
            } else {
                echo '<p>' . esc_html(sprintf('Failed to create past event #%d', (int) $i)) . '</p>';
            }
        }

        // Future Events
        $num_future_events = 8;
        echo '<h3>Future Events (Next Year)</h3>';
        for ($i = 1; $i <= $num_future_events; $i++) {
            // Start next year, spaced 1 week apart
            $weeks_future = $i - 1; // Start from 0 weeks offset from "next year"
            $timestamp = strtotime("+1 year +$weeks_future weeks");

            $start_datetime = date('Y-m-d 18:00:00', $timestamp);
            $end_datetime   = date('Y-m-d 20:00:00', $timestamp);
            $event_title    = "Future Dummy Event #$i: " . wp_generate_password(8, false);

            $event_id = $create_event_func($event_title, $start_datetime, $end_datetime);

            if ($event_id) {
                echo '<p>' . esc_html(sprintf('Created future event %d (Date: %s)', (int) $event_id, date('Y-m-d', $timestamp))) . '</p>';
            } else {
                echo '<p>' . esc_html(sprintf('Failed to create future event #%d', (int) $i)) . '</p>';
            }
        }

        echo '<p><strong>Note:</strong> Check both "Upcoming" and "Past" event lists.</p>';
    } else {
        echo '<p style="color:orange;">The Events Calendar plugin is not active. Skipping events.</p>';
    }

    // Generate Members
    echo '<h2>Members:</h2>';
    $positions = ['Präsident', 'Vizepräsident', 'Kassier', 'Aktuar', 'Beisitzer'];
    for ($i = 1; $i <= 5; $i++) {
        $member_id = wp_insert_post([
            'post_title'  => "Member Name $i",
            'post_status' => 'publish',
            'post_type'   => 'stair_member',
        ]);
        if ($member_id) {
            update_post_meta($member_id, '_stair_member_position', $positions[$i - 1]);
            update_post_meta($member_id, '_stair_member_study_status', 'seit 202' . $i . ' Informatik');
            update_post_meta($member_id, '_stair_member_order', $i);
            echo '<p>' . esc_html(sprintf('Created member %d as %s', (int) $member_id, $positions[$i - 1])) . '</p>';
        }
    }

    // Generate Sponsors
    echo '<h2>Sponsors:</h2>';
    $tiers = ['main_partner', 'event_sponsor', 'supporter'];
    for ($i = 1; $i <= 3; $i++) {
        $sponsor_id = wp_insert_post([
            'post_title'  => "Sponsor Corporation $i",
            'post_status' => 'publish',
            'post_type'   => 'sponsor',
        ]);
        if ($sponsor_id) {
            update_post_meta($sponsor_id, 'sponsor_url', 'https://example.com/sponsor' . $i);
            update_post_meta($sponsor_id, 'sponsor_tier', $tiers[$i - 1]);
            echo '<p>' . esc_html(sprintf('Created sponsor %d with tier %s', (int) $sponsor_id, $tiers[$i - 1])) . '</p>';
        }
    }

    echo '<h3>Done!</h3>';
    echo '<p><a href="' . esc_url(admin_url()) . '">Go to Dashboard</a></p>';
    exit;
}
add_action('admin_init', 'stair_generate_dummy_content');
