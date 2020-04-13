<?php
/*==========================================
    Shortcode : Checkout
    Author URI: https://aazztech.com
============================================*/

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]); ?>

<div class="direo-directorist_checkout <?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_checkout]'); ?>
</div>