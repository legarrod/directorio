<?php
/*==========================================
    Element Name: Testimonial Carousel
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'testimonial' => array(
                'name' => esc_html__('Testimonial Carousel', 'direo'),
                'description' => esc_html__('Display clients testimonial in carousel.', 'direo'),
                'icon' => 'kc-icon-testi',
                'category' => 'Content',
                'priority' => 440,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'group',
                            'label' => esc_html__('Add Clients Testimonial', 'direo'),
                            'name' => 'testimonials',
                            'description' => esc_html__('Add, remove, edit or reorder your testimonial items.', 'direo'),
                            'options' => array('add_text' => esc_html__('Add New', 'direo')),
                            'params' => array(
                                array(
                                    'type' => 'text',
                                    'name' => 'name',
                                    'label' => esc_html__('Client Name', 'direo'),
                                    'value' => 'Text Title',
                                    'admin_label' => true
                                ),
                                array(
                                    'name' => 'position',
                                    'label' => esc_html__('Position', 'direo'),
                                    'type' => 'text',
                                ),
                                array(
                                    'type' => 'textarea',
                                    'name' => 'desc',
                                    'label' => esc_html__('Description', 'direo'),
                                ),
                                array(
                                    'name' => 'image',
                                    'label' => esc_html__('Upload Image', 'direo'),
                                    'type' => 'attach_image',
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