<?php
/*==========================================
    Element Name: Button
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'dr_button' => array(
                'name' => esc_html__('Button', 'direo'),
                'description' => esc_html__(' ', 'direo'),
                'icon' => 'kc-icon-button',
                'category' => 'Content',
                'priority' => 310,
                'params' => array(
                    'general' => array(
                        array(
                            'type' => 'text',
                            'label' => esc_html__('Title', 'direo'),
                            'name' => 'text_title',
                            'description' => esc_html__('Add the text that appears on the button.', 'direo'),
                            'value' => 'Text Button',
                            'admin_label' => true
                        ),
                        array(
                            'type' => 'link',
                            'label' => esc_html__('Link', 'direo'),
                            'name' => 'link',
                            'description' => esc_html__('Add your relative URL. Each URL contains link, anchor text and target attributes.', 'direo'),
                        ),
                        array(
                            'type' => 'text',
                            'label' => esc_html__('Wrapper class name', 'direo'),
                            'name' => 'class',
                            'description' => esc_html__('Custom class for wrapper of the shortcode widget.', 'direo'),
                        )
                    ),
                    'styling' => array(
                        array(
                            'type' => 'css',
                            'label' => esc_html__('css', 'direo'),
                            'name' => 'custom_css',
                            'options' => array(
                                array(
                                    'screens' => 'any,1024,999,767,479',
                                    'Button Style' => array(
                                        array('property' => 'color', 'label' => 'Text Color', 'selector' => 'a'),
                                        array('property' => 'background-color', 'label' => 'Background Color', 'selector' => 'a'),
                                        array('property' => 'font-size', 'label' => 'Text Size', 'selector' => 'a'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => 'a'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => 'a'),
                                        array('property' => 'display', 'label' => 'Display'),
                                        array('property' => 'border', 'label' => 'Border', 'selector' => 'a'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => 'a'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => 'a'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'a'),
                                    ),
                                    'Mouse Hover' => array(
                                        array('property' => 'font-size', 'label' => 'Text Size', 'selector' => 'a:hover'),
                                        array('property' => 'color', 'label' => 'Text Color', 'selector' => 'a:hover'),
                                        array('property' => 'background-color', 'label' => 'Background Color', 'selector' => 'a:hover'),
                                        array('property' => 'border', 'label' => 'Border', 'selector' => 'a:hover'),
                                        array('property' => 'border-radius', 'label' => 'Border Radius Hover', 'selector' => 'a:hover')
                                    )
                                )
                            )
                        ),
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