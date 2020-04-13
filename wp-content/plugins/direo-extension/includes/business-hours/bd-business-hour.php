<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

final class BD_Business_Hour
{

    /** Singleton *************************************************************/

    /**
     * @var BD_Business_Hour The one true BD_Business_Hour
     * @since 1.0
     */
    private static $instance;

    /**
     * Main BD_Business_Hour Instance.
     *
     * Insures that only one instance of BD_Business_Hour exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @return object|BD_Business_Hour The one true BD_Business_Hour
     * @uses BD_Business_Hour::setup_constants() Setup the constants needed.
     * @uses BD_Business_Hour::includes() Include the required files.
     * @uses BD_Business_Hour::load_textdomain() load the language files.
     * @see  BD_Business_Hour()
     * @since 1.0
     * @static
     * @static_var array $instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof BD_Business_Hour)) {
            self::$instance = new BD_Business_Hour;
            self::$instance->setup_constants();

            //add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
            add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts'));
            add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts'));

            self::$instance->includes();


            // add settings fields for our custom settings sections
            add_filter('atbdp_edit_after_contact_info_fields', array(self::$instance, 'add_business_hour_fields'), 10, 2);
            //register business hour widgets
            add_action('widgets_init', array(self::$instance, 'register_widget'));

            add_action('atbdp_after_contact_listing_owner_section', array(self::$instance, 'show_listings_business_hours'));

            // Add setting section to the Directorist settings page.
            add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'add_settings_to_ext_submenu'));
            // Add Settings fields to the extension general fields
            add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_to_ext_general_fields'));

            add_shortcode('directorist_business_hours', array(self::$instance, 'display_listings_business_hours'));

            add_action('atbdp_listing_updated', array(self::$instance, 'save_business_hours_data'));
            add_action('atbdp_listing_inserted', array(self::$instance, 'save_business_hours_data'));
            add_action('edit_post', array(self::$instance, 'dbh_save_meta_data'));

            add_filter('atbdp_listing_search_query_argument', array(self::$instance, 'businessHours_listing_search_query_argument'));

        }

        return self::$instance;
    }

    public function businessHours_listing_search_query_argument($args)
    {
        //filter by open now business
        if (isset($_GET['open_now']) && ($_GET['open_now'] == 'open_now')) {
            $listings = get_atbdp_listings_ids();
            if ($listings->have_posts()) {
                $closed = array();
                while ($listings->have_posts()) {
                    $listings->the_post();
                    $id = get_the_ID();
                    $business_hours = get_post_meta($id, '_bdbh', true);
                    $always_open = get_post_meta($id, '_enable247hour', true);
                    $no_time = get_post_meta($id, '_disable_bz_hour_listing', true);
                    $business_hours = !empty($business_hours) ? atbdp_sanitize_array($business_hours) : array();
                    $_day = '';
                    foreach ($business_hours as $day => $time) {
                        if (empty($time)) continue; // do not print the day if time is not set
                        $day_ = date('D');
                        $timezone = get_directorist_option('timezone');
                        $timezone = !empty($timezone) ? $timezone : 'America/New_York';
                        $listing_timezone = get_post_meta(get_the_ID(), '_timezone', true);
                        $timezone = !empty($listing_timezone) ? $listing_timezone : $timezone;
                        $interval = DateTime::createFromFormat('H:i a', '11:59 am');
                        switch ($day_) {
                            case 'Sat' :
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }

                                break;
                            case 'Sun' :
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }


                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }

                                }

                                break;
                            case 'Mon' :
                                $start_time = date('h:i a', strtotime(esc_attr($time['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($time['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }


                                break;
                            case 'Tue' :
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }

                                break;
                            case 'Wed' :
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }

                                break;
                            case 'Thu' :
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }

                                break;
                            case 'Fri':
                                $start_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['start'])));
                                $close_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['close'])));
                                $dt = new DateTime('now', new DateTimezone($timezone));
                                $current_time = $dt->format('g:i a');
                                $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                                $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                                if (1 == $remain_close) {
                                    $_day = false;
                                } elseif (!empty($always_open)) {
                                    $_day = true;
                                } elseif ($open_all_day) {
                                    $_day = true;
                                } else {
                                    /*
                                  * time start as pm (12.01 pm to 11.59 pm)
                                  * lets calculate time
                                  * is start time is smaller than current time and grater than close time
                                  */
                                    if ($interval < $time_now) {
                                        //pm
                                        if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                            $_day = true;
                                        } elseif ($time_now > $time_end) {
                                            if (($time_end < $time_start) && ($time_start < $time_now)) {
                                                $_day = true;
                                            } else {
                                                $_day = false;
                                            }
                                        }

                                    } else {
                                        //am
                                        //is the business start in a pm time
                                        if ((($time_start && $time_end) < $interval)) {
                                            if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                $_day = true;

                                            }
                                        } else {
                                            if ($time_end < $interval) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            }
                                        }
                                    }
                                    if (($time_now > $time_start) && ($time_now < $time_end)) {
                                        $_day = true;
                                    }
                                }

                                break;
                        }

                    }

                    if (empty($_day)) {
                        $closed[] = get_the_ID();
                    }
                }

                $closed_id = array(
                    'post__not_in' => !empty($closed) ? $closed : array()
                );
                $args = array_merge($args, $closed_id);
                return $args;

            }

        } else {
            return $args;
        }

    }

    private function __construct()
    {
        /*making it private prevents constructing the object*/

    }

    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @return void
     * @since 1.0
     * @access protected
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }

    /**
     * Disable unserializing of the class.
     *
     * @return void
     * @since 1.0
     * @access protected
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }

    public function dbh_save_meta_data($post_id)
    {

        if (is_admin()) {
            $timezone = !empty($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : '';
            if (!empty($timezone)) {
                update_post_meta($post_id, '_timezone', $timezone);
            }
        }


    }

    /**
     * @since 1.4.0
     */
    public function save_business_hours_data($listing_id)
    {

        $timezone = !empty($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : '';
        update_post_meta($listing_id, '_timezone', $timezone);

    }

    /**
     * @since 5.0.5
     */
    public function show_listings_business_hours($listing_id)
    {
        if ((BDBH_VERSION < '2.2.8') && (ATBDP_VERSION < '5.0.5')) {
            return;
        } elseif ((BDBH_VERSION >= '2.2.8') && (ATBDP_VERSION < '5.0.5')) {
            return;
        } elseif ((BDBH_VERSION < '2.2.8') && (ATBDP_VERSION >= '5.0.5')) {
            return;
        } else {
            $text247 = get_directorist_option('text247', __('Open 24/7', 'direo-extension')); // text for 24/7 type listing
            $business_hour_title = get_directorist_option('business_hour_title', __('Business Hour', 'direo-extension')); // text Business Hour Title
            $atbh_display_single_listing = get_directorist_option('atbh_display_single_listing', 1);

            $bdbh = get_post_meta($listing_id, '_bdbh', true);
            $enable247hour = get_post_meta($listing_id, '_enable247hour', true);
            $disable_bz_hour_listing = get_post_meta($listing_id, '_disable_bz_hour_listing', true);
            $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
            $fm_plan = get_post_meta($listing_id, '_fm_plans', true);

            // if business hour is active then add the following markup...

            $plan_hours = true;
            $allow_hours = apply_filters('atbdp_single_listing_business_hours', true);
            if (is_fee_manager_active()) {
                $plan_hours = is_plan_allowed_business_hours($fm_plan);
            }
            if (is_business_hour_active() && $allow_hours && $plan_hours && !empty($atbh_display_single_listing) && empty($disable_bz_hour_listing) && (!is_empty_v($business_hours) || !empty($enable247hour))) {
                $this->show_business_hour_module($business_hours, $business_hour_title, $enable247hour); // show the business hour in an unordered list
            }
        }

    }


    /**
     * It displays settings for the
     * @param $screen  string  get the current screen
     * @since 2.0.0
     */
    public function load_needed_scripts($screen)
    {
        $listing_form_page = get_directorist_option('add_listing_page');
        global $post;
        $page = $post ? $post->ID : '';
        //@todo later need to load the stylesheet only for this page
        if (is_rtl()) {
            wp_enqueue_style('bdbh_main_style_rtl', plugin_dir_url(__FILE__) . '/assets/css/bh-main-rtl.css');
        } else {
            wp_enqueue_style('bdbh_main_style2', plugin_dir_url(__FILE__) . '/assets/css/bh-main.css');
        }
        $post_type = get_post_type(get_the_ID());
        if (('post-new.php' == $screen) || ('post.php' == $screen) || ($post_type == 'at_biz_dir') || ($page == $listing_form_page)) {
            wp_enqueue_style('bdbh_main_style', plugin_dir_url(__FILE__) . '/assets/css/bh-main.css');
            wp_enqueue_script('bdbh_main_script', plugin_dir_url(__FILE__) . '/assets/js/main.js');
        }
    }

    /**
     * It displays settings for the
     * @param $settings_submenus array The array of the settings menu of Directorist
     * @return array
     */
    public function add_settings_to_ext_submenu($settings_submenus)
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $items = array();
        foreach ($timezones as $key => $timezone) {
            $items[] = array(
                'value' => $timezone,
                'label' => $timezone,
            );
        }
        /*lets add a submenu of our extension*/
        $settings_submenus[] = array(
            'title' => __('Business Hour', 'direo-extension'),
            'name' => 'business_hour_submenu',
            'icon' => 'font-awesome:fa-clock-o',
            'controls' => array(
                'general_section' => array(
                    'type' => 'section',
                    'title' => __('Business Hour Settings', 'direo-extension'),
                    'description' => __('You can Customize all the settings of Business Hour Extension here', 'direo-extension'),
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'open_badge_text',
                            'label' => __('Open Badge Text', 'direo-extension'),
                            'default' => __('Open Now', 'direo-extension'),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'close_badge_text',
                            'label' => __('Closed Badge Text', 'direo-extension'),
                            'default' => __('Closed Now', 'direo-extension'),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'business_hour_title',
                            'label' => __('Title for Business Hour', 'direo-extension'),
                            'default' => __('Business Hour', 'direo-extension'),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'text247',
                            'label' => __('24/7 Type Listing Description', 'direo-extension'),
                            'description' => __('You can set the text for listing that is open 24 hours a day and 7 days a week here.', 'direo-extension'),
                            'default' => __('Open 24/7', 'direo-extension'),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'atbh_time_format',
                            'label' => __('Time Format', 'direo-extension'),
                            'items' => array(
                                array(
                                    'value' => '12',
                                    'label' => __('12 Hours', 'direo-extension'),
                                ),
                                array(
                                    'value' => '24',
                                    'label' => __('24 Hours', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => '12',
                                'label' => __('12 Hours', 'direo-extension'),
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'timezone',
                            'label' => __('Default Timezone', 'direo-extension'),
                            'items' => $items,
                            'default' => __('America/New_York', 'direo-extension'),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'atbh_display_single_listing',
                            'label' => __('Display Business Hours on Single Listing', 'direo-extension'),
                            'default' => 1,
                        ),

                    ),// ends fields array
                ), // ends general section array
            ), // ends controls array that holds an array of sections
        );
        return $settings_submenus;


    }


    /**
     * It adds custom settings field of Directorist Business Hour to the General Settings Sections Under the Extension menu
     * of Directorist.
     * @param $fields array
     * @return array
     */
    public function add_settings_to_ext_general_fields($fields)
    {
        $ebh = array(
            'type' => 'toggle',
            'name' => 'enable_business_hour',
            'label' => __('Business Hour', 'direo-extension'),
            'description' => __('Allow users add and display business hour for a listing.', 'direo-extension'),
            'default' => 1,
        );
        // lets push our settings to the end of the other settings field and return it.
        array_push($fields, $ebh);

        return $fields;

    }


    /**
     *It registers business hours widget
     */
    public function register_widget()
    {

        register_widget('BD_Business_Hour_Widget');
    }

    /* Show business hours on single listing page
     * @param string $business_hours The array of the business days and hours
     * @param string $business_hour_title The business hour title
     * @param string $enable247hour 24/7 open status*/
    public function show_business_hour_module($business_hours, $business_hour_title, $enable247hour)
    {
        ?>
        <div class="atbd_content_module">
            <div class="atbd_content_module__tittle_area">
                <div class="atbd_area_title">
                    <h4>
                        <span class="<?php atbdp_icon_type(true); ?>
-calendar-o"></span><?php echo esc_html($business_hour_title); ?>
                    </h4>
                </div>
                <?php //lets check is it 24/7
                if (!empty($enable247hour)) {
                    ?>
                    <div class="atbd_upper_badge">
                        <span class="atbd_badge atbd_badge_open"><?php echo get_directorist_option('open_badge_text', __('Open', 'direo-extension')) ?></span>
                    </div><!-- END /.atbd_upper_badge -->
                    <?php
                } else {
                    echo $this::instance()->show_business_open_close($business_hours); // show the business hour in an unordered list
                } ?>
            </div>

            <div class="atbdb_content_module_contents">
                <div class="atbd_directory_open_hours">
                    <?php
                    if ($enable247hour) {
                        $text = get_directorist_option('text247', __("Open 24/7 in a week", 'direo-extension'));
                        echo $text;
                    } else {
                        // if 24 hours 7 days open then show it only, otherwise, show the days and its opening time.
                        $this::instance()->show_business_hour($business_hours);
                    }
                    ?>
                </div> <!--ends .directory_open_hours -->
            </div>
        </div>

        <?php
    }

    /**
     * It displays business hours in an unordered list
     * @param array $business_hours The array of the business days and hours
     */
    public function show_business_hour($business_hours)
    {
        // if 24 hours 7 days open then show it only, otherwise, show the days and its opening time.
        ?>
        <ul>
            <?php
            if (!empty($business_hours)) {
                foreach ($business_hours as $day => $time) {
                    if (empty($time)) continue; // do not print the day if time is not set
                    $day_ = date('D');
                    $_day = '';
                    $timezone = get_directorist_option('timezone');
                    $timezone = !empty($timezone) ? $timezone : 'America/New_York';
                    $listing_timezone = get_post_meta(get_the_ID(), '_timezone', true);
                    $timezone = !empty($listing_timezone) ? $listing_timezone : $timezone;
                    $interval = DateTime::createFromFormat('H:i a', '11:59 am');
                    switch ($day_) {
                        case 'Sat' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                             * time start as pm (12.01 pm to 11.59 pm)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Sun' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                             * time start as pm (12.01 pm to 11.59 pm)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');

                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Mon' :
                            $start_time = date('h:i a', strtotime(esc_attr($time['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($time['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                              * time start as pm (12.01 pm to 11.59 pm)
                              * lets calculate time
                              * is start time is smaller than current time and grater than close time
                              */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Tue' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                             * time start as pm (12.01 pm to 11.59 pm)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Wed' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                             * time start as pm (12.01 pm to 11.59 pm)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Thu' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                              * time start as pm (12.01 pm to 11.59 pm)
                              * lets calculate time
                              * is start time is smaller than current time and grater than close time
                              */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                        case 'Fri' :
                            $start_time = date('h:i a', strtotime(esc_attr($business_hours['friday']['start'])));
                            $close_time = date('h:i a', strtotime(esc_attr($business_hours['friday']['close'])));
                            $dt = new DateTime('now', new DateTimezone($timezone));
                            $current_time = $dt->format('g:i a');
                            $time_now = DateTime::createFromFormat('H:i a', $current_time);
                            $time_start = DateTime::createFromFormat('H:i a', $start_time);
                            $time_end = DateTime::createFromFormat('H:i a', $close_time);
                            /*
                             * time start as pm (12.01 pm to 11.59 pm)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if ($interval < $time_now) {
                                //pm
                                if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $_day = date('D');
                                } elseif ($time_now > $time_end) {
                                    if (($time_end < $time_start) && ($time_start < $time_now)) {
                                        $_day = date('D');
                                    } else {
                                        $_day = 'cls';
                                    }
                                }

                            } else {
                                //am
                                //is the business start in a pm time
                                if ((($time_start && $time_end) < $interval)) {
                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                        $_day = date('D');
                                    }
                                } else {
                                    if ($time_end < $interval) {
                                        if (($time_start > $time_now) && ($time_now < $time_end)) {
                                            $_day = date('D');
                                        }
                                    }

                                }

                            }
                            /*
                             * time start as am (12.01 am to 11.58 am)
                             * lets calculate time
                             * is start time is smaller than current time and grater than close time
                             */
                            if (($time_now > $time_start) && ($time_now < $time_end)) {
                                $_day = date('D');
                            }
                            $open_all_day = (!empty($time['remain_close']) && $time['remain_close'] === 'open') ? 1 : '';
                            if ($open_all_day == 1) {
                                $_day = date('D');
                            }
                            break;
                    }
                    $start_time = date('h:i a', strtotime(!empty($time['start']) ? esc_attr($time['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($time['close']) ? esc_attr($time['close']) : ''));
                    $time_format = get_directorist_option('atbh_time_format', '12');
                    if ('24' === $time_format) {
                        $start_time = DateTime::createFromFormat('H:i a', $start_time)->format('H:i');
                        $close_time = DateTime::createFromFormat('H:i a', $close_time)->format('H:i');
                    }

                    $result = substr($day, 0, 3);
                    // $remain_close = !empty($time['remain_close']) ? 1 : '';
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    if ($_day == ucwords($result)) {
                        $open_close_focus = 'class="atbd_today"';
                    } else {
                        $open_close_focus = 'class="atbd_open"';
                    }
                    if (1 == $remain_close) {
                        $open_close_focuss = 'class="atbd_closed"';
                    }
                    ?>
                    <li <?php echo ($remain_close) ? $open_close_focuss : $open_close_focus; ?>>
                        <?php
                        $days = esc_html(ucfirst($day));
                        printf('<span class="day">%s</span>', __($days, 'direo-extension')); ?>
                        <?php
                        //$remain_close = !empty($time['remain_close']) ? 1 : '';
                        if (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) {
                            ?>
                            <span><?php _e('Closed', 'direo-extension') ?></span>
                            <?php
                        } else {
                            ?>
                            <div class="atbd_open_close_time">
                                <?php
                                if (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) {
                                    ?>
                                    <span class="time">
                                        <?php echo __('Open 24h', 'direo-extension'); ?>
                                </span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="time"><?php echo $start_time; ?></span> - <span
                                            class="time"><?php echo $close_time; ?>
                                </span>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </li>
                <?php }
            } ?>
        </ul>
        <?php

    }

    /**
     * It displays business hours in an unordered list
     * @param array $business_hours The array of the business days and hours
     */
    public function show_business_open_close($business_hours, $echo = false)
    {
        $open_ = get_directorist_option('open_badge_text', __('Open', 'direo-extension'));
        $close_ = get_directorist_option('close_badge_text', 'Closed', 'direo-extension');
        foreach ($business_hours as $day => $time) {
            if (empty($time)) continue; // do not print the day if time is not set
            $day_ = date('D');
            $timezone = get_directorist_option('timezone');
            $timezone = !empty($timezone) ? $timezone : 'America/New_York';
            $listing_timezone = get_post_meta(get_the_ID(), '_timezone', true);
            $timezone = !empty($listing_timezone) ? $listing_timezone : $timezone;
            $interval = DateTime::createFromFormat('H:i a', '11:59 am');
            $open_close = '';
            switch ($day_) {
                case 'Sat' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['saturday']['start']) ? esc_attr($business_hours['saturday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['saturday']['close']) ? esc_attr($business_hours['saturday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone)); //need to take input from user
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;
                case 'Sun' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['sunday']['start']) ? esc_attr($business_hours['sunday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['sunday']['close']) ? esc_attr($business_hours['sunday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }


                    break;
                case 'Mon' :
                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['monday']['start'])));
                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['monday']['close'])));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;
                case 'Tue' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['tuesday']['start']) ? esc_attr($business_hours['tuesday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['tuesday']['close']) ? esc_attr($business_hours['tuesday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;
                case 'Wed' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['wednesday']['start']) ? esc_attr($business_hours['wednesday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['wednesday']['close']) ? esc_attr($business_hours['wednesday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;
                case 'Thu' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['thursday']['start']) ? esc_attr($business_hours['thursday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['thursday']['close']) ? esc_attr($business_hours['thursday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;
                case 'Fri' :
                    $start_time = date('h:i a', strtotime(!empty($business_hours['friday']['start']) ? esc_attr($business_hours['friday']['start']) : ''));
                    $close_time = date('h:i a', strtotime(!empty($business_hours['friday']['close']) ? esc_attr($business_hours['friday']['close']) : ''));
                    $dt = new DateTime('now', new DateTimezone($timezone));
                    $current_time = $dt->format('g:i a');
                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                    $_day = date('D');
                    $remain_close = (!empty($time['remain_close']) && (($time['remain_close'] === 'on') || ($time['remain_close'] === '1'))) ? 1 : '';
                    $open_all_day = (!empty($time['remain_close']) && ($time['remain_close'] === 'open')) ? 1 : '';
                    if (1 == $remain_close) {
                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                    } elseif ($open_all_day) {
                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                    } else {
                        if ($interval < $time_now) {
                            //pm
                            if (($time_start < $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                            } elseif ($time_now > $time_end) {
                                if (($time_end < $time_start) && ($time_start < $time_now)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                            }

                        } else {
                            //am
                            //is the business start in a pm time
                            if ((($time_start && $time_end) < $interval)) {
                                if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                    $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                } else {
                                    $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                }
                            } else {
                                if ($time_end < $interval) {
                                    if (($time_start > $time_now) && ($time_now < $time_end) && ($time_start != $time_end)) {
                                        $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                                    } else {
                                        $open_close = '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                                    }
                                }

                            }

                        }
                        if (($time_now > $time_start) && ($time_now < $time_end) && ($time_start != $time_end)) {
                            $open_close = '<span class="atbd_badge atbd_badge_open">' . $open_ . '</span>';
                        }
                    }

                    break;

            }
            $result = substr($day, 0, 3);
            if ($_day == ucwords($result)) {
                if ($echo) {
                    echo !empty($open_close) ? $open_close : '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                } else {
                    return !empty($open_close) ? $open_close : '<span class="atbd_badge atbd_badge_close">' . $close_ . '</span>';
                }
            }
        }

    }


    /**
     * It gets the business hours of the given listing/post
     * @param int $post_id The ID of the listing
     * @return array It returns the business hours if found, else an empty array
     */
    public function get_business_hours($post_id)
    {
        $lf = get_post_meta($post_id, '_bdbh', true);
        $listing_info = (!empty($lf)) ? aazztech_enc_unserialize($lf) : array();
        return !empty($listing_info['bdbh']) ? atbdp_sanitize_array($listing_info['bdbh']) : array(); // arrays of days and times

    }


    /**
     * It gets the business hours settings of the given listing/post
     * @param int $post_id The ID of the listing
     * @return array It returns the business hours settings if found, else an empty array.
     */
    public function get_business_hours_settings($post_id)
    {
        $lf = get_post_meta($post_id, '_listing_info', true);
        $listing_info = (!empty($lf)) ? aazztech_enc_unserialize($lf) : array();
        return !empty($listing_info['bdbh_settings']) ? atbdp_sanitize_array($listing_info['bdbh_settings']) : array(); // arrays of settings

    }


    /**
     * It adds the business hour input fields to the add listing page
     * @param string $page_type the type of of business directory page we are hooking into
     * @param array $listing_info All the information about the current listing.
     */
    public function add_business_hour_fields($page_type, $listing_info)
    {
        if (!get_directorist_option('enable_business_hour')) return; // vail if the business hour is not enabled
        self::$instance->load_template('business-hour-fields', array('listing_ino' => $listing_info));
    }

    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     */
    public function load_template($name, $args = array())
    {
        global $post;
        include(BDBH_TEMPLATES_DIR . $name . '.php');
    }

    /**
     * It register the text domain to the WordPress
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('direo-extension', false, BDBH_LANG_DIR);
    }

    /**
     * It Includes and requires necessary files.
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function includes()
    {
        require_once BDBH_INC_DIR . 'helper-functions.php';
        require_once BDBH_DIR . 'widgets/class-widget.php';
    }


    /**
     * Setup plugin constants.
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function setup_constants()
    {
        require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
    }

    /*
     * display listings business hours via shortocde
     * */
    public function display_listings_business_hours()
    {
        global $post;
        $listing_id = $post->ID;
        ob_start();
        if (is_singular(ATBDP_POST_TYPE)) {
            do_action('atbdp_after_contact_listing_owner_section', $listing_id);
        }
        return ob_get_clean();
    }
}


/**
 * The main function for that returns BD_Business_Hour
 *
 * The main function responsible for returning the one true BD_Business_Hour
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @return object|BD_Business_Hour The one true BD_Business_Hour Instance.
 * @since 1.0
 */
function BD_Business_Hour()
{
    return BD_Business_Hour::instance();
}

//  Directorist Stripe gateway Instantiate only if our directorist plugin is active
if (in_array('directorist/directorist-base.php', (array)get_option('active_plugins'))) {
    BD_Business_Hour(); // get the plugin running
}
