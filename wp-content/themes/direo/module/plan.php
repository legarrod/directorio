<?php
/*==========================================
    Element Name: Pricing Plan
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'plan' => array(
                'name' => __('Pricing Plan', 'direo'),
                'icon' => 'fa-dollar-sign',
                'category' => 'Direo',
                'priority' => 122,
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