<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

final class Listings_fAQs
{


    /** Singleton *************************************************************/

    /**
     * @var Listings_fAQs The one true Listings_fAQs
     * @since 1.0
     */
    private static $instance;

    /**
     * Main Listings_fAQs Instance.
     *
     * Insures that only one instance of Listings_fAQs exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0
     * @static
     * @static_var array $instance
     * @uses Listings_fAQs::setup_constants() Setup the constants needed.
     * @uses Listings_fAQs::includes() Include the required files.
     * @uses Listings_fAQs::load_textdomain() load the language files.
     * @see  Listings_fAQs()
     * @return object|Listings_fAQs The one true Listings_fAQs
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Listings_fAQs)) {
            self::$instance = new Listings_fAQs;
            self::$instance->setup_constants();

           // add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
            add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts_admin'));
            add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts'));

            self::$instance->includes();

            //register business hour widgets
            /*
             * @todo later need to active the FAQs widget
             */
            add_action('widgets_init', array(self::$instance, 'register_widget'));

            // Add Settings fields to the extension general fields
                add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_to_ext_general_fields'));
            // Add Settings fields to the extension general fields
            add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'add_settings_for_faqs_submenue'));

            add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'atbdp_new_metabox'));
                add_filter('atbdp_after_contact_info_section', array(self::$instance, 'add_new_faq'), 10, 3);
                add_action('wp_ajax_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'));
                add_action('wp_ajax_nopriv_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'));
                add_action('atbdp_listing_faqs', array(self::$instance, 'atbdp_show_faqs'), 10, 2);
                add_shortcode('directorist_listing_faqs',array(self::$instance, 'atbdp_shortcode_faqa'));
        }

        return self::$instance;
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
     * @since 1.0
     * @access protected
     * @return void
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }

    /**
     * Disable unserializing of the class.
     *
     * @since 1.0
     * @access protected
     * @return void
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }


    /**
     * It displays settings for the
     * @param $settings_submenus array The array of the settings menu of Directorist
     * @return array
     */
    public function add_settings_for_faqs_submenue($settings_submenus)
    {
        /*lets add a submenu of our extension*/
        $settings_submenus[] = array(
            'title' => __('FAQs', 'direo-extension'),
            'name' => 'faqs_submenu',
            'icon' => 'font-awesome:fa-question',
            'controls' => array(
                'general_section' => array(
                    'type' => 'section',
                    'title' => __('FAQs Settings', 'direo-extension'),
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'faqs_ans_box',
                            'label' => __( 'Answer Field Type', 'direo-extension' ),
                            'items' => array(
                                array(
                                    'value' => 'wpeditor',
                                    'label' => __('WP Editor', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'normal',
                                    'label' => __('Textarea', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => 'normal',
                                'label' => __('Textarea', 'direo-extension'),
                            ),

                        )

                    ),// ends fields array
                ), // ends general section array
            ), // ends controls array that holds an array of sections
        );
        return $settings_submenus;


    }

    public function atbdp_show_faqs($post, $listing_info){
        $plan_faqs = true;
        if (is_fee_manager_active()){
            $plan_faqs =is_plan_allowed_listing_faqs(get_post_meta(get_the_ID(), '_fm_plans', true));
        }
        if ($plan_faqs){
            $faqs = !empty($listing_info['faqs'])?$listing_info['faqs']:array();
            if (!empty($faqs)){
                self::$instance->load_template('view-faqs', array( 'listing_faq' => $faqs, ));
            }
        }

    }
    public function atbdp_shortcode_faqa() {
        global $post;
        $listing_info['faqs'] = get_post_meta($post->ID, '_faqs', true);
        extract($listing_info);
        ob_start();
        if(is_singular(ATBDP_POST_TYPE)) {
            $plan_faqs = true;
            if (is_fee_manager_active()){
                $plan = get_post_meta($post->ID, '_fm_plans', true);
                $plan_faqs =is_plan_allowed_listing_faqs($plan);
            }
            if ($plan_faqs) {
                do_action('atbdp_listing_faqs', $post, $listing_info);
            }
        }
        return ob_get_clean();
    }


  public function atbdp_new_metabox(){
      add_meta_box( '_listing_faqs',
          __( 'Add FAQs for the Listing', 'direo-extension' ),
          array($this, 'add_new_faq_admin'),
          ATBDP_POST_TYPE,
          'normal', 'high' );
  }



    public function add_new_faq_admin($post)
    {
        if (!get_directorist_option('enable_faqs',1)) return; // vail if the business hour is not enabled
        ?>
        <div id="directorist" class="directorist atbd_wrapper">
                <?php
                $listing_info = get_post_meta($post->ID, '_faqs', true);
                $faqs = !empty($listing_info)?$listing_info:array();
                self::$instance->load_template('add-faq', array('listing_faq' => $faqs));
                ?>
            </div>

        <?php
    }

    public function atbdp_faqs_ajax_handler(){
        $id = (!empty($_POST['id'])) ? absint($_POST['id']) : 0;
        self::$instance->load_template('ajax/faqs-ajax', array( 'id' => $id, ));
        die();
    }

    public function load_needed_scripts($screen){
        wp_enqueue_script('listing_faqs', plugin_dir_url(__FILE__) . '/assets/js/main.js', array('jquery'), true);
        if (is_rtl()){
            wp_enqueue_style('faqs_main_style_rtl', plugin_dir_url(__FILE__) . '/assets/css/main-rtl.css');
        }else{
            wp_enqueue_style('faqs_main_style', plugin_dir_url(__FILE__) . '/assets/css/main.css');
        }
        $faqs_ans_box = get_directorist_option('faqs_ans_box', 'normal');
        $l10n = array(
          'ans_field' => $faqs_ans_box
        );
        wp_localize_script('listing_faqs', 'listing_faqs', $l10n);
    }
    public function load_needed_scripts_admin($screen)
    {
        $post_type = get_post_type(get_the_ID());
        if (('post-new.php' == $screen) || ('post.php' == $screen) || ($post_type == 'at_biz_dir')) {

            wp_enqueue_script('listing_faqs_admin', plugin_dir_url(__FILE__) . '/assets/js/admin-main.js', array('jquery'), true);
            wp_enqueue_style('faqs_main_style', plugin_dir_url(__FILE__) . '/assets/css/main.css');

        }
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
            'name' => 'enable_faqs',
            'label' => __('Enable FAQs', 'direo-extension'),
            'description' => __('Allow users add FAQs for a listing.', 'direo-extension'),
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

        register_widget('FAQs_Widget');
    }


    /**
     * It adds the business hour input fields to the add listing page
     * @param string $page_type the type of of business directory page we are hooking into
     * @param array $listing_info All the information about the current listing.
     */
    public function add_new_faq($type, $listing_info, $listing_id)
    {
        if (!get_directorist_option('enable_faqs',1)) return; // vail if the business hour is not enabled
        $plan_faqs = true;
        if (is_fee_manager_active()){
            $plan_faqs = is_plan_allowed_listing_faqs(get_post_meta($listing_id, '_fm_plans', true));
        }
        if ($plan_faqs){
        ?>
<div class="atbd_content_module atbd_contact_information">
    <div class="atbd_content_module__tittle_area">
        <div class="atbd_area_title">
            <h4><?php _e('Listings FAQs', 'direo-extension'); ?></h4>
        </div>

    </div>

    <div class="atbdb_content_module_contents">

        <?php
        $faqs = !empty($listing_info['faqs'])?$listing_info['faqs']:array();
        self::$instance->load_template('add-faq', array('listing_faq' => $faqs));
        ?>

    </div>
</div><!-- end .atbd_general_information_module -->
<?php
    }}

    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     */
    public function load_template($name, $args = array())
    {
        global $post;
        include(FAQS_TEMPLATES_DIR . $name . '.php');
    }

    /**
     * It register the text domain to the WordPress
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('direo-extension', false, FAQS_LANG_DIR);
    }

    /**
     * It Includes and requires necessary files.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function includes()
    {
        require_once FAQS_INC_DIR . 'helper-functions.php';
        require_once FAQS_DIR . 'widgets/class-widget.php';
    }


    /**
     * Setup plugin constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function setup_constants()
    {
        require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
    }
}


/**
 * The main function for that returns Listings_fAQs
 *
 * The main function responsible for returning the one true Listings_fAQs
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|Listings_fAQs The one true Listings_fAQs Instance.
 */
function Listings_fAQs()
{
    return Listings_fAQs::instance();
}

// Instantiate Directorist Stripe gateway only if our directorist plugin is active
if (in_array('directorist/directorist-base.php', (array)get_option('active_plugins'))) {
    Listings_fAQs(); // get the plugin running
}