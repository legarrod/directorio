<?php
// Plugin version.
if ( ! defined( 'DWPP_VERSION' ) ) {define( 'DWPP_VERSION', '1.0.3' );}
// Plugin Folder Path.
if ( ! defined( 'DWPP_DIR' ) ) { define( 'DWPP_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DWPP_URL' ) ) { define( 'DWPP_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DWPP_FILE' ) ) { define( 'DWPP_FILE', __FILE__ ); }
if ( ! defined( 'DWPP_BASE' ) ) { define( 'DWPP_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Includes Path
if ( !defined('DWPP_INC_DIR') ) { define('DWPP_INC_DIR', DWPP_DIR.'inc/'); }
// Plugin Assets Path
if ( !defined('DWPP_ASSETS') ) { define('DWPP_ASSETS', DWPP_URL.'assets/'); }
// Plugin Template Path
if ( !defined('DWPP_TEMPLATES_DIR') ) { define('DWPP_TEMPLATES_DIR', DWPP_DIR.'templates/'); }
// Plugin Language File Path
if ( !defined('DWPP_LANG_DIR') ) { define('DWPP_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Post Type
if ( !defined('ATBDP_POST_TYPE') ) { define('ATBDP_POST_TYPE', 'at_biz_dir'); }
if ( !defined('ATBDP_ORDER_POST_TYPE') ) { define('ATBDP_ORDER_POST_TYPE', 'atbdp_orders'); }
if ( !defined('DWPP_Pricing_Plans_POST_TYPE') ) { define('DWPP_Pricing_Plans_POST_TYPE', 'DWPP_Pricing_Plans'); }
