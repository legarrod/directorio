<?php
/*==========================================
    Element Name: Checkout
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'checkout' => array(
                'name' => __('Checkout', 'direo'),
                'category' => 'Direo',
                'icon' => 'fa fa-shopping-cart',
                'priority' => 124,
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