<?php

/*==========================================
    Shortcode : Contact Form
    Author URI: https://aazztech.com
============================================*/

$contact_form_id = $title = '';
extract($atts);

extract($atts);
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);

if ($contact_form_id) { ?>
    <div class="widget atbd_widget widget-card contact-block <?php echo implode(' ', $el_class); ?>">
        <?php if ($title) { ?>
            <div class="atbd_widget_title">
                <h4><span class="la la-envelope"></span><?php echo esc_attr($title); ?></h4>
            </div>
            <?php
        } ?>
        <div class="atbdp-widget-listing-contact contact-form">
            <?php echo do_shortcode('[contact-form-7 id="' . intval(esc_attr($contact_form_id)) . '" ]'); ?>
        </div>
    </div>
    <?php
} ?>
