<?php

/*==========================================
    Element Name: Subscribe
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'subscribe' => array(
                'name' => esc_html__('Mailchimp Newsletter', 'direo'),
                'description' => esc_html__('Display mailchimp subscribe form.', 'direo'),
                'icon' => 'fa fa-paper-plane',
                'category' => 'Content',
                'priority' => 129,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'text',
                            'name' => 'title',
                            'label' => esc_html__('Title', 'direo'),
                            'admin_label' => true,
                        ),
                        array(
                            'name' => 'subtitle',
                            'type' => 'text',
                            'label' => esc_html__('Subtitle', 'direo'),
                            'relation' => array(
                                'parent' => 'template',
                                'hide_when' => array('4', '6')
                            ),
                        ),
                        array(
                            'name' => 'btn',
                            'type' => 'text',
                            'label' => esc_html__('Subscribe Button Text', 'direo'),
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'action',
                            'label' => esc_html__('Mailchimp Form Action Url', 'direo'),
                            'description' => function_exists('mail_desc') ? mail_desc() : '',
                        ),
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    'Box' => array(
                                        array('property' => 'background'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                        array('property' => 'margin', 'label' => 'Margin',),
                                    )
                                )
                            )
                        )
                    ),
                )
            ),
        )
    );
}


