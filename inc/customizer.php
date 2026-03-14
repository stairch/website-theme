<?php

declare(strict_types=1);
/**
 * STAIR Theme Customizer
 *
 * @package STAIR
 */

function stair_customize_register($wp_customize)
{
    // Add Section for Contact Info
    $wp_customize->add_section('stair_contact_section', [
        'title'    => __('Kontakt Informationen', 'stair'),
        'priority' => 30,
    ]);

    // General Contact Email
    $wp_customize->add_setting('stair_contact_email', [
        'default'           => 'info@stair.ch',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('stair_contact_email', [
        'label'    => __('Allgemeine Kontakt E-Mail', 'stair'),
        'section'  => 'stair_contact_section',
        'type'     => 'email',
    ]);
}
add_action('customize_register', 'stair_customize_register');
