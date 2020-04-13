<?php
if (!class_exists('DWPP_Enqueuer')):
    class DWPP_Enqueuer
    {


        public function __construct()
        {
            // best hook to enqueue scripts for front-end is 'template_redirect'
            // 'Professional WordPress Plugin Development' by Brad Williams
            add_action('template_redirect', array($this, 'front_end_enqueue_scripts'));

        }


        /**
         * It loads all scripts for front end if the current post type is our custom post type
         * @param bool $force [optional] whether to load the style in the front end forcibly(even if the post type is not our custom post). It is needed for enqueueing file from a inside the short code call
         */
        public function front_end_enqueue_scripts($force = false)
        {
            // enqueue the style and the scripts on the page when the post type is our registered post type.
            // add scripts for adding the gallery if the user is not using our directoria theme.
            wp_register_style('DWPP_main_css', DWPP_ASSETS . 'css/main.css', false, DWPP_VERSION);
            wp_register_script('main', DWPP_ASSETS . 'js/main.js', array('jquery'), DWPP_VERSION, true);
            wp_enqueue_script('main');
            wp_enqueue_style('DWPP_main_css');
        }

    }

endif;