<?php
// Plugin version.
if ( ! defined( 'dPlugins_VERSION' ) ) {define( 'dPlugins_VERSION', '1.0.0' );}
// Plugin Folder Path.
if ( ! defined( 'dPlugins_DIR' ) ) { define( 'dPlugins_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'dPlugins_URL' ) ) { define( 'dPlugins_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'dPlugins_FILE' ) ) { define( 'dPlugins_FILE', __FILE__ ); }
if ( ! defined( 'dPlugins_BASE' ) ) { define( 'dPlugins_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Text domain File.
// Plugin Includes Path
if ( !defined('dPlugins_INC_DIR') ) { define('dPlugins_INC_DIR', dPlugins_DIR.'includes/'); }

// Plugin Language File Path
if ( !defined('dPlugins_LANG_DIR') ) { define('dPlugins_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Name
if ( !defined('dPlugins_NAME') ) { define('dPlugins_NAME', 'Direo - Plugins'); }
