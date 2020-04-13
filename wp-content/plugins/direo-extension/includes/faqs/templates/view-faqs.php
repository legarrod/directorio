<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');
$faqsInfo = (array_key_exists('listing_faq', $args)) ? $args['listing_faq'] : array();
?>

<div class="atbd_content_module atbd_contact_information_module">
    <div class="atbd_content_module__tittle_area">
        <div class="atbd_area_title">
            <h4>
                <span class="<?php atbdp_icon_type(true);?>
-question-circle"></span><?php _e('Listing FAQs', 'direo-extension'); ?>
            </h4>
        </div>
    </div>

    <div class="atbdb_content_module_contents">

        <div class="atbdp-accordion">
            <?php
            foreach ($faqsInfo as $index => $faqInfo) {
            ?>
            <div class="accordion-single">
                <h3><a href="#"><?php echo !empty($faqInfo['quez'])?esc_attr($faqInfo['quez']):''; ?></a></h3>
                <p class="ac-body"><?php echo !empty($faqInfo['ans'])?($faqInfo['ans']):''; ?></p>
            </div>
                <?php
            }
            ?>
        </div>

    </div>
</div><!-- end .atbd_custom_fields_contents -->