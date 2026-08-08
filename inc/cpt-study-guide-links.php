<?php

/**
 * Register 'Useful Link' Custom Post Type and ACF Fields
 *
 * @package STAIR
 */

if (!defined('ABSPATH')) {
    exit;
}

function stair_register_cpt_useful_link() {
    $labels = [
        'name'               => 'Nützliche Links',
        'singular_name'      => 'Nützlicher Link',
        'menu_name'          => 'Study Guide - Nützliche Links',
        'name_admin_bar'     => 'Nützlicher Link',
        'add_new'            => 'Neu hinzufügen',
        'add_new_item'       => 'Neuen Link hinzufügen',
        'new_item'           => 'Neuer Link',
        'edit_item'          => 'Link bearbeiten',
        'view_item'          => 'Link ansehen',
        'all_items'          => 'Alle Links',
        'search_items'       => 'Links suchen',
        'not_found'          => 'Keine Links gefunden.',
        'not_found_in_trash' => 'Keine Links im Papierkorb.',
    ];

    $args = [
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => ['slug' => 'useful-link'],
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-admin-links',
        'supports'            => ['title'],
    ];

    register_post_type('useful_link', $args);
}
add_action('init', 'stair_register_cpt_useful_link');

function stair_register_useful_link_acf() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_useful_link',
        'title'  => 'Link Details',
        'fields' => [
            [
                'key'          => 'field_link_url',
                'label'        => 'URL',
                'name'         => 'link_url',
                'type'         => 'url',
                'required'     => 1,
                'instructions' => 'Vollständige URL inkl. https://',
            ],
            [
                'key'          => 'field_link_description',
                'label'        => 'Beschreibung',
                'name'         => 'link_description',
                'type'         => 'text',
                'instructions' => 'Kurze Beschreibung, die unter dem Titel erscheint.',
            ],
            [
                'key'           => 'field_link_category',
                'label'         => 'Kategorie',
                'name'          => 'link_category',
                'type'          => 'select',
                'required'      => 1,
                'choices'       => [
                    'module'   => 'Module/Einschreibungen',
                    'alltag'   => 'Studien Alltag',
                    'software' => 'Software',
                    'hardware' => 'Hardware',
                    'partner'  => 'Partner',
                ],
                'default_value' => 'alltag',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_link_order',
                'label'         => 'Reihenfolge',
                'name'          => 'link_order',
                'type'          => 'number',
                'default_value' => 10,
                'min'           => 0,
                'instructions'  => 'Niedrigere Zahl = weiter oben. Standard: 10.',
                'wrapper'       => ['width' => '50'],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'useful_link',
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
}
add_action('acf/init', 'stair_register_useful_link_acf');
