<?php
/*==========================================
    Element Name: Listing Locations
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'location' => array(
                'name' => esc_html__(' Listing Locations', 'direo'),
                'description' => esc_html__(' Display your all listing Locations.', 'direo'),
                'icon' => 'la la-map-marker',
                'category' => 'Direo',
                'priority' => 116,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'layout',
                            'type' => 'dropdown',
                            'label' => esc_html__('Locations Layout', 'direo'),
                            'admin_label' => true,
                            'options' => array(
                                'grid' => esc_html__('Grid View', 'direo'),
                                'list' => esc_html__('List View', 'direo'),
                            ),
                            'value' => 'grid',
                        ),
                        array(
                            'name' => 'row',
                            'label' => esc_html__('Locations Per Row', 'direo'),
                            'type' => 'select',
                            'value' => '2',
                            'options' => array(
                                '5' => esc_html__('5 Items / Row', 'direo'),
                                '4' => esc_html__('4 Items / Row', 'direo'),
                                '3' => esc_html__('3 Items / Row', 'direo'),
                                '2' => esc_html__('2 Items / Row', 'direo'),
                                '1' => esc_html__('1 Items / Row', 'direo')
                            )
                        ),
                        array(
                            'name' => 'number_loc',
                            'type' => 'number_slider',
                            'label' => esc_html__('Locations Per Page', 'direo'),
                            'value' => '4',
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 100
                            )
                        ),
                        array(
                            'name' => 'slug',
                            'label' => esc_html__('Select Locations', 'direo'),
                            'type' => 'multiple',
                            'options' => class_exists('Directorist_Base') ? direo_listing_locations() : [],
                        ),
                        array(
                            'type' => 'dropdown',
                            'label' => esc_html__('Order by', 'direo'),
                            'name' => 'order_by',
                            'admin_label' => true,
                            'options' => array(
                                'id' => esc_html__('Location ID', 'direo'),
                                'count' => esc_html__('Listing Count', 'direo'),
                                'name' => esc_html__(' Location name (A-Z)', 'direo'),
                            ),
                            'value' => 'id'
                        ),
                        array(
                            'type' => 'dropdown',
                            'label' => esc_html__('Locations Order', 'direo'),
                            'name' => 'order_list',
                            'admin_label' => true,
                            'options' => array(
                                'asc' => esc_html__(' ASC', 'direo'),
                                'desc' => esc_html__(' DESC', 'direo'),
                            ),
                            'value' => 'desc'
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
                                        array('property' => 'border', 'label' => 'Border'),
                                        array('property' => 'width', 'label' => 'Width'),
                                        array('property' => 'height', 'label' => 'Height'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius'),
                                        array('property' => 'float', 'label' => 'Float'),
                                        array('property' => 'display', 'label' => 'Display'),
                                        array('property' => 'box-shadow', 'label' => 'Box Shadow'),
                                        array('property' => 'opacity', 'label' => 'Opacity'),
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