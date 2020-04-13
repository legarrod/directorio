<?php
if (has_post_thumbnail() && !post_password_required()) { ?>
    <figure>
        <?php the_post_thumbnail('direo_blog');?>
    </figure>
<?php }