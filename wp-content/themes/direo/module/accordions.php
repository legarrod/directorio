<?php

/*==========================================
    Element Name: Accordion
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'accordions' => array(
                'name' => esc_html__('Accordion', 'direo'),
                'description' => esc_html__('Collapsible content panels', 'direo'),
                'category' => 'Content',
                'priority' => 133,
                'icon' => 'kc-icon-accordion',
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'section_title',
                            'type' => 'text',
                            'label' => esc_html__('Element Title', 'direo'),
                        ),
                        array(
                            'type' => 'group',
                            'label' => esc_html__('Accordions', 'direo'),
                            'description' => esc_html__('Here you can add, remove, edit or reorder your accordions.', 'direo'),
                            'name' => 'accordions',
                            'options' => array('add_text' => esc_html__('Add', 'direo')),
                            'params' => array(
                                array(
                                    'type' => 'text',
                                    'label' => esc_html__('Tab Title', 'direo'),
                                    'name' => 'title',
                                ),
                                array(
                                    'type' => 'textarea',
                                    'label' => esc_html__('Tab Description', 'direo'),
                                    'name' => 'desc',
                                ),
                            )
                        ),
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
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
                ),
            ),
        )
    );
}