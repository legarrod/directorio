<?php
/*==========================================
    Shortcode : Divider
    Author URI: https://aazztech.com
============================================*/
$style = $icon = $line_text = $class = '';

extract($atts);

$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$wrap_class[] = 'divider_line';
$wrap_class[] = 'float-none';

if ($class != '')
    $wrap_class[] = $class;
?>

<div class="<?php echo implode(' ', $wrap_class); ?>">
    <div class="divider_inner <?php echo esc_attr('divider_line' . $style); ?>">
        <?php
        switch ($style) {
            case '2':
                if ($icon)
                    echo sprintf('<i class="%s"></i>', esc_attr($icon));
                break;
            case '3':
                if ($line_text)
                    echo sprintf('<span class="line_text">%s</span>', esc_attr($line_text));
                break;
        } ?>
    </div>
</div>