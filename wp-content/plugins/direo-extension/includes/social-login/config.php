<?php
// Plugin version.
if ( ! defined( 'DEB_VERSION' ) ) {define( 'DEB_VERSION', '1.0.0' );}
// Plugin Folder Path.
if ( ! defined( 'DEB_DIR' ) ) { define( 'DEB_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DEB_URL' ) ) { define( 'DEB_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DEB_FILE' ) ) { define( 'DEB_FILE', __FILE__ ); }
if ( ! defined( 'DEB_BASE' ) ) { define( 'DEB_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Includes Path
if ( !defined('DEB_INC_DIR') ) { define('DEB_INC_DIR', DEB_DIR.'inc/'); }
// Plugin Assets Path
if ( !defined('DEB_ASSETS') ) { define('DEB_ASSETS', DEB_URL.'assets/'); }
if ( !defined('DEB_PUBLIC_ASSETS') ) { define('DEB_PUBLIC_ASSETS', DEB_URL.'assets/public'); }
if ( !defined('DEB_ADMIN_ASSETS') ) { define('DEB_ADMIN_ASSETS', DEB_URL.'assets/admin'); }
// Plugin Template Path
if ( !defined('DEB_TEMPLATES_DIR') ) { define('DEB_TEMPLATES_DIR', DEB_DIR.'templates/'); }
// Plugin Language File Path
if ( !defined('DEB_LANG_DIR') ) { define('DEB_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Name
if ( !defined('DEB_NAME') ) { define('DEB_NAME', 'Directorist - Extension Base'); }

// Plugin Alert Message
if ( !defined('DEB_ALERT_MSG') ) { define('DEB_ALERT_MSG', __('You do not have the right to access this file directly', 'direo-extension')); }
