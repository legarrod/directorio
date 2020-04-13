<?php
/*==========================================
    Element Name: Listing Locations
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'add-listing' => array(
                'name' => esc_html__('Listing Form', 'direo'),
                'icon' => 'fa fa-plus',
                'category' => 'Direo',
                'priority' => 115,
                'params' => array(
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
                    )
                )
            )
        )
    );
}