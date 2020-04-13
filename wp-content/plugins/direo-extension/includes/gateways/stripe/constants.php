<?php
// Plugin version.
if ( ! defined( 'DT_STRIPE_VERSION' ) ) {define( 'DT_STRIPE_VERSION', '1.1.4' );}
// API version.
if ( ! defined( 'DT_STRIPE_API_VERSION' ) ) {define( 'DT_STRIPE_API_VERSION', '2019-08-14' );}
// Plugin Folder Path.
if ( ! defined( 'DT_STRIPE_DIR' ) ) { define( 'DT_STRIPE_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'DT_STRIPE_URL' ) ) { define( 'DT_STRIPE_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'DT_STRIPE_FILE' ) ) { define( 'DT_STRIPE_FILE', __FILE__ ); }
if ( ! defined( 'DT_STRIPE_BASE' ) ) { define( 'DT_STRIPE_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Library Path
if ( !defined('DT_STRIPE_LIB_DIR') ) { define('DT_STRIPE_LIB_DIR', DT_STRIPE_DIR.'stripe-php-sdk/'); }
// Plugin Language File Path
if ( !defined('DT_STRIPE_LANG_DIR') ) { define('DT_STRIPE_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }