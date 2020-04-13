<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

class BDMV_Settings
{
    public function __construct()
    {
        // Add setting section to the Directorist settings page.
        add_filter('atbdp_extension_settings_submenus', array($this, 'add_settings_to_ext_submenu'));
    }

    public function add_settings_to_ext_submenu($settings_submenus)
    {
        $business_hours = '(Requires <a style="color: red" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
        /*lets add a submenu of our extension*/
        $settings_submenus[] = array(
            'title' => __('Listings with Map', 'direo-extension'),
            'name' => 'listings_with_map',
            'icon' => 'font-awesome:fa-clock-o',
            'controls' => array(
                'listings_map_settings' => array(
                    'type' => 'section',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'bdmv_listings_with_map_columns',
                            'label' => __('Columns', 'direo-extension'),
                            'items' => array(
                                array(
                                    'value' => '2',
                                    'label' => __('2', 'direo-extension'),
                                ),
                                array(
                                    'value' => '3',
                                    'label' => __('3', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => '3',
                                'label' => __('3', 'direo-extension'),
                            ),
                        ),

                    ),// ends fields array
                ), // ends general section array
                'filter_section' => array(
                    'type' => 'section',
                    'title' => __('Filter Settings', 'direo-extension'),
                    'description' => __('You can Customize Filters Settings here', 'direo-extension'),
                    'fields' => array(
                        'listings_filters_field' =>
                        array(
                            'type' => 'checkbox',
                            'name' => 'listing_map_filters',
                            'label' => __('Filter Fields', 'direo-extension'),
                            'validation' => 'minselected[0]|maxselected[15]',
                            'items' => array(
                                array(
                                    'value' => 'search_text',
                                    'label' => __('Text', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_category',
                                    'label' => __('Category', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_location',
                                    'label' => __('Location', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_price',
                                    'label' => __('Price (Min - Max)', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_price_range',
                                    'label' => __('Price Range', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_rating',
                                    'label' => __('Rating', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_tag',
                                    'label' => __('Tag', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_open_now',
                                    'label' => sprintf(__('Open Now %s', 'direo-extension'), !class_exists('BD_Business_Hour') ? $business_hours : '')),
                                array(
                                    'value' => 'search_custom_fields',
                                    'label' => __('Custom Fields', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_website',
                                    'label' => __('Website', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_email',
                                    'label' => __('Email', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_phone',
                                    'label' => __('Phone', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_fax',
                                    'label' => __('Fax', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_zip_code',
                                    'label' => __('Zip/Post Code', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'radius_search',
                                    'label' => __('Radius Search', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'search_text',
                                'search_category',
                                'search_location',
                                'search_price',
                                'search_rating',
                                'search_tag',
                                'radius_search'
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'name' => 'listing_map_visible_fields',
                            'label' => __('Visible Fields', 'direo-extension'),
                            'validation' => 'minselected[0]|maxselected[15]',
                            'items' => array(
                                array(
                                    'value' => 'search_text',
                                    'label' => __('Text', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_category',
                                    'label' => __('Category', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_location',
                                    'label' => __('Location', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_price',
                                    'label' => __('Price (Min - Max)', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_price_range',
                                    'label' => __('Price Range', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_rating',
                                    'label' => __('Rating', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_tag',
                                    'label' => __('Tag', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_open_now',
                                    'label' => sprintf(__('Open Now %s', 'direo-extension'), !class_exists('BD_Business_Hour') ? $business_hours : '')),
                                array(
                                    'value' => 'search_custom_fields',
                                    'label' => __('Custom Fields', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_website',
                                    'label' => __('Website', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_email',
                                    'label' => __('Email', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_phone',
                                    'label' => __('Phone', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_fax',
                                    'label' => __('Fax', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_zip_code',
                                    'label' => __('Zip/Post Code', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'radius_search',
                                    'label' => __('Radius Search', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'search_text',
                                'search_category',
                                'search_location',
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'name' => 'bdmv_search_filters',
                            'label' => __('Filters Button', 'direo-extension'),
                            'validation' => 'minselected[0]|maxselected[2]',
                            'items' => array(
                                array(
                                    'value' => 'search_reset_filters',
                                    'label' => __('Reset Filters', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'search_apply_filters',
                                    'label' => __('Apply Filters', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'search_reset_filters', 'search_apply_filters'
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'listing_map_location_address',
                            'label' => __('Location Source for Search', 'direo-extension'),
                            'items' => array(
                                array(
                                    'value' => 'listing_location',
                                    'label' => __('Display from Listing Location', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'map_api',
                                    'label' => __('Display From Map API', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => 'map_api',
                                'label' => __('Display From Map API', 'direo-extension'),
                            ),
                        ),
                    ),// ends fields array
                ), // ends slider section array
                'listings_section' => array(
                    'type' => 'section',
                    'title' => __('Listings Settings', 'direo-extension'),
                    'description' => __('You can Customize Listings Settings here', 'direo-extension'),
                    'fields' => array(
                        'theme_one_listings_field' => array(
                            'type' => 'select',
                            'name' => 'listing_map_view',
                            'label' => __('Default View', 'direo-extension'),
                            'items' => array(
                                array(
                                    'value' => 'grid',
                                    'label' => __('Grid', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'list',
                                    'label' => __('List', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => 'grid',
                                'label' => __('Grid', 'direo-extension'),
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'listings_map_viewas',
                            'label' => __('Display "View As" Dropdown', 'direo-extension'),
                            'default' => 1,
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'listings_map_sortby',
                            'label' => __('Display "Sort By" Dropdown', 'direo-extension'),
                            'default' => 1,
                        ),

                    ),
                ),
                'map_section' => array(
                    'type' => 'section',
                    'title' => __('Listings Settings', 'direo-extension'),
                    'description' => __('You can Customize Map Settings here', 'direo-extension'),
                    'fields' => array(
                        'theme_one_listings_field' => array(
                            'type' => 'slider',
                            'name' => 'listing_map_zoom_level',
                            'label' => __('Zoom Level for Map View', 'direo-extension'),
                            'description' => __('Here 0 means 100% zoom-out. 18 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', 'direo-extension'),
                            'min' => '1',
                            'max' => '18',
                            'step' => '1',
                            'default' => '2',

                        ),

                    ),// ends fields array
                ), // ends slider section array


            ), // ends controls array that holds an array of sections
        );
        return $settings_submenus;
    }
}