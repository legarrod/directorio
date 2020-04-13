<?php
// Plugin version.
if ( ! defined( 'DGR_VERSION' ) ) {define( 'DGR_VERSION', '1.2.0' );}
// Plugin Folder Path.
if ( ! defined( 'DGR_DIR' ) ) { define( 'DGR_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DGR_URL' ) ) { define( 'DGR_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DGR_FILE' ) ) { define( 'DGR_FILE', __FILE__ ); }
if ( ! defined( 'DGR_BASE' ) ) { define( 'DGR_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Includes Path
if ( !defined('DGR_INC_DIR') ) { define('DGR_INC_DIR', DGR_DIR.'inc/'); }
// Plugin Assets Path
if ( !defined('DGR_ASSETS') ) { define('DGR_ASSETS', DGR_URL.'assets/'); }
// Plugin Template Path
if ( !defined('DGR_TEMPLATES_DIR') ) { define('DGR_TEMPLATES_DIR', DGR_DIR.'templates/'); }
// Plugin Language File Path
if ( !defined('DGR_LANG_DIR') ) { define('DGR_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Name
if ( !defined('DGR_NAME') ) { define('DGR_NAME', 'Directorist - Google reCAPTCHA'); }

// Plugin Alert Message
if ( !defined('DGR_ALERT_MSG') ) { define('DGR_ALERT_MSG', __('You do not have the right to access this file directly', 'direo-extension')); }

// plugin author url
if (!defined('ATBDP_AUTHOR_URL')) {
    define('ATBDP_AUTHOR_URL', 'https://directorist.com');
}
// post id from download post type (edd)
if (!defined('ATBDP_RECAPTCHA_POST_ID')) {
    define('ATBDP_RECAPTCHA_POST_ID', 13768 );
}

