<?php
/*==========================================
    Element Name: Search
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'search' => array(
                'name' => esc_html__('Search Form', 'direo'),
                'icon' => 'fas fa-search-plus',
                'category' => 'Direo',
                'priority' => 112,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'border',
                            'label' => esc_html__('Show Element Border?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'text_field',
                            'label' => esc_html__('Show Text Field?', 'direo'),
                            'type' => 'toggle',
                            'value' => 'yes',
                            'admin_label' => true
                        ),
                        array(
                            'name' => 'category_field',
                            'label' => esc_html__('Show Category Field?', 'direo'),
                            'type' => 'toggle',
                            'value' => 'yes',
                            'admin_label' => true
                        ),

                        array(
                            'name' => 'location_field',
                            'label' => esc_html__('Show Location Field?', 'direo'),
                            'type' => 'toggle',
                            'value' => 'yes',
                            'admin_label' => true
                        ),

                        array(
                            'name' => 'advance',
                            'label' => esc_html__('Advance Search Field?', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'popular',
                            'label' => esc_html__('Show Popular Category?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'popular_count',
                            'type' => 'number_slider',
                            'label' => esc_html__('Number of Popular Categories', 'direo'),
                            'value' => 4,
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 1000
                            ),
                            'relation' => array(
                                'parent' => 'popular',
                                'show_when' => 'yes'
                            )
                        ),
                    ),
                ),
            )
        )
    );
}