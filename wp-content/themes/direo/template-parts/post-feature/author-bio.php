<?php
if (!empty(get_the_author_meta('description'))) { ?>
    <div class="post-author cardify border">
        <div class="author-thumb">
            <?php echo get_avatar(get_the_author_meta('email'), 100, null, null, array('class' => array('rounded-circle'))); ?>
        </div>
        <div class="author-info">

            <h5><?php echo esc_html__('About', 'direo'); ?> <span><?php the_author(); ?></span></h5>

            <p><?php the_author_meta('description'); ?></p>

            <?php if (function_exists('direo_author_social')) {
                direo_author_social();
            } ?>

        </div>
    </div>
    <?php
}