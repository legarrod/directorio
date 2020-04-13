<?php
/*==========================================
    Element Name: Text Block
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'text' => array(
                'name' => esc_html__(' Text Block', 'direo'),
                'description' => esc_html__(' A block of text with TINYMCE editor', 'direo'),
                'icon' => 'kc-icon-text',
                'category' => 'Content',
                'is_container' => true,
                'priority' => 140,
                'pop_width' => 650,
                'admin_view' => 'text',
                'preview_editable' => true,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'content',
                            'label' => esc_html__('Content', 'direo'),
                            'type' => 'textarea_html',
                            'value' => esc_html__('Sample Text', 'direo'),
                        ),
                        array(
                            'name' => 'class',
                            'label' => esc_html__('Extra Class', 'direo'),
                            'type' => 'text',
                        )
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
                                    'Typography' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => ',p'),
                                        array('property' => 'font-family', 'label' => 'Font Family', 'selector' => ',p'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => ',p'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => ',p'),
                                        array('property' => 'font-style', 'label' => 'Font Style', 'selector' => ',p'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => ',p'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => ',p'),
                                        array('property' => 'text-align', 'label' => 'Text Align', 'selector' => ',p'),
                                        array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => ',p'),
                                    ),
                                    'Box' => array(
                                        array('property' => 'background', 'label' => 'Background'),
                                        array('property' => 'border', 'label' => 'Border'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius'),
                                        array('property' => 'display', 'label' => 'Display'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'p'),

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