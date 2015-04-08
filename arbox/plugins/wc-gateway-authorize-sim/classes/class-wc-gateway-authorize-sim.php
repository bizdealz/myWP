<?php
/**
* Gateway class
**/
class WC_Gateway_Authorize_SIM extends WC_Payment_Gateway {
	
	/**
	 * Test mode
	 */
	var $testmode;
	
	/**
	 * notify url
	 */
	var $notify_url;
	
	var $line_items = array();
	
	function __construct() { 
		global $woocommerce;
		
		$this->id			= 'authorize_sim';
        $this->has_fields 	= false;
		$this->method_title = __( 'Authorize SIM', WC_Authorize_SIM::TEXT_DOMAIN );
		
		// Load the form fields
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		foreach ( $this->settings as $setting_key => $setting ) {
			$this->$setting_key = $setting;
		}
		
		// Hooks
		add_action('woocommerce_receipt_authorize_sim', array(&$this, 'receipt_page'));
		
		$this->notify_url = home_url('/');;
		
		if($this->enabled == 'yes') {
			add_action( 'init', array(&$this, 'response_handler') );
			
			//load & configure API
			$this->init_api();
		}
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {
			add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
		} else {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			
			add_action( 'woocommerce_api_wc_gateway_authorize_sim', array( $this, 'response_handler' ) );
			$this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Authorize_SIM', $this->notify_url);
		}
		
	}
	
	/**
	 * Add selected card icons to payment method label, defaults to Visa/MC/Amex/Discover
	 *
	 * @since 1.2
	 */
	public function get_icon() {
		global $wc_authorize_sim;

		$icon = '';

		$icon = '<img src="' . esc_url( $wc_authorize_sim->force_ssl( $wc_authorize_sim->plugins_url('assets/images/authorize-net-co.png') ) ) . '" alt="' . esc_attr( $this->title ) . '" />';

		return apply_filters( 'woocommerce_authorize_sim_icon', $icon, $this->id );
	}
	
	/**
     * Initialize Gateway Settings Form Fields
     */
    function init_form_fields() {
    
    	$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'label' => __( 'Enable Authorize.Net SIM Payment Module', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'checkbox', 
				'description' => '', 
				'default' => 'no'
			), 
			'title' => array(
				'title' => __( 'Title' ), 
				'type' => 'text', 
				'description' => __( 'This controls the title which the user sees during checkout.', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => __( 'Authorize.net SIM', WC_Authorize_SIM::TEXT_DOMAIN ),
				'css' => "width: 300px;"
			), 
			'description' => array(
				'title' => __( 'Description', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'textarea', 
				'description' => __( 'This controls the description which the user sees during checkout.', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => 'Pay with your credit card via Authorize.net.'
			),
			'debug' => array(
				'title' => __( 'Debug', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'checkbox', 
				'label' => __( 'Enable logging (<code>woocommerce/logs/authorize_sim.txt</code>)', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => 'no'
			),
			'testmode' => array(
				'title' => __( 'Test mode', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'label' => __( 'Test Mode allows you to submit test transactions to the payment gateway', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'checkbox', 
				'description' => __( 'You may want to set to true if testing against production', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => 'no'
			), 
			'login_id' => array(
				'title' => __( 'API Login ID', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'text', 
				'description' => __( 'This is API Lgoin supplied by Authorize.', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => ''
			), 
			'tran_key' => array(
				'title' => __( 'Transaction Key', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'text', 
				'description' => __( 'This is Transaction Key supplied by Authorize.', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => ''
			),
			'md5_hash' => array(
				'title' => __( 'MD5 Hash', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'text', 
				'description' => __( 'The MD5 hash value to verify transactions', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'default' => ''
			),
			'type' => array(
				'title' => __( 'Sale Method', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'select', 
				'description' => __( 'Select which sale method to use. Authorize Only will authorize the customers card for the purchase amount only.  Authorize &amp; Capture will authorize the customer\'s card and collect funds.', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'options' => array(
					'AUTH_CAPTURE'=>'Authorize &amp; Capture',
					'AUTH_ONLY'=>'Authorize Only'
				),
				'default' => 'AUTH_CAPTURE'
			),
			'tran_mode' => array(
				'title' => __( 'Transaction Mode', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'type' => 'select', 
				'description' => __( 'Transaction mode used for processing orders', WC_Authorize_SIM::TEXT_DOMAIN ), 
				'options' => array('live'=>'Live', 'sandbox'=>'Sandbox'),
				'default' => 'live'
			),
			
		);
    }
    
    /**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 **/
	public function admin_options() {
?>
		<h3><?php _e('Authorize SIM', WC_Authorize_SIM::TEXT_DOMAIN); ?></h3>
    	<p><?php _e('Authorize SIM works by sending the user to Authorize to enter their payment information.', WC_Authorize_SIM::TEXT_DOMAIN); ?></p>
    	<div class="updated"><p>
    		<strong><?php _e('Authorize.Net config:', WC_Authorize_SIM::TEXT_DOMAIN) ?></strong>
    		<?php _e( 'Please login to Authorize and go to Account >> Settings >> Response/Receipt URLs' ); ?>
    		<ol>
	    		<li><?php _e( 'Click "Add URL", and set this value for URL textbox: ') ?><strong><?php echo $this->notify_url ?></strong></li>
	    		<li><?php _e( 'Click "Submit" to complete', WC_Authorize_SIM::TEXT_DOMAIN ) ?></li>
    		</ol>
    	</p></div>
    	<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
		</table><!--/.form-table-->    	
<?php
    }
	
	/**
	 * Init api
	 */
	public function init_api(){
		global $wc_authorize_sim;
		
		if(!defined('AUTHORIZE_NET_SDK')) {
			define('AUTHORIZE_NET_SDK', 1);
			require_once $wc_authorize_sim->plugins_path( 'includes/autoload.php' );
		}
	}
	
	
	/**
     * Payment form on checkout page
     */
	function payment_fields() {
?>
		<?php if ($this->tran_mode=='sandbox') : ?><p><?php _e('TEST MODE/SANDBOX ENABLED', WC_Authorize_SIM::TEXT_DOMAIN); ?></p><?php endif; ?>
		<?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>
<?php

	}
	
 	/**
	 * Get args for passing
	 **/
	function get_params( $order) {
		global $woocommerce, $wc_authorize_sim;
		
		$this->add_log( 'Generating payment form for order #' . $order->id);
		
		$params = array();
		
		$params = array (
			'x_login'			=> $this->login_id,
			'x_show_form'		=> 'PAYMENT_FORM',			
			'x_type' 			=> $this->type,
			'x_test_request' 	=> ($this->testmode == 'yes') ? 'TRUE' : 'FALSE',			
			'x_relay_response'	=> 'TRUE',
            'x_relay_url'     	=> add_query_arg('_hdl_anet_sim', 'relay', $this->notify_url),
            
			//billing
			'x_first_name' 		=> $order->billing_first_name,
			'x_last_name' 		=> $order->billing_last_name,
			'x_address' 		=> $order->billing_address_1,
			'x_city' 			=> $order->billing_city,
			'x_state' 			=> $order->billing_state,
			'x_zip' 			=> $order->billing_postcode,
			'x_country' 		=> $order->billing_country,
			'x_phone' 			=> $order->billing_phone,
			'x_email'			=> $order->billing_email,
			
			//shipping
			'x_ship_to_first_name' 		=> $order->shipping_first_name,
			'x_ship_to_last_name' 		=> $order->shipping_last_name,
			'x_ship_to_address' 		=> $order->shipping_address_1,
			'x_ship_to_city' 			=> $order->shipping_city,
			'x_ship_to_state' 			=> $order->shipping_state,
			'x_ship_to_zip' 			=> $order->shipping_postcode,
			'x_ship_to_country' 		=> $order->shipping_country,
			'x_ship_to_company' 		=> $order->shipping_company,
				
			'x_cust_id' 		=> $order->user_id,
			'x_customer_ip' 	=> $this->get_user_ip(),
			'x_invoice_num' 	=> $order->id,
			'x_fp_sequence'		=> $order->order_key,
			'x_amount' 			=> $order->get_total(),
			
			'x_cancel_url'		=> $order->get_cancel_order_url(),
			'x_cancel_url_text'	=> __( 'Cancel', WC_Authorize_SIM::TEXT_DOMAIN ),
		);
		
		// Address 2
		if( ! empty( $order->billing_address_2 ) ) {
			$params['x_address'] .= ' - ' . $order->billing_address_2;
		}
		if( ! empty( $order->shipping_address_2 ) ) {
			$params['x_ship_to_address'] .= ' - ' . $order->shipping_address_2;
		}
		
		// Add item line
		$this->add_item_fields( $order );
		
		// Tax
		if( $order->get_total_tax() > 0 ) {
			// Id
			$item_id = 'TAX01' ;
			
			// name
			$item_name 	= __( 'Cart Tax', WC_Authorize_SIM::TEXT_DOMAIN );
			
			// description
			$item_desc 	= '';
			
			// Quantity
			$item_qty 	= 1;
			
			// Amount
			$item_amount = $order->get_total_tax();
			
			// Log point
			$this->add_log( $item_id . ", $item_name, $item_desc, $item_qty, $item_amount" );
			
			// Add line item
			$this->add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, 'N');
		}

		// Fees
		if ( sizeof( $order->get_fees() ) > 0 ) {
			$idx = 0;
			foreach ( $order->get_fees() as $item ) {
				$idx ++;
				
				// Id
				$item_id = sprintf( 'FEE%02d', $idx );
			
				// name
				$item_name 	= $this->get_item_name( $item['name'] );
				
				// description
				$item_desc 	= $item_name;
				
				// Quantity
				$item_qty 	= 1;
				
				// Amount
				$item_amount = $item['line_total'];
				
				// Log point
				$this->add_log( $item_id . ", $item_name, $item_desc, $item_qty, $item_amount" );
				
				$this->add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, 'N');
			}
		}

		// Shipping Cost item
		if ( $order->get_total_shipping() > 0 ) {
			// Id
			$item_id = 'SHP01';
				
			// name
			$item_name 	= $this->get_item_name( $order->get_shipping_method() );
			
			// description
			$item_desc 	= sprintf( __( 'Shipping via %s', WC_Authorize_SIM::TEXT_DOMAIN ), $order->get_shipping_method() );
			
			// Quantity
			$item_qty 	= 1;
			
			// Amount
			$item_amount = round( $order->get_total_shipping(), 2 );
			
			// Log point
			$this->add_log( $item_id . ", $item_name, $item_desc, $item_qty, $item_amount" );
			
			$this->add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, 'N');
		}
		
		// Discount
		if ( $order->get_cart_discount() > 0 ) {
			// Id
			$item_id = 'DIS01' ;
			
			// name
			$item_name 	= __( 'Cart Discount', WC_Authorize_SIM::TEXT_DOMAIN );
			
			// description
			$item_desc 	= '';
			
			// Quantity
			$item_qty 	= 1;
			
			// Amount
			$item_amount = round( $order->get_cart_discount(), 2 );
			
			// Log point
			$this->add_log( $item_id . ", $item_name, $item_desc, $item_qty, $item_amount" );
			
			$this->add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, 'N');
		}
		
		return  apply_filters( 'woocommerce_authorize_sim_args', $params, $order->id );
	}
	
	/**
	 * Get standalone line item hidden field
	 */
	function add_item_fields( $order ) {
		
		$line_items = '';
		
		// Cart Contents
		$item_loop = 0;
		if (sizeof($order->get_items())>0) {
			foreach ($order->get_items() as $item) {
				
				if ($item['qty']){
					$item_loop++;
					$product = $order->get_product_from_item($item);
					
					$item_id = '';
					if ($product->get_sku()) { 
						$item_id = $product->get_sku();
					} else {
						$item_id = $product->id;
					}
					
					$item_name 	= $this->get_item_name( $item['name'] );
	
					$item_meta 	= new WC_Order_Item_Meta( $item['item_meta'] );
					$item_desc 	= $item_meta->display( true, true );
					$item_desc 	= $this->get_item_name( $item_desc, 255);
					
					$item_qty 		= $item['qty'];
					$item_amount 	= $order->get_item_total( $item, false );
					$item_tax 		= 'NO';
					
					$this->add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, $item_tax );
				}
			}
		}

		return $line_items;
	}
	
	/**
	 * Add item field
	 */
	public function add_item_field( $item_id, $item_name, $item_desc, $item_qty, $item_amount, $item_tax='NO' ){
		$this->line_items[] = '<input type="hidden" name="x_line_item" value="' . esc_attr("{$item_id}<|>{$item_name}<|>{$item_desc}<|>{$item_qty}<|>{$item_amount}<|>{$item_tax}") . '" />';
	}
	
	/**
	 * Generate the Authorize SIM button link
	 **/
    function generate_form( $order_id ) {
		global $woocommerce, $wc_authorize_sim;
		
		$order = new WC_Order( $order_id );
		
		$this->add_log( 'Generating payment form for order #' . $order->id);
		
		$pay_url = $this->get_gateway_url();
		
		$params = $this->get_params( $order );
		
		$time = time();
        $fp_hash = AuthorizeNetSIM_Form::getFingerprint( $this->login_id, $this->tran_key, $params['x_amount'], $params['x_fp_sequence'], $time );
		
		$params['x_fp_timestamp'] 	= $time;
		$params['x_fp_hash'] 		= $fp_hash;
		
		$this->add_log( "Sending request: " . print_r( $params,true ));
		
		$form = new AuthorizeNetSIM_Form($params);
		
		wc_enqueue_js( 
			'$.blockUI({
				message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to 2checkout to make payment.', WC_Authorize_SIM::TEXT_DOMAIN ) ) . '",
				baseZ: 99999,
				overlayCSS:
				{
					background: "#fff",
					opacity: 0.6
				},
				css: {
					padding:        "20px",
					zindex:         "9999999",
					textAlign:      "center",
					color:          "#555",
					border:         "3px solid #aaa",
					backgroundColor:"#fff",
					cursor:         "wait",
					lineHeight:		"24px",
				}
			});
			jQuery("#authorize_sim_payment_form input[type=submit]").click();' 
		);
?>
<form action="<?php echo $pay_url ?>" method="post" id="authorize_sim_payment_form">
	<?php echo $form->getHiddenFieldString(); ?>
	<?php echo implode( '', $this->line_items ) ?>
	<input type="submit" class="button button-alt" id="submit_authorize_sim_payment_form" value="<?php _e('Pay via Authorize.Net SIM', WC_Authorize_SIM::TEXT_DOMAIN) ?>" /> 
	<a class="button cancel" href="<?php echo $order->get_cancel_order_url() ?>"><?php _e('Cancel order &amp; restore cart', WC_Authorize_SIM::TEXT_DOMAIN) ?></a>
</form>
<?php
	}
	
	/**
     * Process the payment
     */
	function process_payment($order_id) {
		global $woocommerce;
		
		$order = new WC_Order( $order_id );
		
		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_checkout_payment_url( $order )
		);
	}

	/**
	 * receipt_page
	 **/
	function receipt_page( $order ) {
		echo '<p>'.__('Thank you for your order, please wait to pay with Authorize.Net.', WC_Authorize_SIM::TEXT_DOMAIN).'</p>';
		$this->generate_form( $order );
	}
	
	/**
     * Validate the payment form
     */
	function validate_fields() {
		return true;
	}

	/**
	 * Check response data
	 */
	public function response_handler() {
		global $woocommerce, $wc_authorize_sim;
		
		if (isset($_GET['_hdl_anet_sim'])) {
			$hdl= $_GET['_hdl_anet_sim']; // handle value
			
			if( 'relay' == $hdl ) {
				@ob_clean();
				
				$msg = false;
				
				$location = $wc_authorize_sim->get_page_url( 'cart' );
				
				$this->add_log( 'Relay response:' . print_r($_POST,true));
				
				$response = new AuthorizeNetSIM( $this->login_id, $this->md5_hash );
				
				if ( $response->isAuthorizeNet() ) {
			        if ($response->approved) {
			        	// Get order ID
			        	$order_id = isset( $response->invoice_number ) ? $response->invoice_number : '';
						
						if(!empty($order_id)) {
							
							$order = new WC_Order( $order_id );
													
							$order->add_order_note( __('Authorize.Net SIM Commerce payment completed', WC_Authorize_SIM::TEXT_DOMAIN) . ' (Transaction ID: ' . $response->transaction_id . ')' );
							
							$this->add_log( 'Authorize.Net SIM Commerce payment completed (Transaction ID: ' . $response->transaction_id . ')');
							
							$order->payment_complete();
							$woocommerce->cart->empty_cart();
							
							// Review order
							$location = $this->get_checkout_order_received_url( $order );
						} else {
							
							$this->add_log( 'Error: the order id is empty' );
							$msg = __( 'Error: the order id is empty', WC_Authorize_SIM::TEXT_DOMAIN );
						}
					} else {
						
						$this->add_log( sprintf( 'Response error %s: %s', $response->response_reason_code, $response->response_reason_text));
						$msg = sprintf( __( 'Response error %s: %s', WC_Authorize_SIM::TEXT_DOMAIN ), $response->response_reason_code, $response->response_reason_text);
					}
				} else {
					
					$this->add_log( 'MD5 Hash failed. Check to make sure your MD5 Setting matches the one in admin option' );
					$msg = __( 'MD5 Hash failed. Check to make sure your MD5 Setting matches the one in admin option', WC_Authorize_SIM::TEXT_DOMAIN );
				}
				
				if( $msg ) {
					$location = add_query_arg( 'wc-api', 'WC_Gateway_Authorize_SIM', $location );
					$location = add_query_arg( '_hdl_anet_sim', 'error', $location );
					
					// redirect with message
					$location = add_query_arg( 'reason_text', urlencode( $msg ), $location );
				}
				
				echo AuthorizeNetDPM::getRelayResponseSnippet($location); // Must be client redirect
				exit(1);
				
			} elseif( 'error' == $hdl ) {
				// Redirect againt with message
				$message = isset( $_REQUEST['reason_text'] ) ? urldecode( $_REQUEST['reason_text'] ) : '';
				$this->add_message( $message );
				
				wp_redirect( $wc_authorize_sim->get_page_url( 'cart' ) );
				exit(1);
			}
		}
	}

	/**
	 * Adds debug messages to the page as a WC message/error, and / or to the WC Error log
	 *
	 * @since 2.1
	 * @param array $errors error messages to add
	 */
	public function add_log( $errors ) {
		global $wc_authorize_sim;

		if ( $this->debug != 'yes' ) return; 
		
		// do nothing when debug mode is off
		if ( empty( $errors ) )
			return;

		$message = implode( ', ', ( is_array( $errors ) ) ? $errors : array( $errors ) );

		// add debug message to checkout page
		$wc_authorize_sim->log( $message );			
	}
	
	/**
	 * Show messages
	 */
	public function add_message( $message ) {
		global $wc_authorize_sim;
		
		// do nothing when debug mode is off
		if ( empty( $message ) )
			return;

		$message = implode( ', ', ( is_array( $message ) ) ? $message : array( $message ) );

		// add debug message to checkout page
		$wc_authorize_sim->add_message( $message );			
	}
	
	/**
	 * URL gateway
	 * 
	 */
	function get_gateway_url(){
		return ( $this->tran_mode == 'sandbox' ) ? AuthorizeNetDPM::SANDBOX_URL : AuthorizeNetDPM::LIVE_URL;
	}
	
	/**
	 * Get checkout payment url
	 */
	protected function get_checkout_payment_url( $order ){
		global $wc_authorize_sim;
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			return add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, $wc_authorize_sim->get_page_url('pay')));
		}
		
		return $order->get_checkout_payment_url( true );
	}
	
	/**
	 * Thanks page
	 */
	protected function get_checkout_order_received_url( $order ){
		global $wc_authorize_sim;
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			return add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, $wc_authorize_sim->get_page_url('thanks')));
		}
		return $order->get_checkout_order_received_url();
	}
	
	/**
	 * Limit the length of item names
	 * @param  string $item_name
	 * @return string
	 */
	public function get_item_name( $item_name, $max=31 ) {
		if ( strlen( $item_name ) > $max ) {
			$item_name = substr( $item_name, 0, $max-3 ) . '...';
		}
		return html_entity_decode( $item_name, ENT_NOQUOTES, 'UTF-8' );
	}
	
	/**
     * Get user's IP address
     */
	function get_user_ip() {
		if($_SERVER['SERVER_NAME'] == 'localhost') {
			return '127.0.0.1';
		}
		return $_SERVER['REMOTE_ADDR'];
	}
	
} // end WC_Gateway_Authorize_SIM
