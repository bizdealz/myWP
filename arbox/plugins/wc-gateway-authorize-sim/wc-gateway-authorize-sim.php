<?php
/** 
 * Plugin Name: WC Authorize.Net SIM
 * Plugin URI: http://codecanyon.net/item/authorizenet-sim-payment-gateway-for-woocommerce/3641064
 * Description: Authorize.Net SIM Gateway is a plugin that extends WooCommerce, allowing you to take payments via Authorize.Net.
 * Version: 1.2
 * Author: Buif.Dw <support@browsepress.com>
 * Author URI: http://codecanyon.net/user/browsepress
 * 
 * @package   WC-Gateway-Authorize-SIM
 * @author    BrowsePress <support@browsepress.com>
 * @category  Gateways
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// Check if WooCommerce is active
if ( ! is_woocommerce_active() )
	return;

/**
 * The WC_Authorize_SIM global object
 * @name $wc_authorize_sim
 * @global WC_Authorize_SIM $GLOBALS['wc_authorize_sim']
 */
$GLOBALS['wc_authorize_sim'] = new WC_Authorize_SIM;

class WC_Authorize_SIM {
	
	/** plugin version number */
	const VERSION = '1.2';
	
	/** plugin text domain */
	const TEXT_DOMAIN = 'wc-gateway-authorize-sim';

	/** @var string class to load as gateway, can be base or add-ons class */
	var $gateway_class_name = 'WC_Gateway_Authorize_SIM';

	var $dependencies = array( 'curl', 'json', 'SimpleXML' );
	
	
	/** @var \WC_Logger instance */
	var $logger;
	
	/**
	 * Initializes the plugin
	 *
	 * @since 1.2
	 */
	public function __construct() {
		// include required files
		add_action( 'plugins_loaded', array( $this, 'loaded' ) );

		// load translation
		add_action( 'init', array( $this, 'load_translation' ) );
		
		// load templates, called just before the woocommerce template functions are included
		// add_action( 'init', array( $this, 'include_template_functions' ), 25 );
		
		// admin
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			// dependency check
			add_action( 'admin_notices', array( $this, 'gateway_notices' ) );

			// add a 'Configure' link to the plugin action links
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_plugin_setup_link' ) );
			
			// run every time
			$this->install();
		}
		
	}
	
	/**
	 * Include required files
	 *
	 * @since 1.2
	 */
	public function loaded() {
		// base gateway class
		require( 'classes/class-wc-gateway-authorize-sim.php' );
		
		// add to WC payment methods
		add_filter( 'woocommerce_payment_gateways', array( $this, 'load_gateway' ) );
		
	}
	
	/**
	 * Adds Authorize SIM the list of available payment gateways
	 *
	 * @since 1.2
	 * @param array $gateways
	 * @return array $gateways
	 */
	public function load_gateway( $gateways ) {
		
		$gateways[] = $this->gateway_class_name;
		
		return $gateways;
	}


	/**
	 * Handle localization, WPML compatible
	 *
	 * @since 1.2
	 */
	public function load_translation() {
		//load_textdomain( self::TEXT_DOMAIN, $this->plugins_path( 'languages/'.get_locale().'.mo' ) ); // standalone
		
		// localization in the init action for WPML support
		load_plugin_textdomain( self::TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/** Frontend methods ******************************************************/


	/** Admin methods ******************************************************/

	/**
	 * Checks if required PHP extensions are loaded and SSL is enabled.
	 * Adds an admin notice if either check fails
	 *
	 * @since 1.2
	 */
	public function gateway_notices() {

		$missing_extensions = $this->get_missing_dependencies();

		if ( count( $missing_extensions ) > 0 ) {

			$message = sprintf(
				_n( 'WooCommerce Authorize SIM Gateway requires the %s PHP extension to function.  Contact your host or server administrator to configure and install the missing extension.',
					'WooCommerce Authorize SIM Gateway requires the following PHP extensions to function: %s.  Contact your host or server administrator to configure and install the missing extensions.',
					count( $missing_extensions ), self::TEXT_DOMAIN ),
			  '<strong>' . implode( ', ', $missing_extensions ) . '</strong>'
			);

			echo '<div class="error"><p>' . $message . '</p></div>';
		}

	}

	/**
	 * Gets the string name of any required PHP extensions that are not loaded
	 *
	 * @since 1.2
	 * @return array
	 */
	public function get_missing_dependencies() {

		$missing_extensions = array();
		foreach ( $this->dependencies as $ext ) {
			if ( ! extension_loaded( $ext ) )
				$missing_extensions[] = $ext;
		}

		return $missing_extensions;
	}

	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @since 1.2
	 * @param array $actions associative array of action names to anchor tags
	 * @return array associative array of plugin action links
	 */
	public function add_plugin_setup_link( $actions ) {

		$manage_url = admin_url( 'admin.php' );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {
			$manage_url = add_query_arg( array( 'page' => 'woocommerce_settings', 'tab' => 'payment_gateways', 'subtab' => 'gateway-authorize-sim' ), $manage_url ); // WC 1.6.6
		} else {
			$manage_url = add_query_arg( array( 'page' => 'wc-settings', 'tab' => 'checkout', 'section' => $this->gateway_class_name ), $manage_url ); // WC 2.0+
		}

		// add the link to the front of the actions list
		return ( array_merge( array( 'configure' => sprintf( '<a href="%s">%s</a>', $manage_url, __( 'Configure', self::TEXT_DOMAIN ) ) ), $actions ) );
	}
	
	/** Helper methods ******************************************************/
	
	/**
	 * Get page id of woocommerce
	 * @param string $page the page name
	 * @return int page id
	 */
	public function get_page_id( $page='' ) {
		if( ! empty( $page ) ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
				return woocommerce_get_page_id( $page );
			} else {
				return wc_get_page_id( $page );
			}
		}
	}
	
	/**
	 * Get page link url of woocommerce
	 * @param string $page the page name
	 * @return int page id
	 */
	public function get_page_url( $page='' ) {
		return get_permalink( $this->get_page_id( $page ) );
	}
			
	/**
	 * This will ensure any links output to a page (when viewing via HTTPS) are also served over HTTPS.
	 *
	 * @since 1.2
	 * @return string url
	 */
	public function force_ssl( $url ) {
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			global $woocommerce;
			// For older than version 2.1
			return $woocommerce->force_ssl( $url );
		}
		
		return WC_HTTPS::force_https_url( $url );
	}

	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory
	 *
	 * @since 1.2
	 * @return string plugin path
	 */
	public function plugins_path( $path='' ) {
		// Besure without the first slash
		$path = ltrim($path, '/');
		
		if( ! empty( $this->plugin_path )) {
			return $this->plugin_path . $path;
		}

		$this->plugin_path = plugin_dir_path( __FILE__ );
		
		return $this->plugin_path . $path;
	}

	/**
	 * Gets the plugin url without a trailing slash
	 *
	 * @since 1.2
	 * 
	 * @param $path: Path to the plugin file of which URL you want to retrieve
	 * @return string the plugin url
	 */
	public function plugins_url( $path='' ) {
		// Besure without the first slash
		$path = ltrim($path, '/');
		
		if( ! empty( $this->plugin_url )) {
			return $this->plugin_url . $path;
		}

		$this->plugin_url = plugins_url( '/', __FILE__ );
		
		return $this->plugin_url . $path;
	}
	

	/**
	 * Log errors / messages to WooCommerce error log (/wp-content/woocommerce/logs/)
	 *
	 * @since 1.2
	 * @param string $message
	 */
	public function log( $message ) {
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			global $woocommerce;
	
			if ( ! is_object( $this->logger ) )
				$this->logger = $woocommerce->logger();
		} else {
			if ( ! is_object( $this->logger ) )
				$this->logger = new WC_Logger();
		}
		
		$this->logger->add( 'authorize_sim', $message );
	}
	
	/**
	 * Add message
	 *
	 * @since 1.2
	 * @param string $message
	 */
	public function add_message( $message='', $type='error' ) {
		global $woocommerce;
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			if( 'error' != $type ) {
				$woocommerce->add_message( $message );
			} else {
				$woocommerce->add_error( $message );
			}
		} else {
			wc_add_notice( $message, $type );
		}
	}

	/** Lifecycle methods ******************************************************/


	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 *
	 * @since 1.2
	 */
	private function install() {

		// get current version to check for upgrade
		$installed_version = get_option( 'wc_authorize_sim_version' );

		// upgrade if installed version lower than plugin version
		if ( -1 === version_compare( $installed_version, self::VERSION ) )
			$this->upgrade( $installed_version );
	}


	/**
	 * Perform any version-related changes.
	 *
	 * @since 1.2
	 * @param int $installed_version the currently installed version of the plugin
	 */
	private function upgrade( $installed_version ) {

		// pre-2.0 upgrade
		if ( version_compare( $installed_version, '1.2', '<' ) ) {
			global $wpdb;

			// update from pre-2.0 Authorize SIM version
			if ( $settings = get_option( 'woocommerce_authorize_sim_settings' ) ) {

			}

		}

		// update the installed version option
		update_option( 'wc_authorize_sim_version', self::VERSION );
	}
		
	
}