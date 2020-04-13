<?php
/*==========================================
    Element Name: Contact Information
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'contact_info' => array(
                'name' => esc_html__('Contact Information', 'direo'),
                'description' => esc_html__('Display Contact Information.', 'direo'),
                'icon' => 'fa-info-circle',
                'category' => 'Content',
                'priority' => 131,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'text',
                            'name' => 'title',
                            'label' => esc_html__('Section Title', 'direo'),
                            'admin_label' => true
                        ),
                        array(
                            'type' => 'group',
                            'label' => esc_html__('Add Contact Information', 'direo'),
                            'name' => 'addresses',
                            'description' => esc_html__('Add, remove, edit or reorder your address items.', 'direo'),
                            'options' => array('add_text' => esc_html__('Add New', 'direo')),

                            'params' => array(
                                array(
                                    'type' => 'text',
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'direo'),
                                    'admin_label' => true
                                ),
                                array(
                                    'name' => 'icon',
                                    'label' => esc_html__('Icon', 'direo'),
                                    'type' => 'icon_picker',
                                ),
                            )
                        ),
                        array(
                            'type' => 'group',
                            'label' => esc_html__('Add Social Icons', 'direo'),
                            'name' => 'socials',
                            'description' => esc_html__('Add, remove, edit or reorder your socials items.', 'direo'),
                            'options' => array('add_text' => esc_html__('Add New', 'direo')),

                            'params' => array(
                                array(
                                    'name' => 'icon',
                                    'label' => esc_html__('Icon', 'direo'),
                                    'type' => 'icon_picker',
                                ),
                                array(
                                    'type' => 'text',
                                    'name' => 'url',
                                    'label' => esc_html__('Social Url', 'direo'),
                                    'admin_label' => true
                                ),
                            )
                        ),

                        array(
                            'name' => 'class',
                            'type' => 'text',
                            'label' => esc_html__('Custom class', 'direo'),
                            'description' => esc_html__('Enter extra custom class', 'direo')
                        )
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    'Boxes' => array(
                                        array('property' => 'background'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                        array('property' => 'margin', 'label' => 'Margin'),
                                    )
                                )
                            )
                        )
                    ),
                    'animate' => array(
                        array(
                            'name' => 'animate',
                            'type' => 'animate'
                        )
                    ),
                )
            ),
        )
    );
}