<?php
/*
	Plugin Name: SYN Media WooCommerce UPS
	Plugin URI: http://www.synmedia.ca/en/plugins/ups-shipping-method-for-woocommerce
	Description: Automatic Shipping Calculation using the UPS Shipping API for WooCommerce
	Version: 2.1.4
	Author: SYN Media Inc.
	Author URI: http://www.synmedia.ca
	Requires at least: 3.8
	Tested up to: 3.8.1
	
	Copyright: © 2012-2013 SYN Media Inc.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

require_once("syn-includes/syn-functions.php");
require_once("syn-shipping/syn-functions.php");

function syn_ups_update_init(){
	$syn_update = new SYN_Auto_Update( get_plugin_data(__FILE__), plugin_basename( __FILE__ ), '4495975', 'FW8LYpxrj8jh0jkgdso1vP4NV' );
}
add_action('admin_init', 'syn_ups_update_init', 11);

define('UPS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ));

function syn_ups_activate(){
	syn_clear_transients( 'sups' );
}
register_activation_hook( __FILE__, 'syn_ups_activate' );

/**
 * Localisation
 */
load_plugin_textdomain( 'syn_ups', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Check if WooCommerce is active
 */
if ( is_woo_enabled() ) {

	$activate_restrictions = get_option( 'urestactivate', false );
	$shipping_debug = get_option( 'shipping_debug', false );

	/**
	 * syn_ups_init function.
	 *
	 * @access public
	 * @return void
	 */
	function syn_ups_init() {
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
			
		$met = new SYN_Shipping_UPS();
		if( $met->debug && $met->is_enabled() ){
			wp_register_style( 'syn-debug', plugins_url( 'assets/css/debug.css', __FILE__ ) );
			wp_enqueue_style( 'syn-debug' );
		}
	}

	add_action( 'woocommerce_shipping_init', 'syn_ups_init' );
	add_action( 'init', 'syn_ups_init', 1 );

	/**
	 * syn_ups_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function syn_ups_add_method( $methods ) {
		$methods[] = 'SYN_Shipping_UPS';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'syn_ups_add_method' );

	/**
	 * Display a notice ...
	 * @return void
	 */
	function syn_ups_notices() {
	
		global $woocommerce;
	
		$missings = array();
	
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		if( !$ups->has_enabled_address() ){
		
			$missings[] = "Origin address";
			
		}
		
		if( empty($ups->access_license_number) ){
		
			$missings[] = "Access number";
			
		}
		
		if( empty($ups->user_id) ){
		
			$missings[] = "User ID";
			
		}
		
		if( empty($ups->password) ){
		
			$missings[] = "Password";
			
		}
		
		if( empty($ups->shipper_number) ){
		
			$missings[] = "Shipper number";
			
		}
		
		if( empty( $missings ) )
			return false;
		
		$url = self_admin_url( 'admin.php?page=' . ( version_compare($woocommerce->version, '2.1.0') >= 0 ? 'wc-settings' : 'woocommerce_settings' ) . '&tab=shipping&section=syn_shipping_ups' );

		$message = sprintf( __( 'UPS error, some fields are missing: %s' , 'syn_ups' ), implode( ", ", $missings ) );

		echo '<div class="error fade"><p><a href="' . $url . '">' . $message . '</a></p></div>' . "\n";
	
	}

	add_action( 'admin_notices', 'syn_ups_notices' );
	
	/**
	 * Show action links on the plugin screen
	 */
	function syn_ups_action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=' . ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ? 'wc-settings' : 'woocommerce_settings' ) . '&tab=shipping&section=syn_shipping_ups' ) . '">' . __( 'Settings', 'syn_ups' ) . '</a>'
		), $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'syn_ups_action_links' );
	
	function syn_ups_woocommerce_product_options_shipping(){
		
		global $post;
		$thepostid = $post->ID;
		
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		echo '</div>';
		echo '<div class="options_group">';

		// UPS method Restrictions
		$ups_method_restriction = get_post_meta( $thepostid, '_ups_method_restriction', true );
		
		if( ! $ups_method_restriction )
			$ups_method_restriction = array();

		?><p class="form-field dimensions_field"><label for="product_ups_method_restriction"><?php _e( 'UPS method restriction', 'syn_ups' ); ?></label> 
		<select name="product_ups_method_restriction[]" id="product_ups_method_restriction" class="short multiselect chosen_select" multiple="multiple">
			<?php if( !empty( $ups->custom_methods ) ){ ?>
			<?php foreach( $ups->custom_methods as $method_key => $service ){ ?>
			<option value="<?php echo( $method_key ); ?>"<?php if( array_search( $method_key, $ups_method_restriction ) !== false ) echo( ' selected="selected"' ); ?>><?php echo esc_attr( $service['name'] ) ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		 <img class="help_tip" data-tip="<?php esc_attr_e( 'Restrict UPS shipping method for this product. Only this method can be used.', 'syn_ups' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" /></p><?php
		
	}
	
	if( $activate_restrictions )
		add_action( 'woocommerce_product_options_shipping', 'syn_ups_woocommerce_product_options_shipping' );
	
	
	function syn_ups_process_product_meta( $post_id ){
		
		$ups_method_restriction = isset( $_POST['product_ups_method_restriction'] ) ? $_POST['product_ups_method_restriction'] : array();
		update_post_meta( $post_id, '_ups_method_restriction', $ups_method_restriction );
		
	}
	
	if( $activate_restrictions ){
		add_action( 'woocommerce_process_product_meta_simple', 'syn_ups_process_product_meta' );
		add_action( 'woocommerce_process_product_meta_variable', 'syn_ups_process_product_meta' );
	}
	
	function syn_ups_woocommerce_product_options_shipping_warehouse(){
		
		global $post;
		$thepostid = $post->ID;
		
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		echo '</div>';
		echo '<div class="options_group">';

		// UPS method Restrictions
		$ups_warehouses = get_post_meta( $thepostid, '_ups_warehouses', true );
		
		if( ! $ups_warehouses )
			$ups_warehouses = array();

		?><p class="form-field dimensions_field"><label for="product_ups_warehouse"><?php _e( 'Warehouse', 'syn_ups' ); ?></label> 
		<select name="product_ups_warehouse[]" id="product_ups_warehouse" class="short multiselect chosen_select" multiple="multiple">
			<?php if( !empty( $ups->addresses ) ){ ?>
			<?php foreach( $ups->addresses as $address_key => $address ){ ?>
			<option value="<?php echo( $address_key ); ?>"<?php if( array_search( $address_key, $ups_warehouses ) !== false ) echo( ' selected="selected"' ); ?>><?php echo esc_attr( $address['title'] ) ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		 <img class="help_tip" data-tip="<?php esc_attr_e( 'Select which warehouse has this product.', 'syn_ups' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" /></p><?php
		
	}
	
	if( $shipping_debug )
		add_action( 'woocommerce_product_options_shipping', 'syn_ups_woocommerce_product_options_shipping_warehouse' );
	
	
	function syn_ups_process_product_meta_warehouse( $post_id ){
		
		/*
$ups_method_restriction = isset( $_POST['product_ups_method_restriction'] ) ? $_POST['product_ups_method_restriction'] : array();
		update_post_meta( $post_id, '_ups_method_restriction', $ups_method_restriction );
*/
		
	}
	
	if( $shipping_debug ){
		add_action( 'woocommerce_process_product_meta_simple', 'syn_ups_process_product_meta_warehouse' );
		add_action( 'woocommerce_process_product_meta_variable', 'syn_ups_process_product_meta_warehouse' );
	}

}

?>