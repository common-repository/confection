<?php
/*
Plugin Name: Confection
Plugin URI: https://confection.io
Description: Confection is a privacy-first data management solution. Use this plugin to connect your Confection account to any WP site. Need an account? Set one up @ confection.io/register (It's free.)
Author: Confection, Inc.
Author URI: https://confection.io
Text Domain: confection
Version: 0.4.7
License: GPLv3 or later
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CONFECTION_URL', plugin_dir_url( __FILE__ ) );
define( 'CONFECTION_DIR', plugin_dir_path( __FILE__ ) );
define( 'CONFECTION_VERSION', '0.23.06.20' );

include_once('inc/shortcodes.php');

include_once('inc/options.php');

include_once('inc/ajax.php');

if (get_option('confection_enable_community', 0) == '1') include_once('inc/community.php');

if (get_option('confection_enable_woocommerce', 0) == '1') include_once('inc/woocommerce.php');


//Load Text Domain
add_action('plugins_loaded', function(){
    load_plugin_textdomain( 'confection', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    if( is_admin() )
        new ConfectionWPOptions();
});



register_activation_hook( __FILE__ , function(){
    if (! wp_next_scheduled ( 'confection_name_cron' ) )
        wp_schedule_event( time(), 'twicedaily', 'confection_name_cron' );

    $options = new ConfectionWPOptions();
    $options->update_bridge_file();
});



register_deactivation_hook( __FILE__, function(){
    wp_clear_scheduled_hook('confection_name_cron');
    delete_option('confection_version');
}); 

add_action( 'init', function() {

    if (get_option('confection_version') != CONFECTION_VERSION) {
        $options = new ConfectionWPOptions();
        $options->update_bridge_file();
        update_option( 'confection_version', CONFECTION_VERSION, true );
    }

}, 10, 2 );

    
   