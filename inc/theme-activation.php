<?php

/**
 * Theme activation setup.
 *
 * Creates default pages and navigation menu on first theme activation.
 * Uses a flag in wp_options to ensure setup only runs once.
 *
 * @package STAIR
 */

/**
 * Run theme setup on activation (only once per installation).
 */
function stair_theme_activation_setup() {
    if (get_option('stair_theme_setup_complete')) {
        return;
    }
    stair_create_default_pages();
    stair_create_navigation_menu();
    stair_create_default_categories();

    // set permalink structure to "Month and name"
    // /%year%/%monthnum%/%postname%/
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%postname%/');
    $wp_rewrite->flush_rules();

    update_option('stair_theme_setup_complete', true);
}
add_action('after_switch_theme', 'stair_theme_activation_setup');

/**
 * Create default pages with their templates.
 */
function stair_create_default_pages() {
    $pages = [
        [
            'title'    => 'Vorstand',
            'slug'     => 'vorstand',
            'template' => 'templates/vorstand.php',
            'content'  => 'Lerne das Team kennen, das sich für deine Interessen einsetzt und STAIR am Laufen hält.',
        ],
        [
            'title'    => 'Docker Bar',
            'slug'     => 'docker-bar',
            'template' => 'templates/docker-bar.php',
        ],
        [
            'title'    => 'Galerie',
            'slug'     => 'galerie',
            'template' => 'templates/galerie.php',
            'content'  => 'Eindrücke von unseren Events und Aktivitäten.',
        ],
        [
            'title'    => 'Kontakt',
            'slug'     => 'kontakt',
            'template' => 'templates/kontakt.php',
            'content'  => 'Hast du Fragen, Anregungen oder möchtest du dich bei uns engagieren?
                Wir freuen uns auf deine Nachricht!',
        ],
        [
            'title'    => 'Merch',
            'slug'     => 'merch',
            'template' => 'templates/merch.php',
            'content'  => 'STAIR Merchandise - Zeig deinen Spirit!',
        ],
        [
            'title'    => 'Bewerbung',
            'slug'     => 'bewerbung',
            'template' => 'templates/bewerbung.php',
        ],
        [
            'title'    => 'Bewerbung Docker Bar',
            'slug'     => 'docker-bar-application',
            'template' => 'templates/docker-bar-application.php',
            'content'  => '<p>Die Docker Bar wird von Studenten und Mitarbeitern der HSLU geführt. Ziel ist es, die Bar während den Unterrichtswochen zu betreiben, exklusive Feiertage.</p>
<h3>Gesucht</h3>
<p>Gesucht werden Studenten, die eine Schicht am Dienstag (nur Schicht 2), Donnerstag oder Freitag für das ganze Semester übernehmen wollen.</p>
<p><strong>Schicht 1:</strong> Jeweils von 15:00 bis 17:00<br><strong>Schicht 2:</strong> Jeweils von 17:00 bis 19:00</p>
<p>Im Normalfall wird jeweils nur 1 Schicht pro Person verteilt, sodass möglichst vielen Studenten eine Möglichkeit angeboten werden kann.</p>
<h3>Wir bieten</h3>
<p>– 18 CHF Stundenlohn<br>– 2 gratis Getränke zur Konsumation pro Schicht<br>– viel Freiheit beim Arbeiten</p>

<hr>

<h1>We want you!</h1>
<p>The Docker Bar is run by HSLU students and staff. The aim is to run the bar during the lecture weeks, excluding public holidays.</p>
<h3>Wanted</h3>
<p>We are looking for students who would like to take on a shift on Tuesday, Thursday or Friday for the whole semester.</p>
<p><strong>Shift 1:</strong> from 15:00 until 17:00<br><strong>Shift 2:</strong> from 17:00 until 19:00</p>
<p>Normally, only 1 shift per person is distributed so that as many students as possible can be offered an opportunity.</p>
<h3>We offer</h3>
<p>– 18 CHF hourly pay<br>– 2 free drinks per shift<br>– lots of freedom while working</p>',
        ],
        [
            'title'    => 'Ahnengalerie',
            'slug'     => 'ahnengalerie',
            'template' => 'templates/ahnengalerie.php',
            'content'  => 'Die ehemaligen Mitglieder, die den Verein geprägt haben.',
        ],
        [
            'title'    => 'Partner',
            'slug'     => 'partner',
            'template' => 'templates/partners.php',
        ],
        [
            'title'    => 'Vergangene Events',
            'slug'     => 'vergangene-events',
            'template' => 'templates/past-events.php',
            'content'  => 'Schau dir unsere vergangenen Veranstaltungen an und erlebe, was wir bereits erreicht haben.',
        ],
        [
            'title'    => 'Hoppla! Da hat wohl jemand einen Tritt verpasst!',
            'slug'     => '404',
            'template' => 'templates/404.php',
            'content'  => 'Es scheint, als könnten wir die Seite nicht finden, wonach du suchst. <br><br><a href="/" class="inline-block px-8 py-3.5 bg-primary text-white rounded no-underline font-semibold transition-all duration-300 hover:bg-[#094d42] hover:-translate-y-0.5 hover:shadow-lg">Zur Startseite</a>',
        ],
    ];

    foreach ($pages as $page_data) {
        $existing_page = get_page_by_path($page_data['slug']);

        if (!$existing_page) {
            $page_id = wp_insert_post([
                'post_title'   => $page_data['title'],
                'post_name'    => $page_data['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => isset($page_data['content']) ? $page_data['content'] : '',
            ]);

            // assign the page template
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

/**
 * Create navigation menu with all pages.
 */
function stair_create_navigation_menu() {
    $menu_name = 'Main Menu';

    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        if (!is_wp_error($menu_id)) {
            $menu_items = [
                'vorstand',
                'events',
                'docker-bar',
                'galerie',
                'kontakt',
                'merch',
            ];

            $position = 0;
            foreach ($menu_items as $slug) {
                if ($slug === 'events') {
                    // Add Events Post Type Archive
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => 'Events',
                        'menu-item-object' => 'tribe_events',
                        'menu-item-type' => 'post_type_archive',
                        'menu-item-status' => 'publish',
                        'menu-item-position' => $position,
                    ]);
                    $position++;
                    continue;
                }

                $page = get_page_by_path($slug);

                if ($page) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title'     => $page->post_title,
                        'menu-item-object'    => 'page',
                        'menu-item-object-id' => $page->ID,
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                        'menu-item-position'  => $position,
                    ]);
                    $position++;
                }
            }

            $locations = get_theme_mod('nav_menu_locations');
            $locations['main_menu'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}

/**
 * Create default categories.
 */
function stair_create_default_categories() {
    $categories = [
        'News',
        'Galerie',
    ];

    foreach ($categories as $cat_name) {
        if (!term_exists($cat_name, 'category')) {
            wp_insert_term(
                $cat_name,
                'category',
            );
        }
    }
}

/**
 * Create Default Contact Forms (Contact Form 7).
 */
function stair_create_default_contact_forms() {
    if (get_option('stair_contact_forms_created')) {
        return;
    }

    if (!post_type_exists('wpcf7_contact_form')) {
        return;
    }

    $forms = [
        'Kontakt' => [
            'title' => 'Kontakt',
            'content' =>
'<label> Name
    [text* your-name autocomplete:name] </label>

<label> E-Mail-Adresse
    [email* your-email autocomplete:email] </label>

<label> Betreff
    [text* your-subject] </label>

<label> Deine Nachricht
    [textarea* your-message] </label>

[turnstile]
[submit "Senden"]',
            // Simple mail template
            'mail' => [
                'subject' => 'STAIR Kontakt: [your-subject]',
                'sender' => '[your-name] <[your-email]>',
                'body' => "Von: [your-name] <[your-email]>\nBetreff: [your-subject]\n\nNachricht:\n[your-message]\n\n--\nDiese E-Mail wurde gesendet von einem Kontaktformular auf STAIR (site_url)",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
            ],
        ],
        'Bewerbung' => [
            'title' => 'Bewerbung',
            'content' =>
'<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1.5">
            <label>Name</label>
            [text* your-name placeholder "Dein Name"]
        </div>
        <div class="space-y-1.5">
            <label>E-Mail-Adresse</label>
            [email* your-email placeholder "name@beispiel.de"]
        </div>
    </div>

    <div class="space-y-1.5">
        <label>Position</label>
        <div class="relative">
            [select* position class:w-full "Bitte wählen...|Position" "Event" "PR" "Infrastruktur"]
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1.5">
            <label>Semester</label>
            [text* semester placeholder "z.B. 4"]
        </div>
        <div class="space-y-1.5">
            <label>Studiengang</label>
            <div class="relative">
                [select* studiengang class:w-full "Bitte wählen...|Studiengang" "Artificial Intelligence & Machine Learning" "Digital Ideation" "Immersive Technologies" "Informatik" "Information & Cyber Security" "International IT Management" "Wirtschaftsinformatik" "Anderes"]
            </div>
        </div>
    </div>

    <div class="space-y-3">
        <label class="font-semibold text-gray-800 ml-1 normal-case! tracking-normal! text-sm! mb-0!">Modus</label>
        [radio modus use_label_element default:1 "Vollzeit" "Teilzeit"]
    </div>

    <div class="space-y-1.5">
        <label>Nachricht</label>
        [textarea* your-message x4 placeholder "Erzähl uns etwas über dich..."]
    </div>

    <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-6">
        
        [turnstile]

        [submit "Bewirb dich jetzt!"]
    </div>

</div>',
            'mail' => [
                'subject' => 'STAIR Bewerbung: [your-name]',
                'sender' => '[your-name] <[your-email]>',
                'body' => "Neue Bewerbung eingegangen.\n\nName: [your-name]\nE-Mail: [your-email]\nStudiengang: [your-studiengang]\n\nNachricht:\n[your-message]\n\n--\n(Lebenslauf befindet sich im Anhang)",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
                'attachments' => '[your-cv]',
            ],
        ],
        'Docker Bar Application' => [
            'title' => 'Docker Bar Application',
            'content' =>
'<div class="space-y-6">
    
    <div class="space-y-1.5">
        <label>Name</label>
        [text* your-name placeholder "Name"]
    </div>

    <div class="space-y-1.5">
        <label>HSLU Email</label>
        [email* your-email placeholder "HSLU Email"]
    </div>

    <div class="space-y-1.5">
        <label>Telefon-Nr</label>
        [text* your-phone placeholder "Telefon-Nr"]
    </div>

    <div class="space-y-3">
        <label class="font-semibold text-gray-800 ml-1 normal-case! tracking-normal! text-sm! mb-0!">Schicht / Shift</label>
        [checkbox* schicht use_label_element class:grid class:grid-cols-1 class:md:grid-cols-2 class:gap-x-6 class:gap-y-2 "Dienstag / Tuesday 15:00 - 17:00" "Dienstag / Tuesday 17:00 - 19:00" "Donnerstag / Thursday 15:00 - 17:00" "Donnerstag / Thursday 17:00 - 19:00" "Freitag / Friday 15:00 - 17:00" "Freitag / Friday 17:00 - 19:00"]
    </div>

    <div class="space-y-1.5">
        <label>Nachricht</label>
        [textarea* your-message x4 placeholder "Nachricht"]
    </div>

    <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-6">
        
        [turnstile]

        [submit "Bewirb dich jetzt"]
    </div>

</div>',
            'mail' => [
                'subject' => 'Docker Bar Bewerbung: [your-name]',
                'sender' => '[your-name] <[your-email]>',
                'body' => "Neue Bewerbung für die Docker Bar eingegangen.\n\nName: [your-name]\nE-Mail: [your-email]\nTelefon: [your-phone]\n\nSchichten:\n[schicht]\n\nNachricht:\n[your-message]",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
            ],
        ],
    ];

    foreach ($forms as $key => $form_data) {
        // check if form with this title already exists
        $existing_form = get_page_by_title($form_data['title'], OBJECT, 'wpcf7_contact_form');

        if (!$existing_form) {
            $form_id = wp_insert_post([
                'post_title'   => $form_data['title'],
                'post_type'    => 'wpcf7_contact_form',
                'post_status'  => 'publish',
                'post_content' => $form_data['content'],
            ]);

            if ($form_id && !is_wp_error($form_id)) {
                // set default meta for mail if possible, though CF7 handles this via special meta keys usually.
                // CF7 stores form properties in '_form', '_mail', '_mail_2', '_messages', '_additional_settings', '_locale'

                update_post_meta($form_id, '_form', $form_data['content']);
                update_post_meta($form_id, '_mail', $form_data['mail']);
                // default messages
                update_post_meta($form_id, '_messages', ['mail_sent_ok' => 'Vielen Dank für deine Nachricht. Sie wurde versendet.']);
            }
        }
    }

    // mark as complete so we don't run this check every time
    update_option('stair_contact_forms_created', true);
}
add_action('admin_init', 'stair_create_default_contact_forms');
