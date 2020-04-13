<?php
$blog_style = get_theme_mod('blog_style', 'default');

if ('default' == $blog_style) { ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('blog-single'); ?>>
        <div class="card post--card post--card2 ">
            <?php if (has_post_thumbnail() && !post_password_required()) { ?>
                <figure>
                    <?php the_post_thumbnail('direo_blog'); ?>
                </figure>
            <?php }
            get_template_part('template-parts/common/blog', 'content'); ?>
        </div>
    </div>
    <?php
} else { ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('col-lg-4 col-md-6'); ?>>
        <div class="grid-single">
            <div class="card post--card shadow-sm">
                <?php if (has_post_thumbnail() && !post_password_required()) { ?>
                    <figure>
                        <?php the_post_thumbnail('direo_blog_grid'); ?>
                    </figure>
                <?php }
                get_template_part('template-parts/common/blog', 'content'); ?>
            </div>
        </div>
    </div>
    <?php
}