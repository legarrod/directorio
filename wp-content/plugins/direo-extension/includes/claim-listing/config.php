<?php
// Plugin version.
if ( ! defined( 'DCL_VERSION' ) ) {define( 'DCL_VERSION', '1.0.1' );}
// Plugin Folder Path.
if ( ! defined( 'DCL_DIR' ) ) { define( 'DCL_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DCL_URL' ) ) { define( 'DCL_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DCL_FILE' ) ) { define( 'DCL_FILE', __FILE__ ); }
if ( ! defined( 'DCL_BASE' ) ) { define( 'DCL_BASE', plugin_basename( __FILE__ ) ); }

// Plugin Includes Path
if ( !defined('DCL_INC_DIR') ) { define('DCL_INC_DIR', DCL_DIR.'inc/'); }
// Plugin Assets Path
if ( !defined('DCL_ASSETS') ) { define('DCL_ASSETS', DCL_URL.'assets/'); }
// Plugin Template Path
if ( !defined('DCL_TEMPLATES_DIR') ) { define('DCL_TEMPLATES_DIR', DCL_DIR.'templates/'); }
// Plugin Language File Path
if ( !defined('DCL_LANG_DIR') ) { define('DCL_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Name
if ( !defined('DCL_NAME') ) { define('DCL_NAME', 'Directorist - Pricing Plans'); }
// Plugin Post Type
if ( !defined('ATBDP_POST_TYPE') ) { define('ATBDP_POST_TYPE', 'at_biz_dir'); }
if ( !defined('ATBDP_ORDER_POST_TYPE') ) { define('ATBDP_ORDER_POST_TYPE', 'atbdp_orders'); }
if ( !defined('ATBDP_PRICING_PLANS_POST_TYPE') ) { define('ATBDP_PRICING_PLANS_POST_TYPE', 'ATBDP_Pricing_Plans'); }
// Plugin Alert Message
if ( !defined('DCL_ALERT_MSG') ) { define('DCL_ALERT_MSG', __('You do not have the right to access this file directly', 'direo-extension')); }
