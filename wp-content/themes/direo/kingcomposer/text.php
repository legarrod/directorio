<?php
/*==========================================
    Shortcode : Text Block
    Author URI: https://aazztech.com
============================================*/
$class = $css = '';

extract($atts);

$output = '';
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);
$el_class[] = 'kc_text_block';

if ($class != '') $el_class[] = $class;
if ($css != '') $el_class[] = $css;

$content = apply_filters('kc_column_text', $content);

echo sprintf('<div class="%s mb-0">%s</div>', esc_attr(implode(' ', $el_class)), do_shortcode($content));