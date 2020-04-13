<?php
/*==========================================
    Shortcode: Feature Box
    Author URI: https://aazztech.com
============================================*/

$title = $desc = $type = $number = $icon = $class = $feature_style = '';

extract($atts);
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);

$el_class[] = ' list-unstyled ' . $class . ' ' . $feature_style;


if ('icon' == $type) { ?>
    <ul class="<?php echo implode(' ', $el_class); ?> ">
        <li>
            <div class="icon">
                <span class="circle-secondary">
                    <i class="la <?php echo esc_attr($icon); ?>"></i>
                </span>
            </div>
            <div class="list-content">
                <h4><?php echo esc_attr($title) ?></h4>
                <p><?php echo esc_attr($desc) ?></p>
            </div>
        </li>
    </ul>
    <?php
} else { ?>
    <ul class="<?php echo implode(' ', $el_class); ?> list-features p-top-15">
        <li>
            <div class="list-count">
                <span><?php echo esc_attr($number); ?></span>
            </div>
            <div class="list-content">
                <h4><?php echo esc_attr($title) ?></h4>
                <p><?php echo esc_attr($desc) ?></p>
            </div>
        </li>
    </ul>
    <?php
} ?>
