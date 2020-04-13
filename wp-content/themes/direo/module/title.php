<?php
/*==========================================
    Element Name: Title Pro
    Author URI: https://aazztech.com
============================================*/

if (function_exists('kc_add_map')) {
    kc_add_map(
        array(
            'title' => array(
                'name' => esc_html__('Title Pro', 'direo'),
                'description' => esc_html__('Display a title with subtitle', 'direo'),
                'icon' => 'kc-icon-title',
                'category' => 'Content',
                'priority' => 210,
                'params' => array(
                    'general' => array(
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'direo'),
                            'type' => 'textarea',
                            'description' => __('Insert tag <b>&lt;span&gt;</b> when you want to highlight text.<br> Example: Why <b> &lt;span&gt;<span> Direo </span>&lt;/span&gt; </b>for your Business?', 'direo'),
                            'admin_label' => true,
                        ),
                        array(
                            'name' => 'type',
                            'label' => esc_html__(' Type', 'direo'),
                            'type' => 'select',
                            'admin_label' => true,
                            'options' => array(
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                            )
                        ),
                        array(
                            'name' => 'inline_link',
                            'label' => esc_html__('Add inline link?', 'direo'),
                            'type' => 'toggle',
                            'description' => esc_html__('Additional text with link on one line with title text.', 'direo')
                        ),
                        array(
                            'name' => 'link',
                            'label' => esc_html__(' Link title', 'direo'),
                            'type' => 'link',
                            'description' => esc_html__(' Insert link for title', 'direo'),
                            'relation' => array(
                                'parent' => 'inline_link',
                                'show_when' => 'yes'
                            )
                        ),
                        array(
                            'label' => esc_html__('Sub Title', 'direo'),
                            'name' => 'subtitle',
                            'type' => 'textarea',
                            'admin_label' => true,
                        ),
                        array(
                            'name' => 'class',
                            'label' => esc_html__(' Extra Class', 'direo'),
                            'type' => 'text'
                        ),
                    ),
                    'styling' => array(
                        array(
                            'name' => 'css_custom',
                            'type' => 'css',
                            'options' => array(
                                array(
                                    "screens" => "any,1024,999,767,479",
                                    'Section' => array(
                                        array('property' => 'margin', 'label' => 'Margin'),
                                        array('property' => 'padding', 'label' => 'Padding'),
                                    ),
                                    'Title' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'color', 'label' => 'Color Hover', 'selector' => '+:hover h1, +:hover h2, +:hover h3, +:hover h4, +:hover h5, +:hover h6'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'line-height', 'label' => 'Line Height', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'font-style', 'label' => 'Font Style', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'text-align', 'label' => 'Text Align', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => 'h1, h2, h3, h4, h5, h6'),
                                    ),
                                    'subtitle' => array(
                                        array('property' => 'color', 'label' => 'Color', 'selector' => 'p'),
                                        array('property' => 'color', 'label' => 'Color Hover', 'selector' => '+:hover p'),
                                        array('property' => 'font-size', 'label' => 'Font Size', 'selector' => 'p'),
                                        array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => 'p'),
                                        array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => 'p'),
                                        array('property' => 'text-align', 'label' => 'Text Align', 'selector' => 'p'),
                                        array('property' => 'padding', 'label' => 'Padding', 'selector' => 'p'),
                                        array('property' => 'margin', 'label' => 'Margin', 'selector' => 'p'),
                                    ),
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
            )
        )
    );
}