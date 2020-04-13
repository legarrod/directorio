<?php
/*==========================================
    Element Name: Need All Locations
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'need-locations' => array(
                'name' => esc_html__('Need Locations', 'direo'),
                'description' => esc_html__(' Display your all need Locations.', 'direo'),
                'icon' => 'fa fa-map-marker',
                'category' => 'Need',
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
                            )
                        ),
                        array(
                            'name' => 'number_loc',
                            'type' => 'number_slider',
                            'label' => esc_html__('Locations Per Page', 'direo'),
                            'description' => esc_html__('The number of locations you want to show.', 'direo'),
                            'value' => '4',
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 1000
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
                                'count' => esc_html__('need Count', 'direo'),
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
                        array(
                            'name' => 'user',
                            'label' => esc_html__('Show Only For Logged In User?', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'redirect',
                            'label' => esc_html__('Add Redirect User?', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'type' => 'link',
                            'label' => esc_html__('Redirect Link', 'direo'),
                            'name' => 'link',
                            'description' => esc_html__('Add your relative URL. Each URL contains link, anchor text and target attributes.', 'direo'),
                            'relation' => array(
                                'parent' => 'redirect',
                                'show_when' => 'yes'
                            )
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