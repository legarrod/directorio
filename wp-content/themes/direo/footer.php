<?php
$default = '©' . date('Y') . ' Direo. Made with <span class="la la-heart-o"></span> by <a href="#">AazzTech</a>';
$copy_right = get_theme_mod('copy_right', $default);

$login = get_theme_mod('login_btn', 'Login');
$register = get_theme_mod('register_btn', 'Register');

$social_login = class_exists('Directorist_Base') ? get_directorist_option('enable_social_login', 1) : '';
$dashboardFileName = basename(get_page_template());
$footer_style = get_post_meta(direo_page_id(), 'footer_style', true);

if (!$footer_style || 'light' == $footer_style) {
    $logo_id = get_theme_mod('footer_logo');
    $logo = wp_get_attachment_image_src(get_theme_mod('footer_logo'), 'full');
    $logo = $logo ? $logo[0] : '';
} else {
    $logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
    $logo = $logo ? $logo[0] : '';
}

if (!changed_header_footer()) { ?>
    <footer class="footer-three footer-<?php echo esc_attr($footer_style); ?>">
        <?php
        if ($dashboardFileName != 'dashboard.php' && 'footer-hide' != $footer_style) {
            if (is_active_sidebar('footer_sidebar_1') || is_active_sidebar('footer_sidebar_2') || is_active_sidebar('footer_sidebar_3') || is_active_sidebar('footer_sidebar_4')) { ?>
                <div class="footer-top p-top-95">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="footer-widgets p-bottom-25">
                                    <div class="row">
                                        <?php if (is_active_sidebar('footer_sidebar_1')) { ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <?php dynamic_sidebar('footer_sidebar_1'); ?>
                                            </div>
                                        <?php }
                                        if (is_active_sidebar('footer_sidebar_2')) { ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <?php dynamic_sidebar('footer_sidebar_2'); ?>
                                            </div>
                                        <?php }
                                        if (is_active_sidebar('footer_sidebar_3')) { ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <?php dynamic_sidebar('footer_sidebar_3'); ?>
                                            </div>
                                        <?php }
                                        if (is_active_sidebar('footer_sidebar_4')) { ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <?php dynamic_sidebar('footer_sidebar_4'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        <div class="footer-bottom">
            <div class="container<?php echo ($dashboardFileName == 'dashboard.php') ? esc_html('-fluid') : ''; ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-bottom--content">
                            <?php
                            echo $logo ? sprintf('<a href="%s" class="footer-logo"><img src="%s" alt="%s"></a>', esc_url(home_url('/')), esc_url($logo), direo_get_image_alt($logo_id)) : ''; ?>
                            <div class="copyr-content">
                                <?php echo apply_filters('get_the_content', $copy_right); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

if (class_exists('Directorist_Base')) {
    $display_password_reg = get_directorist_option('display_password_reg', 1);
    if (!is_user_logged_in()) { ?>
        <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modal_label"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="login_modal_label">
                            <i class="la la-lock"></i> <?php echo esc_attr($login); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="login" id="direo-login" method="post">
                            <input type="text" class="form-control" id="direo-username" name="username"
                                   placeholder="<?php echo esc_attr_x('Username or Email', 'placeholder', 'direo'); ?>"
                                   required>
                            <input type="password" id="direo-password" autocomplete="false" name="password"
                                   class="form-control"
                                   placeholder="<?php echo esc_attr_x('Password', 'placeholder', 'direo'); ?>"
                                   required>
                            <input class="btn btn-block btn-lg btn-gradient btn-gradient-two" type="submit"
                                   value="<?php echo esc_attr($login); ?>"
                                   name="submit"/>
                            <p class="status"></p>
                            <div class="form-excerpts">
                                <div class="keep_signed">
                                    <label for="keep_signed_in" class="not_empty">
                                        <input type="checkbox" id="direo-keep_signed_in" value="1" name="keep_signed_in"
                                               checked="">
                                        <?php esc_html_e('Remember Me', 'direo'); ?>
                                        <span class="cf-select"></span>
                                    </label>
                                </div>
                                <a href=""
                                   class="recover-pass-link"><?php esc_html_e('Forgot your password?', 'direo'); ?></a>
                            </div>
                            <?php wp_nonce_field('ajax-login-nonce', 'direo-security'); ?>
                        </form>
                        <form method="post" id="direo_recovery_password" class="recover-pass-form">
                            <fieldset>
                                <p>
                                    <?php esc_html_e('Please enter your username or email address. You will receive a link to create a new password via email.', 'direo'); ?></p>
                                <label for="user_login"><?php esc_html_e('E-mail:', 'direo'); ?></label>
                                <?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
                                <input type="text" name="direo_recovery_user" class="direo_recovery_user"
                                       id="user_login" value="<?php echo esc_attr($user_login); ?>"/>
                                <input type="hidden" name="action" value="reset"/>
                                <p class="recovery_status"></p>
                                <input type="submit" value="Get New Password"
                                       class="btn btn-primary direo_recovery_password" id="direo-submit"/>

                            </fieldset>
                        </form>
                        <?php if ($social_login) { ?>
                            <p class="social-connector text-center"><?php esc_html_e('Or connect with', 'direo'); ?></p>
                            <div class="social-login">
                                <?php do_action('atbdp_before_login_form_end'); ?>
                            </div>
                            <?php
                        } ?>
                        <div class="form-excerpts">
                            <ul class="list-unstyled">
                                <li>
                                    <?php esc_html_e('Not a member? ', 'direo'); ?>
                                    <a href="<?php echo ATBDP_Permalink::get_registration_page_link(); ?>"
                                       class="access-link" data-toggle="modal"
                                       data-target="#signup_modal"
                                       data-dismiss="modal">
                                        <?php echo esc_attr($register); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="signup_modal" tabindex="-1" role="dialog" aria-labelledby="signup_modal_label"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signup_modal_label">
                            <i class="la la-lock"></i>
                            <?php echo esc_attr($register); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="vb-registration-form">
                            <form class="form-horizontal registraion-form" role="form">
                                <div class="form-group">
                                    <input type="email" name="vb_email" id="vb_email" value=""
                                           placeholder="<?php echo esc_html_x('Your Email', 'placeholder', 'direo'); ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="vb_username" id="vb_username" value=""
                                           placeholder="<?php echo esc_html_x('Choose Username', 'placeholder', 'direo'); ?>"
                                           class="form-control"/>
                                    <span class="help-block">
                                        <?php esc_html_e('Please use only a-z,A-Z,0-9,dash and underscores', 'direo'); ?>
                                    </span>
                                </div>

                                <?php
                                if (!empty($display_password_reg)) { ?>
                                    <div class="form-group">
                                        <input type="password" name="vb_password" id="vb_password" value=""
                                               placeholder="<?php echo esc_html_x('Password', 'placeholder', 'direo'); ?>"
                                               class="form-control"/>
                                    </div>
                                    <?php
                                }

                                wp_nonce_field('vb_new_user', 'vb_new_user_nonce', true, true); ?>

                                <input type="submit" class="btn btn-block btn-lg btn-gradient btn-gradient-two"
                                       id="btn-new-user" value="<?php echo esc_attr($register); ?>"/>
                            </form>

                            <div class="indicator"><?php esc_html_e('Please wait...', 'direo'); ?></div>
                            <div class="alert result-message"></div>
                        </div>
                        <?php if ($social_login) { ?>
                            <p class="social-connector text-center"><?php esc_html_e('Or connect with', 'direo'); ?></p>
                            <div class="social-login">
                                <?php do_action('atbdp_before_login_form_end'); ?>
                            </div>
                            <?php
                        } ?>
                        <div class="form-excerpts">
                            <ul class="list-unstyled">
                                <li><?php esc_html_e('Already a member? ', 'direo'); ?>
                                    <a href="<?php echo ATBDP_Permalink::get_login_page_link(); ?>" class="access-link"
                                       data-toggle="modal"
                                       data-target="#login_modal"
                                       data-dismiss="modal"><?php echo esc_attr($login); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
wp_footer(); ?>
</body>

</html>
