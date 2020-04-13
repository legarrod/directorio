<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');
$id = (array_key_exists('id', $args)) ? $args['id'] : 0;
?>


<div class="directorist row atbdp_faqs_wrapper" id="faqsID-<?= $id; ?>" style="margin-bottom: 10px">
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
           <!-- <label><?php /*_e('Question', 'direo-extension')*/?></label>-->
            <input type="text" placeholder="<?php _e('Question', 'direo-extension'); ?>" name="faqs[<?= !empty($index)?$index:''; ?>][quez]" id="atbdp_social" value="" class="form-control atbdp_faqs_quez">

        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <?php
            $faqs_ans_box = get_directorist_option('faqs_ans_box', 'normal');
            $placeholder=__('Answer..', 'direo-extension');
            if ('normal' === $faqs_ans_box){
                ?>
                <textarea type="text" rows="5" placeholder="<?php _e('Answer..', 'direo-extension'); ?>" name="faqs[<?= $id; ?>][ans]" class="form-control directory_field atbdp_faqs_input" value="" required></textarea>
            <?php
            }else{
                $settings = array(
                    'textarea_name'=>"faqs[$id][ans]",//name you want for the textarea
                    'textarea_rows' => 8,
                    'tabindex' => 4,
                    'tinymce' => array(
                        'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp',
                    ),
                );
                wp_editor($placeholder,$id,$settings);
            }

            ?>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <span data-id="<?= $id; ?>" class="removeFAQSField dashicons dashicons-trash" title="<?php _e('Remove this item', 'direo-extension'); ?>"></span>
        <span class="adl-move-icon dashicons dashicons-move"></span>
    </div>
</div>
