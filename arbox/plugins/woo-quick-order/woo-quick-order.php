<?php
/*
Plugin Name: WooCommerce Onepage Quick Shop
Plugin URI: http://www.solvercircle.com
Description: This is a WooCommerce plugin that list all the products on one page and allow users to quickly order products.
Version: 1.0
Author: SolverCircle
Author URI: http://www.solvercircle.com
*/

define("WQO_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));

include ('includes/wqo-admin.php');
include ('includes/wqo-view.php');
include ('includes/wqo-init.php');

function wqo_init(){
  wp_enqueue_style('wqo-css',WQO_BASE_URL.'/css/wqo.css');
  wp_enqueue_style('colorbox-css',WQO_BASE_URL.'/css/colorbox.css'); 
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('wcp-jscolor', plugins_url( '/js/colorpicker/jscolor.js', __FILE__ ));
  wp_enqueue_script('wqo-tooltip', plugins_url( '/js/wqo_tooltip.js', __FILE__ ));    
  wp_enqueue_script('jquery.colorbox', plugins_url( '/js/jquery.colorbox.js', __FILE__ ));

  
  
}

add_action('init','wqo_init');
register_activation_hook( __FILE__, 'wqo_install');
register_deactivation_hook( __FILE__, 'wqo_uninstall');
?>