<?php

/*==========================================
    Element Name: Contact Form
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'contact_form' => array(
                'name' => esc_html__('Contact Form 7', 'direo'),
                'icon' => 'fas fa-envelope',
                'category' => 'Content',
                'priority' => 130,
                'description' => esc_html__('Display contact form 7 form', 'direo'),
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'direo'),
                            'description' => esc_html__('Insert the module title.', 'direo'),
                            'type' => 'text',
                            'value' => esc_html__('Free Consultation', 'direo'),
                            'admin_label' => true,
                        ),
                        array(
                            'name' => 'contact_form_id',
                            'type' => 'select',
                            'label' => esc_html__('Select Contact Form', 'direo'),
                            'admin_label' => true,
                            'options' => mp_get_cf7_names(),
                            'description' => esc_html__('Choose previously created contact form from the drop down list.', 'direo')
                        ),
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
                )
            ),
        )
    );
}



