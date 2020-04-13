<?php
/*==========================================
    Shortcode : User Login
    Author URI: https://aazztech.com
============================================*/

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
?>

<div class="direo-directorist_user_login <?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_user_login]'); ?>
</div>