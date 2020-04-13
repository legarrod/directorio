<?php
/*==========================================
    Element Name: Need All Categories
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'need-categories' => array(
                'name' => esc_html__('Need Categories', 'direo'),
                'description' => esc_html__('Display your all need Categories.', 'direo'),
                'icon' => 'fas fa-tag',
                'category' => 'Need',
                'priority' => 117,
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
                            'name' => 'cat_style',
                            'type' => 'radio',
                            'label' => esc_html__('Category Style', 'direo'),
                            'options' => array(
                                'category-style1' => esc_html__('Style 1', 'direo'),
                                'category-style-two' => esc_html__('Style 2', 'direo'),
                            ),
                            'value' => 'category-style1',
                            'relation' => array(
                                'parent' => 'layout',
                                'show_when' => 'grid'
                            )
                        ),
                        array(
                            'name' => 'row',
                            'label' => esc_html__('Categories Per Row', 'direo'),
                            'type' => 'select',
                            'value' => '3',
                            'options' => array(
                                '5' => esc_html__('5 Items / Row', 'direo'),
                                '4' => esc_html__('4 Items / Row', 'direo'),
                                '3' => esc_html__('3 Items / Row', 'direo'),
                                '2' => esc_html__('2 Items / Row', 'direo'),
                            ),
                        ),
                        array(
                            'name' => 'number_cat',
                            'type' => 'number_slider',
                            'label' => esc_html__('Categories Per Page', 'direo'),
                            'value' => '6',
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 1000
                            )
                        ),
                        array(
                            'type' => 'dropdown',
                            'label' => esc_html__('Order by', 'direo'),
                            'name' => 'order_by',
                            'admin_label' => true,
                            'options' => array(
                                'id' => esc_html__(' Cat ID', 'direo'),
                                'count' => esc_html__('Needs Count', 'direo'),
                                'name' => esc_html__(' Category name (A-Z)', 'direo'),
                                'slug' => esc_html__('Select Category', 'direo'),
                            ),
                            'value' => 'id'
                        ),
                        array(
                            'name' => 'slug',
                            'label' => esc_html__('Select Categories', 'direo'),
                            'type' => 'multiple',
                            'options' => class_exists('Directorist_Base') ? direo_listing_category() : [],
                            'relation' => array(
                                'parent' => 'order_by',
                                'show_when' => 'slug'
                            )
                        ),
                        array(
                            'type' => 'dropdown',
                            'label' => esc_html__('Categories Order', 'direo'),
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