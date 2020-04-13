<?php

/*==========================================
    Element Name: Divider
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'divider' => array(
                'name' => esc_html__('Divider', 'direo'),
                'description' => esc_html__('List of horizontal divider line', 'direo'),
                'icon' => 'kc-icon-divider',
                'category' => 'Content',
                'priority' => 410,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'select',
                            'name' => 'style',
                            'admin_label' => true,
                            'label' => esc_html__('Select Style', 'direo'),
                            'description' => esc_html__('Style of divider', 'direo'),
                            'value' => '1',
                            'options' => array(
                                '1' => esc_html__('Line Simple', 'direo'),
                                '2' => esc_html__('Line With Icon', 'direo'),
                                '3' => esc_html__('Line With Text', 'direo')
                            )
                        ),
                        array(
                            'type' => 'icon_picker',
                            'name' => 'icon',
                            'label' => esc_html__('Icon', 'direo'),
                            'description' => esc_html__('Select icon on divider', 'direo'),
                            'value' => 'sl-heart',
                            'relation' => array(
                                'parent' => 'style',
                                'show_when' => array('2')
                            )
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'line_text',
                            'label' => esc_html__('Text Line', 'direo'),
                            'description' => esc_html__('Text display center line.', 'direo'),
                            'relation' => array(
                                'parent' => 'style',
                                'show_when' => array('3')
                            )
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'class',
                            'label' => esc_html__('Class', 'direo'),
                            'description' => esc_html__('Extra CSS class', 'direo')
                        )
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
                                    'Line' => array(
                                        array('property' => 'border-color', 'label' => 'Line Color', 'selector' => '.divider_inner'),
                                        array('property' => 'border-style', 'label' => 'Divider Style', 'value' => 'solid', 'selector' => '.divider_inner'),
                                        array('property' => 'width', 'label' => 'Width Line', 'selector' => '.divider_inner'),
                                        array('property' => 'border-width', 'label' => 'Height Line', 'selector' => '.divider_inner'),
                                        array('property' => 'text-align', 'label' => 'Text Align'),
                                        array('property' => 'display', 'label' => 'Display'),
                                        array('property' => 'margin', 'label' => 'Spacing'),
                                    ),
                                    'Icon' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => 'i'),
                                        array('property' => 'background-color', 'label' => 'Background Color', 'selector' => 'i'),
                                        array('property' => 'width', 'label' => 'Width', 'selector' => 'i'),
                                        array('property' => 'height', 'label' => 'Height', 'selector' => 'i'),
                                        array('property' => 'font-size', 'label' => 'Size', 'selector' => 'i'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => 'i'),
                                        array('property' => 'border', 'label' => 'Border', 'selector' => 'i'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => 'i'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => 'i'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'i'),
                                    ),
                                    'Text' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => '.line_text'),
                                        array('property' => 'background-color', 'label' => 'Background Color', 'selector' => '.line_text'),
                                        array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.line_text'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => '.line_text'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.line_text'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.line_text'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.line_text'),
                                        array('property' => 'border', 'label' => 'Border', 'selector' => '.line_text'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.line_text'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => '.line_text'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => '.line_text'),
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






