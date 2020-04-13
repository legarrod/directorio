<?php

/*==========================================
    Shortcode : Contact Information
    Author URI: https://aazztech.com
============================================*/

$class = $title = $class = '';
$addresses = $socials = [];

extract($atts);
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);

$el_class[] = 'widget atbd_widget widget-card ' . $class; ?>

<div class="<?php echo esc_attr(implode(' ', $el_class)); ?> contact_form_widget contact_page_widget">

    <?php if ($title) { ?>
        <div class="atbd_widget_title">
            <h4>
                <span class="la la-phone"></span>
                <?php echo esc_attr($title); ?>
            </h4>
        </div>
        <?php
    } ?>

    <div class="widget-body atbd_author_info_widget">
        <?php if ($addresses) { ?>
            <div class="atbd_widget_contact_info">
                <ul>
                    <?php
                    foreach ($addresses as $address) {
                        $title = !empty($address->title) ? $address->title : '';
                        $icon = !empty($address->icon) ? $address->icon : '';
                        if (!empty($title)) { ?>
                            <li>
                                <span class="la <?php echo esc_attr($icon); ?>"></span>
                                <span class="atbd_info"><?php echo esc_attr($title); ?></span>
                            </li>
                        <?php }
                    }
                    wp_reset_postdata(); ?>
                </ul>
            </div>
        <?php }

        if ($socials) { ?>
            <div class="atbd_social_wrap">
                <?php
                foreach ($socials as $social) {
                    $url = !empty($social->url) ? $social->url : '';
                    $icon = !empty($social->icon) ? $social->icon : '';
                    if (!empty($title)) { ?>
                        <p>
                            <a href="<?php echo esc_url($url); ?>">
                                <span class="fab <?php echo esc_attr($icon); ?>"></span>
                            </a>
                        </p>
                    <?php }
                }
                wp_reset_postdata(); ?>
            </div>
            <?php
        } ?>
    </div>

</div>
