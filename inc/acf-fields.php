<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function stair_register_acf_fields() {

    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // docker bar fields
    acf_add_local_field_group([
        'key' => 'group_docker_bar',
        'title' => 'Docker Bar Einstellungen',
        'fields' => [
            [
                'key' => 'field_docker_bar_subtitle',
                'label' => 'Untertitel',
                'name' => 'docker_bar_subtitle',
                'type' => 'text',
                'default_value' => 'Dein Treffpunkt an der HSLU',
                'instructions' => 'Kurzer Untertitel unter dem Seitentitel.',
            ],
            [
                'key' => 'field_docker_bar_description',
                'label' => 'Beschreibung',
                'name' => 'docker_bar_description',
                'type' => 'textarea',
                'rows' => 3,
                'default_value' => 'Die Docker Bar wird von Student:innen und Mitarbeiter:innen der HSLU geführt. Unser Ziel ist es, die Bar während den Unterrichtswochen zu betreiben, exklusive Feiertage.',
                'instructions' => 'Hauptbeschreibung der Docker Bar.',
            ],
            [
                'key' => 'field_docker_bar_menu_pdf',
                'label' => 'Getränkekarte (PDF)',
                'name' => 'menu_pdf',
                'type' => 'file',
                'return_format' => 'url',
                'mime_types' => 'pdf',
                'instructions' => 'Lade hier die aktuelle Getränkekarte als PDF hoch.',
            ],
            [
                'key' => 'field_docker_bar_application_url',
                'label' => 'Bewerbungs-Link',
                'name' => 'application_url',
                'type' => 'url',
                'default_value' => '/docker-bar-application/',
                'instructions' => 'Link zur Bewerbungsseite für Bar-Mitarbeiter.',
            ],
            // Opening hours as simple textarea (works with free ACF)
            [
                'key' => 'field_docker_bar_opening_hours_text',
                'label' => 'Öffnungszeiten',
                'name' => 'opening_hours_text',
                'type' => 'textarea',
                'rows' => 4,
                'default_value' => "Dienstag: 17:00 – 19:00\nDonnerstag: 15:00 – 19:00\nFreitag: 15:00 – 19:00",
                'instructions' => 'Eine Zeile pro Tag. Format: "Tag: Uhrzeit"',
                'new_lines' => '',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/docker-bar.php',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ]);

    // kontakt page - social links (ACF Free compatible - no repeater)
    // using individual fields for up to 5 social links
    $social_fields = [];
    for ($i = 1; $i <= 5; $i++) {
        $social_fields[] = [
            'key' => 'field_social_' . $i . '_heading',
            'label' => 'Social Link ' . $i,
            'type' => 'message',
            'message' => '',
            'wrapper' => ['class' => 'acf-social-heading'],
        ];
        $social_fields[] = [
            'key' => 'field_social_' . $i . '_url',
            'label' => 'Link',
            'name' => 'social_' . $i . '_url',
            'type' => 'url',
            'instructions' => 'Profil-URL',
            'wrapper' => ['width' => '50'],
        ];
        $social_fields[] = [
            'key' => 'field_social_' . $i . '_icon',
            'label' => 'Icon',
            'name' => 'social_' . $i . '_icon',
            'type' => 'textarea',
            'rows' => 2,
            'instructions' => 'Lucide Name, Bild-URL oder roher <svg> Code',
            'wrapper' => ['width' => '50'],
        ];
    }

    acf_add_local_field_group([
        'key' => 'group_kontakt_socials',
        'title' => 'Soziale Netzwerke',
        'fields' => $social_fields,
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/kontakt.php',
                ],
            ],
        ],
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ]);

    // Kontakt page - Location
    acf_add_local_field_group([
        'key' => 'group_kontakt_location',
        'title' => 'Standort',
        'fields' => [
            [
                'key' => 'field_location_org',
                'label' => 'Organisation',
                'name' => 'location_org',
                'type' => 'text',
                'default_value' => 'STAIR',
            ],
            [
                'key' => 'field_location_address1',
                'label' => 'Adresszeile 1',
                'name' => 'location_address1',
                'type' => 'text',
                'default_value' => 'C/O Hochschule Luzern Informatik',
            ],
            [
                'key' => 'field_location_address2',
                'label' => 'Adresszeile 2',
                'name' => 'location_address2',
                'type' => 'text',
                'default_value' => 'Suurstoffi 1',
            ],
            [
                'key' => 'field_location_city',
                'label' => 'PLZ / Ort',
                'name' => 'location_city',
                'type' => 'text',
                'default_value' => '6343 Rotkreuz',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/kontakt.php',
                ],
            ],
        ],
        'menu_order' => 2,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ]);

    // event additional options
    acf_add_local_field_group([
        'key'    => 'group_event_buttons',
        'title'  => 'Erweiterte Optionen',
        'fields' => [
            [
                'key'           => 'field_event_banner_image',
                'label'         => 'Titelbild',
                'name'          => 'event_banner_image',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'medium',
                'instructions'  => 'Wird oben oberhalb des Textes angezeigt.',
            ],
            [
                'key'          => 'field_event_primary_label',
                'label'        => 'Primary Button - Label',
                'name'         => 'event_primary_label',
                'type'         => 'text',
                'instructions' => 'z.B. «Ticket kaufen»',
                'wrapper'      => ['width' => '50'],
            ],
            [
                'key'          => 'field_event_primary_url',
                'label'        => 'Primär Button – URL',
                'name'         => 'event_primary_url',
                'type'         => 'url',
                'wrapper'      => ['width' => '50'],
            ],
            [
                'key'          => 'field_event_secondary_label',
                'label'        => 'Secondary Button - Label',
                'name'         => 'event_secondary_label',
                'type'         => 'text',
                'instructions' => 'z.B. «Anmelden»',
                'wrapper'      => ['width' => '50'],
            ],
            [
                'key'          => 'field_event_secondary_url',
                'label'        => 'Secondary Button – URL',
                'name'         => 'event_secondary_url',
                'type'         => 'url',
                'wrapper'      => ['width' => '50'],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'tribe_events',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
    ]);

    // study guide fields
    acf_add_local_field_group([
        'key'    => 'group_study_guide',
        'title'  => 'Study Guide',
        'fields' => [
            [
                'key'           => 'field_sg_cover_image',
                'label'         => 'Cover Bild',
                'name'          => 'sg_cover_image',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'medium',
                'instructions'  => 'Titelbild des Study Guides.',
            ],
            [
                'key'          => 'field_sg_download_url_de',
                'label'        => 'Download-Link (DE)',
                'name'         => 'sg_download_url_de',
                'type'         => 'url',
                'instructions' => 'Direkt-Link zur deutschen PDF-Version.',
                'wrapper'      => ['width' => '50'],
            ],
            [
                'key'          => 'field_sg_download_url_en',
                'label'        => 'Download-Link (EN)',
                'name'         => 'sg_download_url_en',
                'type'         => 'url',
                'instructions' => 'Direkt-Link zur englischen PDF-Version.',
                'wrapper'      => ['width' => '50'],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'templates/study-guide.php',
                ],
            ],
        ],
        'menu_order'          => 0,
        'position'            => 'normal',
        'style'               => 'default',
        'label_placement'     => 'top',
        'instruction_placement' => 'label',
    ]);
}
add_action('acf/init', 'stair_register_acf_fields');

/**
 * add notice if ACF is not installed on Docker Bar page
 */
function stair_acf_notice() {
    // Only show if ACF is not installed
    if (function_exists('get_field')) {
        return;
    }

    $screen = get_current_screen();

    // Check if we're on the page editor
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    $template = get_page_template_slug($post->ID);

    if ($template === 'templates/docker-bar.php') {
        ?>
        <div class="notice notice-info">
            <p>
                <strong>Tipp:</strong> Installiere das Plugin
                <a href="<?php echo esc_url(admin_url('plugin-install.php?s=Advanced+Custom+Fields&tab=search&type=term')); ?>">Advanced Custom Fields</a>
                für einfachere Bearbeitung der Docker Bar Inhalte (Öffnungszeiten, Getränkekarte, etc.).
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'stair_acf_notice');
