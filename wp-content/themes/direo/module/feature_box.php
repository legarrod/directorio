<?php
/*==========================================
    Element Name: Feature Box
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'feature_box' => array(
                'name' => esc_html__('Feature Box', 'direo'),
                'icon' => 'kc-icon-feature-box',
                'category' => 'Content',
                'priority' => 132,
                'description' => esc_html__('Display feature boxes styles.', 'direo'),
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'type',
                            'type' => 'radio',
                            'label' => esc_html__('Type', 'direo'),
                            'options' => array(
                                'icon' => esc_html__('Icon Type', 'direo'),
                                'number' => esc_html__('Number Type', 'direo'),
                            ),
                            'value' => 'icon'
                        ),
                        array(
                            'name' => 'feature_style',
                            'type' => 'radio',
                            'label' => esc_html__('Chose Style', 'direo'),
                            'options' => array(
                                'kc-feature-boxes feature-list-wrapper' => esc_html__('Style 1', 'direo'),
                                'feature-box-wrapper' => esc_html__('Style 2', 'direo'),
                            ),
                            'value' => 'kc-feature-boxes feature-list-wrapper',
                            'relation' => array(
                                'parent' => 'type',
                                'show_when' => 'icon',
                            ),
                        ),
                        array(
                            'name' => 'icon',
                            'label' => esc_html__('Select Icon', 'direo'),
                            'type' => 'icon_picker',
                            'description' => esc_html__('Select icon display in box', 'direo'),
                            'value' => 'la la-check-circle',
                            'relation' => array(
                                'parent' => 'type',
                                'show_when' => 'icon',
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'title',
                            'label' => esc_html__('Title', 'direo'),
                            'admin_label' => true
                        ),
                        array(
                            'type' => 'textarea',
                            'name' => 'desc',
                            'label' => esc_html__('Description', 'direo'),
                        ),
                        array(
                            'name' => 'number',
                            'label' => esc_html__('Number', 'direo'),
                            'type' => 'text',
                            'description' => esc_html__('Insert feature number.', 'direo'),
                            'relation' => array(
                                'parent' => 'type',
                                'show_when' => 'number',
                            ),
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
                                    "screens" => "any,1024,999,767,479",
                                    'Icon' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => 'span'),
                                        array('property' => 'background-color', 'label' => 'Background Color', 'selector' => 'span'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => 'span'),
                                        array('property' => 'border', 'label' => 'Border', 'selector' => 'span'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => 'li'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'li')
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