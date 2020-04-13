<?php
/*==========================================
    Shortcode : Payment
    Author URI: https://aazztech.com
============================================*/

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
?>

<div class="direo-payment-receipt <?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_payment_receipt]'); ?>
</div>