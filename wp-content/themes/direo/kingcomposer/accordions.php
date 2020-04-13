<?php

/*==========================================
    Shortcode : Accordion
    Author URI: https://aazztech.com
============================================*/

$section_title = '';
$accordions = [];
extract($atts);
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]); ?>

<div class="faq-contents <?php echo esc_attr(implode(' ', $el_class)); ?>">
    <div class="atbd_content_module atbd_faqs_module">
        <?php if ($section_title) { ?>
            <div class="atbd_content_module__tittle_area">
                <div class="atbd_area_title">
                    <h4>
                        <span class="la la-question-circle"></span>
                        <?php echo esc_attr($section_title); ?>
                    </h4>
                </div>
            </div>
            <?php
        } ?>
        <div class="atbdb_content_module_contents">
            <div class="atbdp-accordion direo_accordion">
                <?php if (!empty($accordions)) {
                    foreach ($accordions as $key => $accordion) {
                        $title = isset($accordion->title) ? $accordion->title : '';
                        $desc = isset($accordion->desc) ? $accordion->desc : ''; ?>
                        <div class="dacc_single <?php echo (1 == $key) ? esc_html('selected') : ''; ?>">
                            <h3 class="faq-title">
                                <a href="#"><?php echo esc_attr($title); ?></a>
                            </h3>
                            <p class="dac_body"><?php echo esc_attr($desc); ?></p>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } ?>
            </div>
        </div>
    </div>
</div>