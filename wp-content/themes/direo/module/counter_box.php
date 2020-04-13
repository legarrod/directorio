<?php

/*==========================================
    Element Name: Counter Box
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'counter_box' => array(
                'name' => esc_html__('Counter Box', 'direo'),
                'description' => esc_html__(' ', 'direo'),
                'icon' => 'kc-icon-counter',
                'category' => 'Content',
                'priority' => 330,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'text',
                            'label' => esc_html__('Targeted number', 'direo'),
                            'name' => 'number',
                            'description' => __('The targeted number to count up to (From zero). e.g: <b>23,k+</b> ', 'direo'),
                            'admin_label' => true,
                            'value' => '23,k+'
                        ),
                        array(
                            'type' => 'text',
                            'label' => esc_html__('Label', 'direo'),
                            'name' => 'label',
                            'admin_label' => true,
                            'value' => 'Percent number'
                        ),
                        array(
                            'type' => 'text',
                            'label' => esc_html__('Wrapper class name', 'direo'),
                            'name' => 'wrap_class',
                            'description' => esc_html__('Custom class for wrapper of the shortcode widget.', 'direo'),
                        )
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
                                    'Label' => array(
                                        array('property' => 'color', 'label' => 'Label Color', 'selector' => 'span'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => 'span'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => 'span'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => 'span'),
                                        array('property' => 'text-align', 'label' => 'Text Align', 'selector' => 'span'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => 'span'),
                                        array('property' => 'margin', 'label' => 'Label Margin', 'selector' => 'span')
                                    ),
                                    'Number' => array(
                                        array('property' => 'color', 'label' => 'Number Color', 'selector' => 'p span, p'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => 'p span, p'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => 'p span, p'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => 'p span, p'),
                                        array('property' => 'text-align', 'label' => 'Text Align', 'selector' => 'p span, p'),
                                        array('property' => 'margin', 'label' => 'Number Margin', 'selector' => 'p span, p'),
                                    ),
                                    'Box Style' => array(
                                        array('property' => 'background', 'label' => 'Background'),
                                        array('property' => 'display', 'label' => 'Display'),
                                        array('property' => 'border', 'label' => 'Border'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                    )
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
            ),
        )
    );
}






