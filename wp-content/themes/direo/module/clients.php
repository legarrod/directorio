<?php

/*==========================================
    Element Name: Client Carousel
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'clients' => array(
                'name' => esc_html__('Logo Carousel', 'direo'),
                'description' => esc_html__(' Display client logo in carousel', 'direo'),
                'category' => 'Content',
                'priority' => 134,
                'icon' => 'kc-icon-pcarousel',
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'clients_logo',
                            'label' => esc_html__('Upload Logos', 'direo'),
                            'type' => 'attach_images',
                            'admin_label' => true,
                        )
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
                                    ),
                                )
                            )
                        )
                    ),
                ),
            ),
        )
    );
}






