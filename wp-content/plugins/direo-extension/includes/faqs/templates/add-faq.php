<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');
$faqsInfo = (array_key_exists('listing_faq', $args)) ? $args['listing_faq'] : array(); ?>
<!--<label for="atbdp_social"><?php /*esc_html_e('Social Information:', 'direo-extension'); */ ?></label>
-->
<div id="faqs_info_sortable_container">
    <?php

    if (!empty($faqsInfo)) {
        foreach ($faqsInfo as $index => $faqs) { // eg. here, $faqs = ['id'=> 'facebook', 'url'=> 'http://fb.com']
            ?>
            <div class="row  atbdp_faqs_wrapper" id="faqsID-<?= $index; ?>" style="margin-bottom: 10px">
                <!--Social ID-->
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <!-- <label><?php /*_e('Question', 'direo-extension');*/ ?></label>-->
                        <input type="text" placeholder="<?php _e('Question', 'direo-extension'); ?>" name="faqs[<?= $index; ?>][quez]" id="atbdp_social"
                               value="<?= !empty($faqs['quez']) ? esc_attr($faqs['quez']) : ''; ?>"
                               class="form-control atbdp_faqs_quez">

                    </div>
                </div>
                <!--Social URL-->
                <div class="col-md-6 col-sm-12">
                    <?php
                    $faqs_ans_box = get_directorist_option('faqs_ans_box', 'normal');
                    $content = !empty($faqs['ans']) ? esc_attr($faqs['ans']) : '';
                    if ('normal' === $faqs_ans_box) {
                        ?>
                        <textarea type="text" name="faqs[<?= $index; ?>][ans]"
                                  class="form-control directory_field atbdp_faqs_input"
                                  placeholder="<?php _e('Answer..', 'direo-extension'); ?>" rows="5"
                                  value=""><?= !empty($faqs['ans']) ? esc_attr($faqs['ans']) : ''; ?></textarea>
                        <?php
                    } else {
                        $settings = array(
                            'textarea_name' => "faqs[$index][ans]",//name you want for the textarea
                            'textarea_rows' => 8,
                            'tabindex' => 4,
                            'tinymce' => array(
                                'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp',
                            ),
                        );
                        $id = $index;//has to be lower case
                        wp_editor($content, $id, $settings);
                    }

                    ?>
                </div>
                <div class="col-md-2 col-sm-12">
                    <span data-id="<?= $index; ?>" class="removeFAQSField dashicons dashicons-trash"
                          title="<?php _e('Remove this item', 'direo-extension'); ?>"></span> <span
                            class="adl-move-icon dashicons dashicons-move"></span>
                </div>
            </div> <!--   ends .row   &  .atbdp_faqs_wrapper-->

            <?php
        }

    } ?>
</div> <!--    ends .faqs_info_sortable_container    -->

<button type="button" class="btn btn-secondary btn-sm" id="addNewFAQS"><span class="plus-sign">+</span>
    <?php esc_html_e('Add New', 'direo-extension'); ?>
</button>
