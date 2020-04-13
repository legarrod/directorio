<?php
/**
 * The template to display the Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

if (post_password_required()) {
    return;
}

if (!function_exists('direo_comment')) {
    function direo_comment($comment, $args, $depth)
    {
        $global['comment'] = $comment; ?>

    <li <?php comment_class(empty($args['has_children']) ? '' : 'depth-1'); ?> id="comment-<?php comment_ID(); ?>">
        <div class="media p-bottom-20" id="div-comment-<?php comment_ID() ?>">
            <div class="cmnt_avatar">
                <?php echo get_avatar($comment, 70, '', '', array('class' => 'media-object rounded-circle')) ?>
            </div>
            <div class="media-body">
                <div class="media_top">
                    <div class="heading_left">
                        <h6 class="media-heading"><?php echo get_comment_author(get_comment_ID()) ?></h6>
                        <span><?php comment_date(); ?></span>
                    </div>
                    <div class="heading_left">
                        <?php edit_comment_link('<span class="la la-edit"></span>'. esc_html__('Edit','direo')); ?>
                        <?php comment_reply_link(array_merge(array('reply_text' => '<i class="la la-reply"> </i>' . esc_html__(' Reply', 'direo')), array('depth' => $depth, 'max_depth' => 4))); ?>
                    </div>

                </div>
                <?php if ('0' == $comment->comment_approved) : ?>
                    <em class="comment-status-notice"><?php esc_html_e('Your comment is awaiting moderation.', 'direo'); ?></em>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </div>
        <?php
    }
}

add_filter('comment_reply_link', 'replace_reply_link_class');

function replace_reply_link_class($class)
{
    $class = str_replace("class='comment-reply-link", "class='comment-reply-link reply", $class);
    return $class;
}


function direo_comment_field($default)
{
    $default['author'] = '<div class="col-md-6"><input name="author" type="text" placeholder="'.esc_attr_x('Name*', 'placeholder', 'direo').'" class="form-control m-bottom-30" required></div>';

    $default['email'] = '<div class="col-md-6">
                            <input name="email" type="email" placeholder="'.esc_attr_x('Email*', 'placeholder', 'direo').'" class="form-control m-bottom-30" required>
                         </div>';
    $default['url'] = '';
    $default['comment_field'] = '<div class="col-md-12">
                                    <textarea name="comment" placeholder="'.esc_attr_x('Your Text', 'placeholder', 'direo').'" class="form-control m-bottom-30"></textarea>
                                 </div>';
    $default['cookies'] = ' ';
    return $default;
}

add_filter('comment_form_default_fields', 'direo_comment_field');

function direo_comment_form_config($form)
{

    if (!is_user_logged_in()) {
        $form['comment_field'] = '';
    } else {
        $form['comment_field'] = '<div class="col-md-12">
                                        <textarea name="comment" placeholder="'.esc_attr_x('Your Text', 'placeholder', 'direo').'" class="form-control m-bottom-30"></textarea>
                                  </div>';
    }

    $form['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="btn btn-primary %3$s" value="Post Comment">';
    $form['submit_field'] = '<div class="col-md-12"><p class="form-submit m-bottom-0">%1$s %2$s</p></div>';
    $form['title_reply_before'] = '<h3 class="m-bottom-10">';
    $form['title_reply_after'] = '</h3>';
    $form['class_form'] = 'comment_form_wrapper row';
    $form['comment_notes_before'] = '<div class="col-md-12">
                                        <p class="comment-notes m-bottom-40">
                                           <span id="email-notes">' . esc_html__('Your email address will not be published.', 'direo') . '</span>' .
        sprintf(' ' . esc_html__('Required fields are marked %s', 'direo'), '<span class="color-primary">*</span>') .
        '</p>
                                     </div>';

    $form = str_replace("logged-in-as", "logged-in-as col-md-12", $form);

    return $form;
}

add_filter('comment_form_defaults', 'direo_comment_form_config');