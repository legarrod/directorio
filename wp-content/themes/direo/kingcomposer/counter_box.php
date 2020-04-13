<?php
/*==========================================
    Shortcode : Counter Box
    Author URI: https://aazztech.com
============================================*/
$number = $label = '';

extract($atts);

$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);
$numbers = explode(',', $number); ?>

<div class="list-unstyled counter-items <?php echo esc_attr(implode(' ', $el_class)); ?>">
    <div>
        <p>
            <span class="count_up">
                <?php echo !empty($numbers[0]) ? esc_attr($numbers[0]) : ''; ?>
            </span>
            <?php echo !empty($numbers[1]) ? esc_attr($numbers[1]) : ''; ?>
        </p>
        <span><?php echo esc_attr($label); ?></span>
    </div>
</div>
