<?php
/*==========================================
    Element Name: Listing
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'search-result-map' => array(
                'name' => esc_html__('Search Result Map View', 'direo'),
                'description' => esc_html__('Display your search result listing with map view.', 'direo'),
                'icon' => 'fas fa-search-plus',
                'category' => 'Direo',
                'priority' => 114,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'listing_map_style',
                            'label' => esc_html__('Section Style', 'direo'),
                            'type' => 'select',
                            'value' => '3',
                            'options' => array(
                                '2' => esc_html__('2 Column', 'direo'),
                                '3' => esc_html__('3 Column', 'direo'),
                            ),
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
                            'name' => 'preview',
                            'label' => esc_html__('Show Preview Image?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
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
                        ),
                        array(
                            'name' => 'show_pagination',
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