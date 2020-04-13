<?php
/*==========================================
    Element Name: Need Single Locations
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'need-single-loc' => array(
                'name' => esc_html__('Needs Single Location', 'direo'),
                'description' => esc_html__('Display your needs by location.', 'direo'),
                'icon' => 'fa fa-map-marker',
                'category' => 'Need',
                'priority' => 116,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'avatar',
                            'label' => esc_html__('Show Author Avatar?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'category',
                            'label' => esc_html__('Show Category?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'budget',
                            'label' => esc_html__('Show Budget Amount?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'columns',
                            'label' => esc_html__('Needs Per Row', 'direo'),
                            'type' => 'select',
                            'value' => '3',
                            'options' => array(
                                '5' => esc_html__('5 Items / Row', 'direo'),
                                '4' => esc_html__('4 Items / Row', 'direo'),
                                '3' => esc_html__('3 Items / Row', 'direo'),
                                '2' => esc_html__('2 Items / Row', 'direo'),
                            ),
                            'admin_label' => true,
                        ),
                        array(
                            'name' => 'number',
                            'type' => 'number_slider',
                            'label' => esc_html__('Needs Per Page', 'direo'),
                            'description' => esc_html__('The number of needs you want to show. Set -1 for all needs', 'direo'),
                            'value' => '3',
                            'admin_label' => true,
                            'options' => array(
                                'min' => -1,
                                'max' => 1000
                            )
                        ),
                        array(
                            'name' => 'pagination',
                            'label' => esc_html__('Show Pagination', 'direo'),
                            'type' => 'toggle',
                            'value' => 'yes',
                        ),
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
                                    'Box' => array(
                                        array('property' => 'margin', 'label' => 'Margin'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                    ),
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
            )
        )
    );
}