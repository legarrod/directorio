<?php

/*
direo All Custom Widget
*/


//**************************************************************************************
// Subscribe for blog
//**************************************************************************************

class direo_subscribe_widget extends WP_Widget
{
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_subscribe_widget',
            'description' => esc_html__('You can use it to display Subscribe form.', 'direo-core')
        );

        parent::__construct('direo_subscribe_widget', esc_html__('-[Subscribe]', 'direo-core'), $widget_details);

    }

    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $form = !empty($instance['form']) ? $instance['form'] : ''; ?>

        <div class="widget-wrapper">
            <div class="widget-default">
                <div class="widget-header">
                    <h6 class="widget-title"><?php echo esc_html($title); ?></h6>
                </div>
                <div class="widget-content">
                    <div class="subscribe-widget">
                        <form action="<?php echo esc_url($form); ?>" method="get">
                            <input type="email" class="form-control m-bottom-20"
                                   placeholder="<?php echo esc_attr_x('Enter email', 'placeholder', 'direo-core'); ?>">
                            <button class="btn btn-sm btn-primary shadow-none"
                                    type="submit"><?php esc_html_e('Subscribe', 'direo-core'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }

    public function form($instance)
    {
        $title = !empty($instance["title"]) ? $instance["title"] : '';
        $form = !empty($instance["form"]) ? $instance["form"] : ''; ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("title")); ?>">
                <b><?php esc_html_e("Title", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("title")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("title")); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("form")); ?>">
                <b><?php esc_html_e("Mailchimp Action Url", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("form")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("form")); ?>" type="text"
                   value="<?php echo esc_attr($form); ?>"/>
        </p>


        <p class="help">
            <?php printf(esc_html__('You can edit or create your subscribe form in the <a href="%s">%s</a>.', 'direo-core'), admin_url('admin.php?page=wpcf7'), esc_html__('Contact form settings', 'direo-core')); ?>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {

        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['form'])) {
            $new_instance['form'] = sanitize_text_field($new_instance['form']);
        }

        return $new_instance;
    }
}


//**************************************************************************************
// Popular post
//**************************************************************************************


class direo_popular_post_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_popular_post_widget',
            'description' => esc_html__('You can use it to display popular post.', 'direo-core')
        );
        parent::__construct('direo_popular_post_widget', esc_html__('-[Popular Blog]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    {
        $title = !empty($instance["title"]) ? $instance["title"] : '';
        $post_count = !empty($instance["post_count"]) ? $instance["post_count"] : '';

        // Popular posts
        query_posts(
            array(
                'posts_per_page' => $post_count,
                'post_type' => 'post',
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'post__not_in' => get_option('sticky_posts'),
                'order' => 'DESC',
            )
        ); ?>

        <div class="widget-wrapper">
            <div class="widget-default">
                <?php if (!empty($title)) { ?>
                    <div class="widget-header">
                        <h6 class="widget-title"><?php echo esc_html($title); ?></h6>
                    </div>
                <?php } ?>
                <?php if (have_posts()) { ?>
                    <div class="widget-content">
                        <div class="sidebar-post">
                            <?php while (have_posts()) {
                                the_post(); ?>
                                <div class="post-single">
                                    <div class="d-flex align-items-center">
                                        <?php the_post_thumbnail(array(60, 60), array('class' => 'rounded')); ?>
                                        <p>
                                            <a href="<?php the_permalink(); ?>"
                                               class="post-title"><?php the_title() ?></a>
                                            <span><?php echo function_exists('direo_time_link') ? direo_time_link() : ''; ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php }
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="widget-content">
                        <div class="sidebar-post"><h4> <?php esc_html_e('No Post Found.', 'direo-core'); ?> </h4></div>
                    </div>
                <?php }
                wp_reset_query(); ?>
            </div>
        </div>
        <?php
    }

    public function form($instance)
    {
        $title = !empty($instance["title"]) ? $instance["title"] : '';
        $post_count = !empty($instance["post_count"]) ? $instance["post_count"] : '';
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("title")); ?>">
                <b><?php esc_html_e("Title", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("title")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("title")); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("post_count")); ?>">
                <b><?php esc_html_e("How many posts you want to show ?", "direo"); ?></b>
            </label>

            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("post_count")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("post_count")); ?>"
                   type="text" value="<?php echo esc_attr($post_count); ?> "/>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['post_count'])) {
            $new_instance['post_count'] = sanitize_text_field($new_instance['post_count']);
        }

        return $new_instance;
    }
}


//**************************************************************************************
// Latest post
//**************************************************************************************


class direo_latest_post_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_latest_post_widget',
            'description' => esc_html__('You can use it to display latest post.', 'direo-core')
        );
        parent::__construct('direo_latest_post_widget', esc_html__('-[Latest Blog]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    {
        $title = !empty($instance["title"]) ? $instance["title"] : '';
        $post_count = !empty($instance["post_count"]) ? $instance["post_count"] : '';


        // Popular posts
        query_posts(
            array(
                'posts_per_page' => $post_count,
                'post_type' => 'post',
                'post__not_in' => get_option('sticky_posts'),
                'order' => 'DESC',
            )
        );
        ?>

        <div class="widget-wrapper">
            <div class="widget-default">
                <?php if (!empty($title)) { ?>
                    <div class="widget-header">
                        <h6 class="widget-title"><?php echo esc_html($title); ?></h6>
                    </div>
                <?php } ?>
                <?php if (have_posts()) { ?>
                    <div class="widget-content">
                        <div class="sidebar-post">
                            <?php while (have_posts()) {
                                the_post(); ?>
                                <div class="post-single">
                                    <div class="d-flex align-items-center">
                                        <?php the_post_thumbnail(array(60, 60), array('class' => 'rounded')); ?>
                                        <p>
                                            <a href="<?php the_permalink(); ?>"
                                               class="post-title"><?php the_title() ?></a>
                                            <span><?php echo function_exists('direo_time_link') ? direo_time_link() : ''; ?></span>

                                        </p>
                                    </div>
                                </div><!-- ends: .post-single -->
                            <?php }
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="widget-content">
                        <div class="sidebar-post"><h4> <?php esc_html_e('No Post Found.', 'direo-core'); ?> </h4></div>
                    </div>
                <?php }
                wp_reset_query(); ?>
            </div>
        </div>
        <?php
    }

    public function form($instance)
    {
        $title = !empty($instance["title"]) ? $instance["title"] : '';
        $post_count = !empty($instance["post_count"]) ? $instance["post_count"] : '';
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("title")); ?>">
                <b><?php esc_html_e("Title", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("title")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("title")); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("post_count")); ?>">
                <b><?php esc_html_e("How many posts you want to show ?", "direo"); ?></b>
            </label>

            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("post_count")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("post_count")); ?>"
                   type="text" value="<?php echo esc_attr($post_count); ?> "/>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['post_count'])) {
            $new_instance['post_count'] = sanitize_text_field($new_instance['post_count']);
        }

        return $new_instance;
    }
}


//**************************************************************************************
// Social
//**************************************************************************************

class direo_connect_follow_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_connect_follow_widget',
            'description' => esc_html__('You can use it to display social profile with icon.', 'direo-core')
        );
        parent::__construct('direo_connect_follow_widget', esc_html__('-[Social Icon]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    {
        $title = (!empty($instance['title'])) ? $instance['title'] : '';

        ?>
        <div class="widget-wrapper">
            <div class="widget-default">
                <?php if (!empty($title)) { ?>
                    <div class="widget-header">
                        <h6 class="widget-title"><?php echo esc_attr($title); ?></h6>
                    </div>
                <?php } ?>
                <div class="widget-content">
                    <div class="social social--small social--gray ">
                        <ul class="d-flex flex-wrap">
                            <?php for ($i = 1; $i <= $instance["social"]; $i++) {

                                $link_text = !empty($instance["link_text$i"]) ? $instance["link_text$i"] : '';
                                $link_url = !empty($instance["link_url$i"]) ? $instance["link_url$i"] : '';
                                if ($link_text): ?>
                                    <li>
                                        <a href="<?php echo esc_url($link_url); ?>"
                                           class="<?php echo esc_attr($link_text) ?>">
                                            <span class="fa fa-<?php echo esc_attr($link_text) ?>"></span>
                                        </a>
                                    </li>
                                <?php endif;

                            }
                            wp_reset_postdata(); ?>

                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <?php

    }

    public function form($instance)
    {
        $title = (!empty($instance['title'])) ? $instance['title'] : '';
        $social = !empty($instance["social"]) ? $instance["social"] : '';
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("title")); ?>">
                <b><?php esc_html_e("Title", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("title")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("title")); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id("social")); ?>">
                <b><?php esc_html_e("How many social field would you want? & hit save.", "direo"); ?></b>
            </label>
        </p>

        <p><input class="widefat" id="<?php echo esc_attr($this->get_field_id("social")); ?>"
                  name="<?php echo esc_attr($this->get_field_name("social")); ?>" type="text"
                  value="<?php echo esc_attr($social); ?>"/>
        </p>

        <?php
        if (!empty($social)) {
            printf("<p style='font-size: 12px; color: #9d9d9d; font-style: italic'>%s | <a href='https://fontawesome.com/icons?d=listing&m=free' target='_blank'>%s</a></p>", esc_html__('Please Note: Social Icon Names are just Fonts Awesome Icon Name in lower case(eg. facebook-f or twitter etc)', 'direo-core'), esc_html__('Font Awesome Icons List', 'direo-core'));
            for ($i = 1; $i <= $social; $i++) {

                $link_text = !empty($instance["link_text$i"]) ? $instance["link_text$i"] : '';
                $link_url = !empty($instance["link_url$i"]) ? $instance["link_url$i"] : '';
                ?>

                <p style="border: 1px solid #f5548e; padding: 10px; ">
                    <label for="<?php echo esc_attr($this->get_field_id("link_text$i")); ?>"><?php echo "#$i : Social Icon Name"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("link_text$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("link_text$i")); ?>" type="text"
                           value="<?php echo esc_attr($link_text); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("link_url$i")); ?>"><?php echo "#$i : Social url"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("link_url$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("link_url$i")); ?>" type="text"
                           value="<?php echo esc_attr($link_url); ?>"/>
                </p>

            <?php }
            wp_reset_postdata();
        } ?>

        <?php
    }

    public function update($new_instance, $old_instance)
    {

        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['social'])) {
            $new_instance['social'] = sanitize_text_field($new_instance['social']);
        }

        return $new_instance;
    }
}


//**************************************************************************************
// search
//**************************************************************************************

class direo_search_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_search_widget',
            'description' => esc_html__('You can use it to display a search form.', 'direo-core')
        );
        parent::__construct('direo_search_widget', esc_html__('-[Search]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    {

        ?>
        <div class="widget-wrapper">
            <div class="search-widget">
                <form role="search" method="get" action="<?php echo esc_url(home_url()); ?>">
                    <div class="input-group">
                        <input type="search" value="<?php echo esc_attr(get_search_query()); ?>" name="s"
                               class="fc--rounded"
                               placeholder="<?php echo esc_attr_x('Search', 'placeholder', 'direo-core'); ?>">
                        <button value="search" type="submit"><i class="la la-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <?php

    }

    public function form($instance)
    {
        ?>
        <p><b><?php echo esc_html__('direo theme default search widget.', 'direo-core') ?></b></p>

        <?php
    }
}


//**************************************************************************************
// Social
//**************************************************************************************

class direo_social_profile_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_social_profile_widget',
            'description' => esc_html__('You can use it to display social profile with icon.', 'direo-core')
        );
        parent::__construct('direo_social_profile_widget', esc_html__('-[Social Icon & Title]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    {
        $title = (!empty($instance['title'])) ? $instance['title'] : ''; ?>

        <div class="widget atbd_widget widget_social">
            <?php if (!empty($title)) { ?>
                <div class="atbd_widget_title">
                    <h4><?php echo esc_attr($title); ?></h4>
                </div>
            <?php } ?>
            <ul class="list-unstyled social-list">
                <?php for ($i = 1; $i <= $instance["social"]; $i++) {

                    $s_title = !empty($instance["s_title$i"]) ? $instance["s_title$i"] : '';
                    $link_url = !empty($instance["link_url$i"]) ? $instance["link_url$i"] : '';
                    $link_text = !empty($instance["link_text$i"]) ? $instance["link_text$i"] : '';
                    $s_color = !empty($instance["s_color$i"]) ? $instance["s_color$i"] : '';

                    if ($link_text): ?>

                        <li>
                            <a href="<?php echo esc_url($link_url); ?>">
                                    <span class="instagram">
                                        <i class="fa fa-<?php echo esc_attr($link_text) ?>"
                                           style="color: <?php echo esc_attr($s_color); ?>"></i>
                                    </span>
                                <?php echo esc_html($s_title); ?>
                            </a>
                        </li>

                    <?php endif;

                }
                wp_reset_postdata(); ?>

            </ul>
        </div>

        <?php

    }

    public function form($instance)
    {
        $title = (!empty($instance['title'])) ? $instance['title'] : '';
        $social = !empty($instance["social"]) ? $instance["social"] : '';
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("title")); ?>">
                <b><?php esc_html_e("Title", "direo"); ?></b>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("title")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("title")); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id("social")); ?>">
                <b><?php esc_html_e("How many social field would you want? & hit save.", "direo"); ?></b>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("social")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("social")); ?>" type="text"
                   value="<?php echo esc_attr($social); ?>"/>
        </p>

        <?php
        if (!empty($social)) {
            printf("<p style='font-size: 12px; color: #9d9d9d; font-style: italic'>%s | <a href='https://fontawesome.com/icons?d=listing&m=free' target='_blank'>%s</a></p>", esc_html__('Please Note: Social Icon Names are just Fonts Awesome Icon Name in lower case(eg. facebook-f or twitter etc)', 'direo-core'), esc_html__('Font Awesome Icons List', 'direo-core'));
            for ($i = 1; $i <= $social; $i++) {

                $s_title = !empty($instance["s_title$i"]) ? $instance["s_title$i"] : '';
                $link_url = !empty($instance["link_url$i"]) ? $instance["link_url$i"] : '';
                $link_text = !empty($instance["link_text$i"]) ? $instance["link_text$i"] : '';
                $s_color = !empty($instance["s_color$i"]) ? $instance["s_color$i"] : '';
                ?>

                <p style="border: 1px solid #f5548e; padding: 10px; ">

                    <label for="<?php echo esc_attr($this->get_field_id("s_title$i")); ?>"><?php echo "#$i : Title"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("s_title$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("s_title$i")); ?>" type="text"
                           value="<?php echo esc_attr($s_title); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("link_url$i")); ?>"><?php echo "#$i : Social url"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("link_url$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("link_url$i")); ?>" type="text"
                           value="<?php echo esc_attr($link_url); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("link_text$i")); ?>"><?php echo "#$i : Social Icon Name"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("link_text$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("link_text$i")); ?>" type="text"
                           value="<?php echo esc_attr($link_text); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("s_color$i")); ?>"><?php echo "#$i : Icon Color"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("s_color$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("s_color$i")); ?>" type="color"
                           value="<?php echo esc_attr($s_color); ?>"/>
                </p>

            <?php }
            wp_reset_postdata();
        }

    }

    public function update($new_instance, $old_instance)
    {

        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['social'])) {
            $new_instance['social'] = sanitize_text_field($new_instance['social']);
        }

        return $new_instance;
    }
}


//**************************************************************************************
// Social
//**************************************************************************************

class direo_widget_button extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'direo_widget_button',
            'description' => esc_html__('You can use it to button with icon.', 'direo-core')
        );
        parent::__construct('direo_widget_button', esc_html__('-[Button]', 'direo-core'), $widget_details);
    }

    public function widget($args, $instance)
    { ?>
        <ul class="list-unstyled store-btns d-flex flex-wrap">
            <?php for ($i = 1; $i <= $instance["social"]; $i++) {

                $btn_text = !empty($instance["btn_text$i"]) ? $instance["btn_text$i"] : '';
                $btn_url = !empty($instance["btn_url$i"]) ? $instance["btn_url$i"] : '';
                $icon = !empty($instance["icon$i"]) ? $instance["icon$i"] : '';
                $btn_type = $i % 2 == 1 ? 'btn-gradient btn-gradient-two icon-left' : 'btn-dark btn-icon';
                if ($btn_text): ?>
                    <li>
                        <a href="<?php echo esc_url($btn_url); ?>"
                           class="btn btn-sm btn-icon <?php echo esc_html($btn_type); ?>">
                            <span class="fa fa-<?php echo esc_html($icon); ?>"></span>
                            <?php echo esc_html($btn_text); ?>
                        </a>
                    </li>

                <?php endif;
            }
            wp_reset_postdata(); ?>
        </ul>

        <?php
    }

    public function form($instance)
    {
        $social = !empty($instance["social"]) ? $instance["social"] : ''; ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id("social")); ?>">
                <b><?php esc_html_e("How many button you want? & hit save.", "direo"); ?></b>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id("social")); ?>"
                   name="<?php echo esc_attr($this->get_field_name("social")); ?>" type="text"
                   value="<?php echo esc_attr($social); ?>"/>
        </p>

        <?php
        if (!empty($social)) {
            printf("<p style='font-size: 12px; color: #9d9d9d; font-style: italic'>%s | <a href='https://fontawesome.com/icons?d=listing&m=free' target='_blank'>%s</a></p>", esc_html__('Please Note: Social Icon Names are just Fonts Awesome Icon Name in lower case(eg. facebook-f or twitter etc)', 'direo-core'), esc_html__('Font Awesome Icons List', 'direo-core'));
            for ($i = 1; $i <= $social; $i++) {

                $btn_text = !empty($instance["btn_text$i"]) ? $instance["btn_text$i"] : '';
                $btn_url = !empty($instance["btn_url$i"]) ? $instance["btn_url$i"] : '';
                $icon = !empty($instance["icon$i"]) ? $instance["icon$i"] : '';
                ?>
                <p style="border: 1px solid #f5548e; padding: 10px; ">
                    <label for="<?php echo esc_attr($this->get_field_id("btn_text$i")); ?>"><?php echo "#$i : Button Text"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("btn_text$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("btn_text$i")); ?>" type="text"
                           value="<?php echo esc_attr($btn_text); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("btn_url$i")); ?>"><?php echo "#$i : Button url"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("btn_url$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("btn_url$i")); ?>" type="text"
                           value="<?php echo esc_attr($btn_url); ?>"/>

                    <label for="<?php echo esc_attr($this->get_field_id("icon$i")); ?>"><?php echo "#$i : Social Icon Name"; ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id("icon$i")); ?>"
                           name="<?php echo esc_attr($this->get_field_name("icon$i")); ?>" type="text"
                           value="<?php echo esc_attr($icon); ?>"/>

                </p>

            <?php }
            wp_reset_postdata();
        } ?>

        <?php
    }

    public function update($new_instance, $old_instance)
    {

        if (!empty($new_instance['title'])) {
            $new_instance['title'] = sanitize_text_field($new_instance['title']);
        }
        if (!empty($new_instance['social'])) {
            $new_instance['social'] = sanitize_text_field($new_instance['social']);
        }

        return $new_instance;
    }
}


function direo_widgets_register()
{
    register_widget('direo_subscribe_widget');
    register_widget('direo_popular_post_widget');
    register_widget('direo_latest_post_widget');
    register_widget('direo_connect_follow_widget');
    register_widget('direo_widget_button');
    register_widget('direo_search_widget');
    register_widget('direo_social_profile_widget');
}

add_action('widgets_init', 'direo_widgets_register');