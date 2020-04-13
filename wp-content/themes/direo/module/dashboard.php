<?php
/*==========================================
    Element Name: Dashboard
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'dashboard' => array(
                'name' => __('Dashboard', 'direo'),
                'icon' => 'la la-dashboard',
                'category' => 'Direo',
                'priority' => 123,
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
                    ),
                ),
            )
        )
    );
}