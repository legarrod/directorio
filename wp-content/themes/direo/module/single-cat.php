<?php
/*==========================================
    Element Name: Listing
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'single-cat' => array(
                'name' => esc_html__('Single Category', 'direo'),
                'icon' => 'fa fa-th-list',
                'category' => 'Direo',
                'priority' => 118,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'header',
                            'label' => esc_html__('Show Header?', 'direo'),
                            'description' => esc_html__('Display header on section topper.', 'direo'),
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Listings Found Title', 'direo'),
                            'type' => 'text',
                            'relation' => array(
                                'parent' => 'header',
                                'show_when' => 'yes'
                            ),
                            'value' => ' :Listings Found'
                        ),
                        array(
                            'name' => 'filter',
                            'label' => esc_html__('Show Filter Button?', 'direo'),
                            'description' => esc_html__('Display "Advance Search Filter Button" on section header.', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                            'relation' => array(
                                'parent' => 'header',
                                'show_when' => 'yes'
                            )
                        ),
                        array(
                            'name' => 'sidebar',
                            'label' => esc_html__('Show Sidebar?', 'direo'),
                            'description' => esc_html__('Display "All Listing Sidebar" on the left column.', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'user',
                            'label' => esc_html__('Show Only For Logged In User?', 'direo'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'redirect',
                            'label' => esc_html__('Redirect Page?', 'direo'),
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

                        array(
                            'name' => 'layout',
                            'type' => 'dropdown',
                            'label' => esc_html__('View As', 'direo'),
                            'admin_label' => true,
                            'options' => array(
                                'grid' => esc_html__('Grid View', 'direo'),
                                'list' => esc_html__('List View', 'direo'),
                                'map' => esc_html__('Map View', 'direo'),
                            ),
                            'value' => 'grid'
                        ),
                        array(
                            'name' => 'row',
                            'label' => esc_html__('Listings Per Row', 'direo'),
                            'type' => 'select',
                            'value' => '3',
                            'options' => array(
                                '5' => esc_html__('5 Items / Row', 'direo'),
                                '4' => esc_html__('4 Items / Row', 'direo'),
                                '3' => esc_html__('3 Items / Row', 'direo'),
                                '2' => esc_html__('2 Items / Row', 'direo'),
                                '1' => esc_html__('1 Items / Row', 'direo')
                            ),
                            'relation' => array(
                                'parent' => 'layout',
                                'show_when' => 'grid'
                            )
                        ),
                        array(
                            'name' => 'preview',
                            'label' => esc_html__('Show Preview Image?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                            'relation' => array(
                                'parent' => 'layout',
                                'hide_when' => 'map'
                            )
                        ),
                        array(
                            'name' => 'map_height',
                            'type' => 'number_slider',
                            'label' => esc_html__('Map Height', 'direo'),
                            'value' => 500,
                            'admin_label' => true,
                            'options' => array(
                                'min' => 300,
                                'max' => 1980
                            ),
                            'relation' => array(
                                'parent' => 'layout',
                                'show_when' => 'map'
                            )
                        ),
                        array(
                            'name' => 'number_cat',
                            'type' => 'number_slider',
                            'label' => esc_html__('Listings Per Page', 'direo'),
                            'value' => '6',
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 1000
                            )
                        ),
                        array(
                            'name' => 'cat',
                            'label' => esc_html__('Select Categories', 'direo'),
                            'type' => 'multiple',
                            'options' => class_exists('Directorist_Base') ? direo_listing_category() : [],
                        ),
                        array(
                            'name' => 'tag',
                            'label' => esc_html__('Select Tags', 'direo'),
                            'type' => 'multiple',
                            'options' => class_exists('Directorist_Base') ? direo_listing_tags() : [],
                        ),
                        array(
                            'name' => 'location',
                            'label' => esc_html__('Select Locations', 'direo'),
                            'description' => esc_html__('The locations of listing posts you want to show.', 'direo'),
                            'type' => 'multiple',
                            'options' => class_exists('Directorist_Base') ? direo_listing_locations() : [],
                        ),
                        array(
                            'name' => 'featured',
                            'label' => esc_html__('Show Featured Only?', 'direo'),
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'popular',
                            'label' => esc_html__('Show Popular Only?', 'direo'),
                            'type' => 'toggle',
                        ),

                        array(
                            'name' => 'order_by',
                            'type' => 'dropdown',
                            'label' => esc_html__('Order by', 'direo'),
                            'admin_label' => true,
                            'options' => array(
                                'title' => esc_html__(' Title', 'direo'),
                                'date' => esc_html__(' Date', 'direo'),
                                'price' => esc_html__(' Price', 'direo'),
                            ),
                            'value' => 'date',
                            'relation' => array(
                                'parent' => 'layout',
                                'hide_when' => 'map'
                            )
                        ),

                        array(
                            'name' => 'order_list',
                            'type' => 'dropdown',
                            'label' => esc_html__('Listings Order', 'direo'),
                            'admin_label' => true,
                            'options' => array(
                                'asc' => esc_html__(' ASC', 'direo'),
                                'desc' => esc_html__(' DESC', 'direo'),
                            ),
                            'value' => 'desc',
                            'relation' => array(
                                'parent' => 'layout',
                                'hide_when' => 'map'
                            )
                        ),
                        array(
                            'name' => 'show_pagination',
                            'label' => esc_html__('Show Pagination', 'direo'),
                            'type' => 'toggle',
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