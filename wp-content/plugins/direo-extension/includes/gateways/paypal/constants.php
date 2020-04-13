<?php
// Plugin version.
if ( ! defined( 'DT_PAYPAL_VERSION' ) ) {define( 'DT_PAYPAL_VERSION', '1.0.0' );}
// Plugin Folder Path.
if ( ! defined( 'DT_PAYPAL_DIR' ) ) { define( 'DT_PAYPAL_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DT_PAYPAL_URL' ) ) { define( 'DT_PAYPAL_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DT_PAYPAL_FILE' ) ) { define( 'DT_PAYPAL_FILE', __FILE__ ); }
if ( ! defined( 'DT_PAYPAL_BASE' ) ) { define( 'DT_PAYPAL_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Library Path
if ( !defined('DT_PAYPAL_LIB_DIR') ) { define('DT_PAYPAL_LIB_DIR', DT_PAYPAL_DIR.'libs/'); }
// Plugin Language File Path
if ( !defined('DT_PAYPAL_LANG_DIR') ) { define('DT_PAYPAL_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }