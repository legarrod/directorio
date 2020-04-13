<?php
/*==========================================
    Element Name: User Login
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'login' => array(
                'name' => __('Login Form', 'direo'),
                'icon' => 'sl-login',
                'category' => 'Direo',
                'priority' => 128,
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