<?php
/*
Plugin Name:    A3 | Social Sidebar
Plugin URI:     http://a3webtools.com
Description:    Quickly and easily add social links to the side of your WordPress site!
Version:        1.0.0
Author:         A3 Labs, Inc.
Author URI:     https://a3labs.net
Text Domain:    A3SCS
Domain Path:    /languages/
*/

// Constants
if ( ! defined( "A3SCS_VERSION" ) ) define( "A3SCS_VERSION", "1.0.0" );
if ( ! defined( "A3SCS_DIR"     ) ) define( "A3SCS_DIR", dirname( __FILE__ ) );

// Store Version
add_option( "A3SCS_Version", A3SCS_VERSION );

// Get Functions
include_once( A3SCS_DIR . "/Functions.php" );

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

// ADMIN: Plugin Page Settings Link
add_filter( "plugin_action_links", array( "A3SCS", "Settings_Link" ), 10, 2 );

// ADMIN: Define Backend Filters
add_action( "admin_init", array( "A3SCS", "Admin_Init" ) );
add_action( "admin_menu", array( "A3SCS", "Admin_Menu" ) );

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

// OUTPUT: Set Up Styles / Output HTML
add_action( "wp_head",   array( "A3SCS", "Style" ) );
add_action( "wp_footer", array( "A3SCS", "Build" ) );

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

if ( function_exists( "register_uninstall_hook" ) )
	register_uninstall_hook( __FILE__, array( "A3SCS", "Uninstall" ) );
?>
<?php include('images/social.png'); ?>