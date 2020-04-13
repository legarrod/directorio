<?php
/*==========================================
    Element Name: Listing
    Author URI: https://aazztech.com
============================================*/
if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'listing-carousel' => array(
                'name' => esc_html__('Listings Carousel', 'direo'),
                'icon' => 'fa fa-arrows-alt-h',
                'category' => 'Direo',
                'priority' => 112,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'featured',
                            'label' => __('Show Featured Listing Only?', 'direo-core'),
                            'value' => 'no',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'list_num',
                            'type' => 'number_slider',
                            'label' => esc_html__('Listings Per Page', 'direo'),
                            'description' => esc_html__('The number of listings you want to show. Set -1 for all listings', 'direo'),
                            'value' => '6',
                            'admin_label' => true,
                            'options' => array(
                                'min' => -1,
                                'max' => 500
                            )
                        ),
                        array(
                            'name' => 'contact',
                            'label' => esc_html__('Show Listing Address?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'phone',
                            'label' => esc_html__('Show Listing Phone Number?', 'direo'),
                            'value' => 'yes',
                            'type' => 'toggle',
                        ),
                        array(
                            'name' => 'date',
                            'label' => esc_html__('Show Listing Publish Date?', 'direo'),
                            'value' => 'yes',
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