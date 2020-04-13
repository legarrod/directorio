<?php
/**
 * Plugin Name: Direo Extension
 * Plugin URI: https://aazztech.com/product
 * Description: This only work with Direo theme.
 * Version: 1.6.0
 * Author: AazzTech
 * Author URI: https://aazztech.com
 * Text Domain: direo-extension
 * Domain Path: /languages
 */


defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Main Direo_Plugins Class.
 *
 * @since 1.0
 */
final class Direo_Plugins
{
    /** Singleton *************************************************************/

    /**
     * @var Direo_Plugins The one true Direo_Plugins
     * @since 1.0
     */
    private static $instance;


    /**
     * dPlugins_Enqueuer Object.
     *
     * @var object|dPlugins_Enqueuer
     * @since 1.0
     */
    public $enquirer;


    /**
     * Main Direo_Plugins Instance.
     *
     * Insures that only one instance of Direo_Plugins exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @return object|Direo_Plugins The one true Direo_Plugins
     * @uses Direo_Plugins::setup_constants() Setup the constants needed.
     * @uses Direo_Plugins::includes() Include the required files.
     * @uses Direo_Plugins::load_textdomain() load the language files.
     * @see  dPlugins()
     * @since 1.0
     * @static
     * @static_var array $instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Direo_Plugins)) {
            self::$instance = new Direo_Plugins;
            self::$instance->setup_constants();
            add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
            add_action('admin_enqueue_scripts', array(self::$instance, 'admin_enqueue_scripts'));
            self::$instance->includes();
            add_filter('atbdp_monetization_settings_controls', array(self::$instance, 'monitization_control'));
            add_filter('atbdp_monetization_settings_fields', array(self::$instance, 'monetization_by'));

            // new settings
            require_once dPlugins_INC_DIR . '/business-hours/bd-business-hour.php';
            require_once dPlugins_INC_DIR . '/claim-listing/directorist-claim-listing.php';
            require_once dPlugins_INC_DIR . '/faqs/directorist-faqs.php';
            require_once dPlugins_INC_DIR . '/gateways/paypal/directorist-paypal.php';
            require_once dPlugins_INC_DIR . '/gateways/stripe/directorist-stripe.php';
            require_once dPlugins_INC_DIR . '/plans/directorist-pricing-plans.php';
            require_once dPlugins_INC_DIR . '/woo-plans/directorist-woocommerce-pricing-plans.php';
            require_once dPlugins_INC_DIR . '/social-login/directorist-social-login.php';
            require_once dPlugins_INC_DIR . '/directorist-listings-map/directorist-listings-map.php';
            require_once dPlugins_INC_DIR . '/directorist-google-recaptcha/directorist-google-recaptcha.php';
        }

        return self::$instance;
    }

    public function admin_enqueue_scripts()
    {
        wp_register_style('direo-extension-css', dPlugins_URL . 'assets/css/main.css', false, dPlugins_VERSION);
        wp_register_script('direo-extension-js', dPlugins_URL . 'assets/js/main.js', array('jquery'), dPlugins_VERSION, true);

        wp_enqueue_style('direo-extension-css');
        wp_enqueue_script('direo-extension-js');
    }

    public function monetization_by($settings)
    {
        $new = array(
            'type' => 'select',
            'name' => 'direo_monetize_by',
            'label' => __('Monetize Using', 'direo-extension'),
            'items' => apply_filters('direo_monetize_by', array(
                array(
                    'value' => 'pricing_plan',
                    'label' => __('Pricing Plans', 'direo-extension'),
                ),
                array(
                    'value' => 'woo_pricing_plan',
                    'label' => __('WooCommerce Pricing Plans', 'direo-extension'),
                ),
                array(
                    'value' => 'none',
                    'label' => __('None', 'direo-extension'),
                ),
            )),

            'default' => array(
                'value' => 'pricing_plan',
            ),
        );
        array_push($settings, $new);
        return $settings;
    }


    public function monitization_control($settings)
    {
        unset($settings['featured_listing_section']);
        unset($settings['monetize_by_subscription']);
        return $settings;
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
        // test
        require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
    }

    /**
     * Include required files.
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function includes()
    {

    }


    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @return void
     * @since 1.0
     * @access public
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
     * @access public
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }


    public function load_textdomain()
    {
        load_plugin_textdomain('direo-extension', false, dPlugins_LANG_DIR);
    }

    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     * @param bool $return_path Whether to return the path instead of including it
     * @return string|void
     * @todo; Improve this method in future so that it lets user/developers to change/override any templates this plugin uses
     */
    public function load_template($name, $args = array(), $return_path = false)
    {
        global $post;
        $path = dPlugins_TEMPLATES_DIR . $name . '.php';
        if ($return_path) return $path;
        include($path);
    }


} // ends Direo_Plugins


/**
 * The main function for that returns Direo_Plugins
 *
 * The main function responsible for returning the one true Direo_Plugins
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $dPlugins = dPlugins(); ?>
 *
 * @return object|Direo_Plugins The one true Direo_Plugins Instance.
 * @since 1.0
 */
function dPlugins()
{
    return Direo_Plugins::instance();
}

// Get dPlugins Running.
dPlugins();