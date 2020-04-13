<?php
/*==========================================
    Element Name: Blog Posts
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'blog-posts' => array(
                'name' => esc_html__('Blog Posts', 'direo'),
                'icon' => 'kc-icon-blog-posts',
                'category' => 'Content',
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'number_post',
                            'type' => 'number_slider',
                            'label' => esc_html__('Number of posts displayed', 'direo'),
                            'description' => esc_html__('The number of posts you want to show.', 'direo'),
                            'value' => 3,
                            'admin_label' => true,
                            'options' => array(
                                'min' => 1,
                                'max' => 12
                            )
                        ),
                        array(
                            'name' => 'order_by',
                            'type' => 'dropdown',
                            'label' => esc_html__('Order by', 'direo'),
                            'admin_label' => true,
                            'options' => array(
                                'ID' => esc_html__(' Post ID', 'direo'),
                                'author' => esc_html__(' Author', 'direo'),
                                'title' => esc_html__(' Title', 'direo'),
                                'name' => esc_html__(' Post name (post slug)', 'direo'),
                                'type' => esc_html__(' Post type (available since Version 4.0)', 'direo'),
                                'date' => esc_html__(' Date', 'direo'),
                                'modified' => esc_html__(' Last modified date', 'direo'),
                                'rand' => esc_html__(' Random order', 'direo'),
                                'comment_count' => esc_html__(' Number of comments', 'direo')
                            ),
                            'value' => 'date'
                        ),
                        array(
                            'type' => 'dropdown',
                            'label' => esc_html__('Order post', 'direo'),
                            'name' => 'order_list',
                            'admin_label' => true,
                            'options' => array(
                                'ASC' => esc_html__(' ASC', 'direo'),
                                'DESC' => esc_html__(' DESC', 'direo'),
                            ),
                            'value' => 'DESC'
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
                                        array('property' => 'padding', 'label' => esc_html__('Padding', 'direo')),
                                        array('property' => 'margin', 'label' => esc_attr__('Margin', 'direo')),
                                    )
                                )
                            )
                        )
                    ),
                )
            ),
        )
    );
}