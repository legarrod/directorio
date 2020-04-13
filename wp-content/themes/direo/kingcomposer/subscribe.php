<?php
/*==========================================
    Shortcode : Subscribe
    Author URI: https://aazztech.com
============================================*/
$action = $btn = $title =  $subtitle = '';

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]); ?>

<section class="subscribe-wrapper <?php echo esc_attr(implode(' ', $wrap_class)) ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php echo sprintf('<h1>%s</h1>', esc_attr($title));
                echo sprintf('<p>%s</p>', esc_attr($subtitle)) ?>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-8 offset-sm-2">
                        <form action="<?php echo esc_url($action); ?>" method="post" class="subscribe-form m-top-40">
                            <div class="form-group">
                                <span class="la la-envelope-o"></span>
                                <input type="email"
                                       placeholder="<?php echo esc_attr_x('Enter your email', 'placeholder', 'direo'); ?>"
                                       value="" name="EMAIL" class="required email" id="mce-EMAIL" required>
                            </div>
                            <input type="submit" value="<?php echo esc_attr($btn); ?>"
                                   class="btn btn-gradient btn-gradient-one">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>