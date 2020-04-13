<?php
require_once('class-tgm-plugin-activation.php');

add_action('tgmpa_register', 'direo_register_required_plugins');

function direo_register_required_plugins()
{

    $plugins = array(
        array(
            'name' => esc_html__('Direo Core', 'direo'),
            'slug' => 'direo-core',
            'source' => get_template_directory() . '/lib/plugins/direo-core.zip',
            'required' => true,
            'version' => '1.8.0',
        ),
        array(
            'name' => esc_html__('Direo Extension', 'direo'),
            'slug' => 'direo-extension',
            'source' => get_template_directory() . '/lib/plugins/direo-extension.zip',
            'required' => true,
            'version' => '1.6.0',
        ),
        array(
            'name' => esc_html__('Directorist – Business Directory Plugin', 'direo'),
            'slug' => 'directorist',
            'required' => true,
        ),
        array(
            'name' => esc_html__('Kirki', 'direo'),
            'slug' => 'kirki',
            'required' => true,
        ),
        array(
            'name' => esc_html__('Elementor', 'direo'),
            'slug' => 'elementor',
            'required' => false,
        ),
        array(
            'name' => esc_html__('KingComposer', 'direo'),
            'slug' => 'kingcomposer',
            'required' => false,
        ),
        array(
            'name' => esc_html__('One click demo import', 'direo'),
            'slug' => 'one-click-demo-import',
            'required' => false,
        ),
        array(
            'name' => esc_html__('Contact form - 7', 'direo'),
            'slug' => 'contact-form-7',
            'required' => false,
        ),
    );

    $config = array(
        'id' => 'direo',
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'has_notices' => true, 'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => false,
        'message' => '',
    );

    tgmpa($plugins, $config);
}