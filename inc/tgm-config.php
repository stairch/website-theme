<?php

declare(strict_types=1);
/**
 * TGM Plugin Activation Configuration
 *
 * This file registers required plugins for the STAIR theme.
 *
 * @package STAIR
 */

/**
 * Register the required plugins for this theme.
 */
function stair_register_required_plugins()
{
    $plugins = [
        [
            'name'     => 'The Events Calendar',
            'slug'     => 'the-events-calendar',
            'required' => true,
        ],
        [
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => true,
        ],
        [
            'name'     => 'Advanced Custom Fields',
            'slug'     => 'advanced-custom-fields',
            'required' => true,
        ],
        [
            'name'     => 'FluentSMTP',
            'slug'     => 'fluent-smtp',
            'required' => true,
        ],
    ];

    $config = [
        'id'           => 'stair',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => [
            'page_title'                      => __('Install Required Plugins', 'stair'),
            'menu_title'                      => __('Install Plugins', 'stair'),
            'installing'                      => __('Installing Plugin: %s', 'stair'),
            'updating'                        => __('Updating Plugin: %s', 'stair'),
            'oops'                            => __('Something went wrong with the plugin API.', 'stair'),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'stair'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'stair'
            ),
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'stair'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'stair'
            ),
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'stair'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'stair'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'stair'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'stair'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'stair'
            ),
            'return'                          => __('Return to Required Plugins Installer', 'stair'),
            'plugin_activated'                => __('Plugin activated successfully.', 'stair'),
            'activated_successfully'          => __('The following plugin was activated successfully:', 'stair'),
            'plugin_already_active'           => __('No action taken. Plugin %1$s was already active.', 'stair'),
            'plugin_needs_higher_version'     => __('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'stair'),
            'complete'                        => __('All plugins installed and activated successfully. %1$s', 'stair'),
            'dismiss'                         => __('Dismiss this notice', 'stair'),
            'notice_cannot_install_activate'  => __('There are one or more required or recommended plugins to install, update or activate.', 'stair'),
            'contact_admin'                   => __('Please contact the administrator of this site for help.', 'stair'),
            'nag_type'                        => '',
        ],
    ];

    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'stair_register_required_plugins');
