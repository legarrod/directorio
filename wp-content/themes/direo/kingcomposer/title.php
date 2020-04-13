<?php
/*==========================================
    Shortcode: Title Pro
    Author URI: https://aazztech.com
============================================*/

$title = $type = $inline_link = $link = $subtitle = $class = $a_href = $a_title = $a_target = $css = '';

extract($atts);
if (empty($type)) {
    $heading_start = '<h2>';
    $heading_end = '</h2>';
} else {
    $heading_start = '<' . $type . '>';
    $heading_end = '</' . $type . '>';
}
$link = ('||' === $link) ? '' : $link;
$link = kc_parse_link($link);
if (strlen($link['url']) > 0) {
    $a_href = $link['url'];
    $a_title = $link['title'];
    $a_target = strlen($link['target']) > 0 ? $link['target'] : '_self';
}

$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);
$el_class[] = $class . ' section-title'; ?>


<div class="<?php echo implode(' ', $el_class); ?>">

    <?php
    echo wp_kses_post($heading_start);

    if ($inline_link == true) { ?>
        <a title="<?php echo esc_attr($a_title); ?>" href="<?php echo esc_url($a_href); ?>"
           target="<?php echo esc_attr($a_target); ?>">
            <?php echo wp_kses_post($title); ?>
        </a>
        <?php
    } else {
        echo nl2br(wp_kses_post($title));
    }

    echo wp_kses_post($heading_end);

    echo wpautop(esc_attr($subtitle)); ?>

</div>
