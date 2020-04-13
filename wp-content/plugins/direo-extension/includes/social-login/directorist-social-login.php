<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

final class Directorist_Social_Login
{
    /** Singleton *************************************************************/

    /**
     * @var Directorist_Social_Login The one true Directorist_Social_Login
     * @since 1.0
     */
    private static $instance;

    private function __construct()
    {
        /*making it private prevents constructing the object*/

    }
    /**
     * Main Directorist_Social_Login Instance.
     *
     * Insures that only one instance of Directorist_Social_Login exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0
     * @static
     * @static_var array $instance
     * @uses Directorist_Social_Login::setup_constants() Setup the constants needed.
     * @uses Directorist_Social_Login::includes() Include the required files.
     * @uses Directorist_Social_Login::load_textdomain() load the language files.
     * @see  Directorist_Social_Login()
     * @return object|Directorist_Social_Login The one true Directorist_Social_Login
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Directorist_Social_Login)) {
            self::$instance = new Directorist_Social_Login;
            self::$instance->setup_constants();

           // add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
            add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts_public'));
            add_action('wp_footer', array(self::$instance, 'load_cnd_for_media'));
            add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_to_ext_general_fields'));
            add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'add_settings_for_social_submenue'));
            add_action('atbdp_before_login_form_end', array(self::$instance, 'atbdp_social_login_html'));

            add_action( 'wp_ajax_atbdp_social_login', array(self::$instance, 'social_login') );
            add_action( 'wp_ajax_nopriv_atbdp_social_login', array(self::$instance, 'social_login') );

            self::$instance->includes();
        }

        return self::$instance;
    }

    public function social_login() {
        $redirect_url = ATBDP_Permalink::get_dashboard_page_link();
        $data = [
            'status' => false, 
            'redirect_url' => $redirect_url
        ];

        $validation = $this->form_validation( $_POST );
        if ( !$validation ) {
            $data['redirect_url'] = '#';
            echo wp_json_encode( $data );
            wp_die();
        }

        if (
            !isset( $_POST['id'] ) || 
            !isset( $_POST['email'] ) || 
            !isset( $_POST['full_name'] ) || 
            !isset( $_POST['last_name'] )
        ) {
            echo wp_json_encode( $data );
            wp_die();
        }

        $user_meta = array(
            'id' => $_POST['id'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
        );

        // Check if user exist by social id
        $user = get_users(array(
            'meta_key' => '_atbdp_social_id', 
            'meta_value' => $user_meta['id']
        ));

        if ( $user ) {
            wp_clear_auth_cookie();
            wp_set_current_user( $user[0]->ID );
            wp_set_auth_cookie( $user[0]->ID, true );

            $data['status'] = true;
            $data['message'] = 'Login is successful';
            echo wp_json_encode( $data );
            wp_die();
        }

        // Check if user exist by email
        if ( email_exists( $user_meta['email'] ) ) {
            $response = $this->login_user( $user_meta );
            $data['status'] = $response;
            $data['message'] = ($response) ? 'Login is successful' : 'Login Failed';

            echo wp_json_encode( $data );
            wp_die();
        }

        // If user doesn't exist register
        $response = $this->register_user( $user_meta );
        $data['status'] = $response;
        $data['message'] = ($response) ? 'Registation successful' : 'Registration Failed';

        echo wp_json_encode( $data );
        wp_die();
    }

    // form_validation
    private function form_validation( $post )
    {
        $status = true;

        // Check if all fields exist
        $form_fields = ['id', 'email', 'full_name', 'first_name', 'last_name'];
        foreach ($form_fields as $field) {
            if ( !isset( $post[$field] ) ) {
                $status = false;
                break;
            }
        }

        return $status;
    }


    // register_user
    public function register_user( $user )
    {
        $status = false;
        $username = $this->genarate_username($user['full_name']);
        if ( !$username ) { return false; }

        $password = wp_generate_password( 6, false );

        $user_data = [
            'user_login' => $username,
            'user_pass' => $password,
            'user_email' => $user['email'],
            'display_name' => $user['full_name'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
        ];
        $user_id = wp_insert_user( $user_data );

        update_user_meta($user_id, '_atbdp_generated_password', $password); //update the password
        update_user_meta($user_id, '_atbdp_social_id', $user['id']); //update social_id

        wp_new_user_notification($user_id, null, 'admin'); // send activation to the admin
        ATBDP()->email->custom_wp_new_user_notification_email($user_id);

        if ( $user && !is_wp_error( $user_id ) ) {
            $status = true;
            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);
        }

        return $status;
    }

    // login_user
    public function login_user( $user )
    {
        $status = false;
        $user = get_user_by('email', $user['email'] );

        if ( $user && !is_wp_error( $user ) ) {
            $status = true;
            wp_clear_auth_cookie();
            wp_set_current_user( $user->ID );
            wp_set_auth_cookie( $user->ID, true );
        }

        return $status;
    }

    // genarate_username
    public function genarate_username( $full_name )
    {
        $username = null;
        $match_not_found = true;
        while ( $match_not_found ) {
            $new_username = $this->get_random_string($full_name);

            if ( !username_exists($new_username) ) {
                $username = $new_username;
                $match_not_found = false;
                return $username;
            }
        }
        return $username;
    }

    public function get_random_string($text) {
        $lower_str = strtolower($text);
        $string = preg_replace('/\s/', '_', $lower_str);

        $rand_num = mt_rand(10000, 90000);
        $rand_string = $string .'_'. $rand_num;
        return $rand_string;
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

    public function atbdp_social_login_html(){
        $enable_social_login = get_directorist_option('enable_social_login',1);
        if (!$enable_social_login) return;
    ?>
        <button type="button" disabled class="btn fb-login az-fb-login-btn">
            <span class="azbdp-fb-loading"><span class="fa fa-spin fa-spinner"></span></span>
            <span class="fa fa-facebook-f"></span> <?php _e('Facebook', 'direo-extension')?>
        </button>

        <button type="button" disabled class="btn fb-google az-gg-login-btn">
            <span class="azbdp-gg-loading"><span class="fa fa-spin fa-spinner"></span></span>
            <span class="fa fa-google"></span> <?php _e('Google', 'direo-extension')?>
        </button>
    <?php
    }

    public function load_cnd_for_media() {
      echo '<script defer src="https://connect.facebook.net/en_US/sdk.js"></script>';
      echo '<script defer src="https://apis.google.com/js/platform.js?onload=initGAPI"></script>';
    }

    public function load_needed_scripts_public($screen) {
        wp_enqueue_script('atbdp_social_login', DEB_PUBLIC_ASSETS . '/js/social-login.js', null, array('jquery'), true);

        $fb_app_id = class_exists('Directorist_Base')?get_directorist_option('atbdp_fb_app_id'):'';
        $google_api = class_exists('Directorist_Base')?get_directorist_option('google_api'):'';
        $social_login_debug = class_exists('Directorist_Base')?get_directorist_option('atbdp_social_login_debug'):'';
        $data = array(
          'ajax_url' => admin_url( 'admin-ajax.php' ),
          'fb_app_id' => $fb_app_id,
          'google_api' => $google_api,
          'social_login_debug' => $social_login_debug,
          'error_msg' => __('Sorry, something went wrong', 'direo-extension'),
          'success_msg' => __('Login successful, redirecting...', 'direo-extension'),
          'wait_msg' => __('Please wait...', 'direo-extension'),
        );
        wp_localize_script( 'atbdp_social_login', 'atbdp_social_login_obj', $data);
        if (is_rtl()){
            wp_enqueue_style('atbdp_social_login_main_rtl', DEB_PUBLIC_ASSETS . '/css/main-rtl.css');

        }else{
            wp_enqueue_style('atbdp_social_login_main', DEB_PUBLIC_ASSETS . '/css/main.css');
        }
    }

    /**
     * It displays settings for the
     * @param $settings_submenus array The array of the settings menu of Directorist
     * @return array
     */
    public function add_settings_for_social_submenue($settings_submenus)
    {
        /*lets add a submenu of our extension*/
        $settings_submenus[] = array(
            'title' => __('Social Login', 'direo-extension'),
            'name' => 'social_login_submenu',
            'icon' => 'font-awesome:fa-sign-in',
            'controls' => array(
                'general_section' => array(
                    'type' => 'section',
                    'title' => __('Social Login API Keys', 'direo-extension'),
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'atbdp_fb_app_id',
                            'label' => __('Facebook App ID', 'direo-extension'),
                            'description' => sprintf(__( 'To allow your visitors to log in with their Facebook account, first you must create a Facebook App. You can find your Facebook App ID and detailed information %s. or you can search in google', 'direo-extension' ), '<a href="https://developers.facebook.com/apps/" target="_blank"> <strong style="color: red;">here</strong> </a>')
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'google_api',
                            'label' => __('Google Clint ID', 'direo-extension'),
                            'description' => sprintf(__( 'To allow your visitors to log in with their Google account, first you must create a Google App. You can find your Google Clint ID and detailed information %s. or you can search in google', 'direo-extension' ), '<a href="https://developers.google.com/identity/sign-in/web/sign-in/" target="_blank"> <strong style="color: red;">here</strong> </a>')
                        ),
                    ),// ends fields array
                ), // ends general section array
                'settings_section' => array(
                    'type' => 'section',
                    'title' => __('Social Login Settings', 'direo-extension'),
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'atbdp_social_login_debug',
                            'label' => __('Debug', 'direo-extension'),
                            'description' => sprintf(__('Enable debuging mode'))
                        ),
                    ),// ends fields array
                ), // ends settings section array
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
            'name' => 'enable_social_login',
            'label' => __('Enable Social Login', 'direo-extension'),
            'default' => 1,
        );
        // lets push our settings to the end of the other settings field and return it.
        array_push($fields, $ebh);

        return $fields;

    }

    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     */
    public function load_template($name, $args = array())
    {
        global $post;
        include(DEB_TEMPLATES_DIR . $name . '.php');
    }

    /**
     * It register the text domain to the WordPress
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('direo-extension', false, DEB_LANG_DIR);
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
        require_once DEB_INC_DIR . 'helper-functions.php';
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
 * The main function for that returns Directorist_Social_Login
 *
 * The main function responsible for returning the one true Directorist_Social_Login
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|Directorist_Social_Login The one true Directorist_Social_Login Instance.
 */
function Directorist_Social_Login()
{
    return Directorist_Social_Login::instance();
}
// Instantiate Directorist Stripe gateway only if our directorist plugin is active
if (in_array('directorist/directorist-base.php', (array)get_option('active_plugins'))) {
    Directorist_Social_Login(); // get the plugin running
}