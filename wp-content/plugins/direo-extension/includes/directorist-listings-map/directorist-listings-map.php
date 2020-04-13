<?php

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

final class BD_Map_View
{
    /** Singleton *************************************************************/

    /**
     * @var BD_Map_View The one true BD_Map_View
     * @since 1.0
     */
    private static $instance;
    /**
     * BDMV_Settings Object.
     *
     * @var object|BDMV_Settings
     * @since 1.0
     */
    public $BDMV_Settings;
    /**
     * BDMV_Ajax Object.
     *
     * @var object|BDMV_Ajax
     * @since 1.0
     */
    public $BDMV_Ajax;
    /**
     * BDMV_Hooks Object.
     *
     * @var object|BDMV_Hooks
     * @since 1.0
     */
    public $BDMV_Hooks;
    /**
     * Main BD_Map_View Instance.
     *
     * Insures that only one instance of BD_Map_View exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0
     * @static
     * @static_var array $instance
     * @uses BD_Map_View::setup_constants() Setup the constants needed.
     * @uses BD_Map_View::includes() Include the required files.
     * @uses BD_Map_View::load_textdomain() load the language files.
     * @see  BD_Map_View()
     * @return object|BD_Map_View The one true BD_Map_View
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof BD_Map_View)) {
            self::$instance = new BD_Map_View;
            self::$instance->setup_constants();
            add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts'));
            add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts_for_admin'));
            self::$instance->includes();
            self::$instance->BDMV_Settings  = new BDMV_Settings;
            self::$instance->BDMV_Ajax      = new BDMV_Ajax;
            self::$instance->BDMV_Hooks     = new BDMV_Hooks;

        }
        return self::$instance;
    }

    public function load_needed_scripts()
    {
        wp_enqueue_script('bdm-main-js', plugin_dir_url(__FILE__) . '/public/assets/js/main.js');
        wp_enqueue_script('bdm-view-js', plugin_dir_url(__FILE__) . '/public/assets/js/view-as.js');
        wp_register_script('bdm-current-js', plugin_dir_url(__FILE__) . '/public/assets/js/current-location.js');
        if(is_rtl()) {
            wp_enqueue_style('bdm-main-css-rtl', plugin_dir_url(__FILE__) . '/public/assets/css/style-rtl.css');
        } else {
            wp_enqueue_style('bdm-main-css', plugin_dir_url(__FILE__) . '/public/assets/css/style.css');
        }
        wp_localize_script('bdm-main-js','bdrr_submit',array(
            'ajaxnonce'         => wp_create_nonce( 'bdas_ajax_nonce' ),
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
        ));
        wp_localize_script('bdm-view-js','bdrr_submit',array(
            'ajaxnonce'         => wp_create_nonce( 'bdas_ajax_nonce' ),
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
        ));
    }

    public function load_needed_scripts_for_admin() {
        wp_enqueue_script('bdm-admin-js', plugin_dir_url(__FILE__) . '/admin/assets/js/main.js');
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
     * It Includes and requires necessary files.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function includes()
    {
        require_once BDM_DIR . 'inc/settings.php';
        require_once BDM_DIR . 'inc/ajax.php';
        require_once BDM_DIR . 'inc/hooks.php';
        require_once BDM_DIR . 'inc/helper.php';
    }
    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     */
    public function load_template($name, $args = array())
    {
        global $post;
        include(BDM_TEMPLATES_DIR . $name . '.php');
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
        // Plugin Folder Path.
        if ( ! defined( 'BDM_DIR' ) ) { define( 'BDM_DIR', plugin_dir_path( __FILE__ ) ); }
        // Plugin Folder URL.
        if ( ! defined( 'BDM_URL' ) ) { define( 'BDM_URL', plugin_dir_url( __FILE__ ) ); }
        // Plugin Root File.
        if ( ! defined( 'BDM_FILE' ) ) { define( 'BDM_FILE', __FILE__ ); }
        if ( ! defined( 'BDM_BASE' ) ) { define( 'BDM_BASE', plugin_basename( __FILE__ ) ); }
        // Plugin Text domain File.
        if ( ! defined( 'BDM_TEXTDOMAIN' ) ) { define( 'BDM_TEXTDOMAIN', 'direo-extension' ); }
        // Plugin Language File Path
        if ( !defined('BDM_LANG_DIR') ) { define('BDM_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
        // Plugin Template Path
        if ( !defined('BDM_TEMPLATES_DIR') ) { define('BDM_TEMPLATES_DIR', BDM_DIR.'templates/'); }
    }
}
/**
 * The main function for that returns BD_Map_View
 *
 * The main function responsible for returning the one true BD_Map_View
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|BD_Map_View The one true BD_Map_View Instance.
 */
function BD_Map_View()
{
    return BD_Map_View::instance();
}

if(in_array( 'directorist/directorist-base.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    BD_Map_View(); // get the plugin running
}
