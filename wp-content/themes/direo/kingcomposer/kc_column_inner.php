<?php

/*==========================================
    Shortcode : Inner Column
    Author URI: https://aazztech.com
============================================*/

$output = $width = $col_in_class = $col_in_class_container = $css = $col_id = '';
$attributes = array();

extract($atts);

$classes = apply_filters('kc-el-class', $atts);
unset($classes[0]);
$classes[] = 'kc_column_inner';
$classes[] = @direo_column_width_class($width);


if (!empty($col_in_class))
    $classes[] = $col_in_class;

if (!empty($css))
    $classes[] = $css;

$col_in_class_container = !empty($col_in_class_container) ? $col_in_class_container . ' kc_wrapper kc-col-inner-container' : 'kc_wrapper kc-col-inner-container';


if (!empty($col_id))
    $attributes[] = 'id="' . $col_id . '"';

$attributes[] = 'class="' . esc_attr(trim(implode(' ', $classes))) . '"'; ?>

<div <?php echo implode(' ', $attributes); ?>>
    <div class="<?php echo trim(esc_attr($col_in_class_container)); ?>">
        <?php echo do_shortcode(str_replace('kc_column_inner#', 'kc_column_inner', $content)) ?>
    </div>
</div>