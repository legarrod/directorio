<?php
/*==========================================
    Shortcode : Button
    Author URI: https://aazztech.com
============================================*/
$text_title = $class = '';
$button_attr = array();

extract($atts);

$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);
$el_class[] = $class . ' action-btns';

$link = ('||' === $link) ? '' : $link;
$link = kc_parse_link($link);

if (strlen($link['url']) > 0) {
    $a_href = $link['url'];
    $a_title = $link['title'];
    $a_target = strlen($link['target']) > 0 ? $link['target'] : '_self';
}

if (isset($a_href))
    $button_attr[] = 'href="' . esc_attr($a_href) . '"';

if (isset($a_target))
    $button_attr[] = 'target="' . esc_attr($a_target) . '"';

if (isset($a_title))
    $button_attr[] = 'title="' . esc_attr($a_title) . '"'; ?>

<div class="<?php echo implode(' ', $el_class); ?>">
    <a <?php echo implode(' ', $button_attr); ?> class="direo-btn">
        <?php
        if ($text_title) {
            echo esc_attr($text_title);
        } ?>
    </a>
</div>
