<?php
/*==========================================
    Element Name: Transaction
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'payment' => array(
                'name' => __('Transaction Failure', 'direo'),
                'icon' => 'fa fa-window-close',
                'category' => 'Direo',
                'priority' => 125,
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