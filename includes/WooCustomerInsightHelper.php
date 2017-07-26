<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

class WooCustomerInsightHelper {

	public $wci_cookie_value;
	public function __construct()
	{
		$this->wci_cookie_value = '';
	}	

	//Get session id using session_key	
	public function wci_get_session_id( $wci_session_key )
	{
		global $wpdb;
		$get_session_id = $wpdb->get_results( $wpdb->prepare( "select * from wci_maintain_session where session_key=%s" , $wci_session_key ) );
		if( !empty( $get_session_id )) {
			$session_id = $get_session_id[0]->session_id;
			return $session_id;
		}
	}

	public function ip_details($IPAddress)
	{
		$json       = file_get_contents("http://ipinfo.io/{$IPAddress}");
		$details    = json_decode($json);
		return $details;
	}

	// Set cookie for guest user
	public function set_wci_session_key( $sess_key )
	{
		setcookie( 'wci_customer_cookie_key' , $sess_key , time() + (86400 * 2 ) ); // set cookie for 2 days
	}	
	
	//get guest cookie id
	public function get_wci_session_key( )
	{
		$wci_session_key = $_COOKIE['wci_customer_cookie_key'];
		return $wci_session_key;
	}

	//Generate or get customer id 
	 
        public function wci_generate_customer_id($generate_wci_unique_key = 'true') {
	        if ( $generate_wci_unique_key == 'true' ) {
		        if ( is_user_logged_in() ) {
			        return get_current_user_id();
		        } else {
			        require_once( ABSPATH . 'wp-includes/class-phpass.php' );
			        $hasher = new PasswordHash( 8, false );
			        return md5( $hasher->get_random_bytes( 32 ) );
		        }
	        } else {
		        require_once( ABSPATH . 'wp-includes/class-phpass.php' );
		        $hasher = new PasswordHash( 8, false );
		        return md5( $hasher->get_random_bytes( 32 ) );
	        }
        }

	
	// Changed function from all_info into trackingHistory
	public function trackingHistory() {
		global $wci_global;
		global $countries;
		global $wpdb;
		global $woocommerce;
		global $current_user;

		// Get email when form is submitted - for guest
		if( !empty($_POST))
		{
			$wci_email_set = array();
			foreach($_POST as $post_key => $post_val)
			{
				$email_pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
				if(preg_match( $email_pattern , $post_val , $match ))
				{
					$wci_email_set[] = $post_val;	
				}
			}	
			if(isset($wci_email_set[0])) {
			$wci_guest_email = $wci_email_set[0];}
			else {
			$wci_guest_email = ""; }
			if(!isset($_COOKIE['wci_guest_email']))
			{
				setcookie('wci_guest_email' , $wci_guest_email);	
			}
			
		}

			//Check Guest email present in COOKIE
			if(isset($_COOKIE['wci_guest_email']))
			{
				$wci_guest_email = $_COOKIE['wci_guest_email'];
			}


		if( !isset( $_COOKIE['wci_unique_key'] )) {
			$unique_key = $this->wci_generate_customer_id('false');
			setcookie( 'wci_unique_key', $unique_key, time() + (3600 *48) );
		}
		if( !isset( $_COOKIE['wci_customer_cookie_key'] ))
		{
			$wci_cust_id = $this->wci_generate_customer_id();
			setcookie( 'wci_customer_cookie_key', $wci_cust_id , time() + (3600 * 48 )  );
			$temp_session_key = $wci_cust_id;
		}
		else
		{
			$temp_session_key = $_COOKIE['wci_customer_cookie_key'];
			//For logged in user
			if( is_user_logged_in() )
			{
				$temp_session_key = get_current_user_id();
				setcookie( 'wci_customer_cookie_key', $temp_session_key , time() + (3600 * 48 )  );
			}
			//If user is a guest , but if user key not cleared in logout - reset to guest key
			else if( strlen($temp_session_key) < 30 )
			{
				$wci_cust_id = $this->wci_generate_customer_id();
				setcookie( 'wci_customer_cookie_key', $wci_cust_id , time() + (3600 * 48 )  );
				$temp_session_key = $wci_cust_id;
			}
		}


		//Get User id
		if( is_user_logged_in() )
		{
			$wci_user_id = get_current_user_id();
		}
		else
		{
			$wci_user_id = 'guest';
		}

		if( isset( $temp_session_key ) )
		{
			$wci_session_id = $wpdb->get_var( $wpdb->prepare( "select session_id from wci_maintain_session where session_key=%s" , $temp_session_key ) );
			if( !empty($wci_session_id) )
			{
				$wpdb->update(
					'wci_maintain_session',
					array(
						'session_key'    => $temp_session_key,
						'user_id'	 => $wci_user_id,
						'session_value'  => $this->wci_cookie_value,
						'session_expiry' => time() + 3600 * 48
					),
					array( 'session_id' => $wci_session_id ),
					array(
						'%s',
						'%s',
						'%s',
						'%d'
					),
					array( '%d' )
				);
				
			}

			else
			{
				if( isset($_COOKIE['wci_customer_cookie_key']) )
				{
				$wpdb->insert(
					'wci_maintain_session',
					array(
						'session_key'    => $temp_session_key,
						'user_id' 	 => $wci_user_id,
						'session_value'  => $this->wci_cookie_value,
						'session_expiry' => time() + 3600 * 48
					),
					array(
						'%s',
						'%s',
						'%s',
						'%d'
					)
				);
				
				}
				
			} 
		}
			
		// Get visitor's location information based on the IP address
		$myPublicIP = $_SERVER['REMOTE_ADDR'];
		$details    = $this->ip_details("$myPublicIP");
		$get_country = '';
		$city = isset($details->city) ? $details->city : "" ;
		$country_code = isset($details->country) ? $details->country : "";
		$country_keys = array_keys( $countries );
		if( in_array($country_code,$country_keys ))
		{
			$get_country .= $countries[$country_code];
		}
		if( !empty( $city) && !empty($get_country) ){
			$country_name = $city . "," . $get_country;
		}else{
			$country_name = '--';
		}
		$ajaxURL = admin_url( 'admin-ajax.php' );
		$get_current_url = new CustomerInsight();
		$current_url = $get_current_url->current_url;

		$wp_lead_builder_version_check = get_option( "WP_LEADS_BUILDER_PRO_VERSION" );
		if(!empty($wp_lead_builder_version_check))
		$cart_url = $woocommerce->cart->get_cart_url();

		$scheme = parse_url($cart_url, PHP_URL_SCHEME);
		$rmscheme = str_replace($scheme,'',$cart_url);
		$cart_url = str_replace('://','',$rmscheme);
		require_once('hasPurchased.php');

		$shop_page_url = '';
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		if ( $shop_page_id ) {
			$shop_page_url = get_permalink( $shop_page_id );
		}

		$shop_scheme = parse_url($shop_page_url, PHP_URL_SCHEME);
		$rm_shop_scheme = str_replace($shop_scheme,'',$shop_page_url);
		$shop_page_url = str_replace('://','',$rm_shop_scheme);

		$myaccount_page_url = '';
		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
		if ( $myaccount_page_id ) {
			$myaccount_page_url = get_permalink( $myaccount_page_id );
			$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
		}
		$myacc_scheme = parse_url($myaccount_page_url, PHP_URL_SCHEME);
		$rm_myacc_scheme = str_replace( $myacc_scheme , '' , $myaccount_page_url );
		$myaccount_page_url = str_replace( '://','',$rm_myacc_scheme );

		$checkout_url = $woocommerce->cart->get_checkout_url();
		$checkout_scheme = parse_url($checkout_url, PHP_URL_SCHEME);
		$rm_shop_scheme = str_replace($checkout_scheme,'',$checkout_url);
		$checkout_url = str_replace('://','',$rm_shop_scheme);
		$site_url = site_url();
		$page_scheme = parse_url($site_url,PHP_URL_SCHEME);
		$post_id =  url_to_postid( $page_scheme."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ) ;
		$page_title = get_the_title( $post_id );
		// If we can't get shop id then get it from option table
		if( $post_id == 0 && $current_url == $shop_page_url)
		{
			$post_id = get_option( 'woocommerce_shop_page_id' );
		}
		if( preg_match( "/product/" , $current_url ) )
		{
			$page_title = get_the_title( $post_id );
		}
		if( preg_match( "/cart/" , $current_url ) )
		{
			$page_title = "Cart";
		}
		if( preg_match( "/checkout/" , $current_url ) )
		{
			$page_title = "Checkout";
		}
		if( preg_match( "/my-account/" , $current_url ) )
		{
			$page_title = "My-account";
		}
		if( preg_match( "/shop/" , $current_url ) )
		{
			$page_title = "Shop";
		}
		$post_type = get_post_type( $post_id );
		$product = get_the_title( $post_id );

		if( $post_type == 'product' )
		{
			$prod_page = $current_url ;
		}
		// Get cart items
		$get_cart_items = $woocommerce->cart->get_cart();
		$cart_items = array();
		foreach($get_cart_items as $item => $values) {
			$_product = $values['data']->post;
			$cart_items[]  =  $_product->post_title ;
		}
		$cart_items = json_encode($cart_items);
		$id = $wpdb->get_results( $wpdb->prepare("select ID,post_date from ".$wpdb->posts." where post_status IN (%s,%s)" , 'wc-pending','wc-on-hold' ),ARRAY_A );
		foreach( $id as $key=>$val )
		{
			$prod_id = $val['ID'];
			$post_date = $val['post_date'];
			$curr_date= current_time('Y-m-d H:i:s');
			$diff = strtotime($curr_date) - strtotime($post_date);
			$time_diff = round( $diff /3600 );
			$order = new WC_Order($val['ID']);
			// here the customer data
			$order_email = $order->billing_email;
			$customer = get_userdata($order->customer_user);
			$order_cust_name =  $customer->display_name;
			$check_entry = $wpdb->get_results($wpdb->prepare( "select order_id from wci_abandon_cart where order_id=%d" , $prod_id ), ARRAY_A);
			$check_order_id = $check_entry[0]['order_id'];
			if( empty( $check_order_id ) )

			{
				if( $time_diff >= 1 ){
					$wpdb->insert( 'wci_abandon_cart' , array( 'user_email' => $order_email , 'order_id' => $prod_id , 'date' => $post_date , 'time_difference' => $time_diff ) );
				}
			}
			else
			{
				$wpdb->update( 'wci_abandon_cart' , array( 'time_difference' => $time_diff ) , array( 'order_id' => $prod_id ) );
			}
			$check_pending = $wpdb->get_results("select order_id from wci_abandon_cart",ARRAY_A);
			foreach( $check_pending as $arr=>$ord_id['order_id']  )
			{
				$ordr_id = $ord_id['order_id'];
				foreach( $ordr_id as $idd )
				{
					$post_stat = $wpdb->get_var($wpdb->prepare("select post_status from ".$wpdb->posts." where ID=%d" , $idd));
					if( $post_stat != 'wc-pending' && $post_stat != 'wc-on-hold' )
					{
						$wpdb->query("delete from wci_abandon_cart where order_id='$idd'");
					}
				}
			}

			$items = $wpdb->get_results( $wpdb->prepare("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=%d", "$prod_id"),ARRAY_A );

			foreach( $items as $itm=>$prod ){
				$pp = $prod['order_item_name'];
			}
		}

		$current_user = wp_get_current_user();
		if( is_user_logged_in() ) {
			$user_id = $current_user->ID;
			$user_login = $current_user->user_login;
			$user_email = $current_user->user_email;
			$user_name = $current_user->display_name;
			$is_user = "1";
			//User Session
			$user_session = $wpdb->get_results( $wpdb->prepare( "select * from wci_maintain_session where session_key=%s" , $user_id ) );
			if( !empty( $user_session ))
			{
				$session_id = $user_session[0]->session_id;
				$session_key = $user_session[0]->session_key;
				$session_val = $user_session[0]->session_value;
			}
			
		}
		else {
			$session_key = $temp_session_key;
			$session_val = $this->wci_cookie_value;
			$session_id = $this->wci_get_session_id($session_key);
			$guest_unique_id = substr( $session_key , -4  );
			$user_name = "Guest_".$guest_unique_id;
			//Check for Guest email
			if(!empty($wci_guest_email))
			{
				$user_email = $wci_guest_email;
			}
			else
			{
				$user_email = '';
			}
			$is_user = "0";
			if( !empty( $session_id ) )
			{
				// Use the above session_id
			}
			else
			{
				$session_id = '0';
			}
		}
		$wci_global[$_COOKIE['wci_unique_key']]['req'] = $_SERVER['REQUEST_URI'];
		$wci_global[$_COOKIE['wci_unique_key']]['ip'] = $_SERVER['REMOTE_ADDR'];
		$wci_global[$_COOKIE['wci_unique_key']]['country'] = $country_name;
		$wci_global[$_COOKIE['wci_unique_key']]['ajaxurl'] = $ajaxURL;
		$wci_global[$_COOKIE['wci_unique_key']]['page_url'] = $get_current_url->current_url;
		$wci_global[$_COOKIE['wci_unique_key']]['cart_url'] = $cart_url;
		$wci_global[$_COOKIE['wci_unique_key']]['prod_id'] = $post_id;
		$wci_global[$_COOKIE['wci_unique_key']]['page_title'] = $page_title;
		$wci_global[$_COOKIE['wci_unique_key']]['product'] = $product;
		$wci_global[$_COOKIE['wci_unique_key']]['cart_items'] = $cart_items;
		$wci_global[$_COOKIE['wci_unique_key']]['user_email'] = $user_email;
		$wci_global[$_COOKIE['wci_unique_key']]['user_name'] = $user_name;
		$wci_global[$_COOKIE['wci_unique_key']]['is_user'] = $is_user;
		$wci_global[$_COOKIE['wci_unique_key']]['session_id'] = $session_id;
		$wci_global[$_COOKIE['wci_unique_key']]['session_key'] = $session_key;
		$wci_global[$_COOKIE['wci_unique_key']]['session_val'] = $session_val;

		$js_glob_vars = $wci_global;
		unset( $js_glob_vars[$_COOKIE['wci_unique_key']]['cart_items'] );
		unset( $js_glob_vars[$_COOKIE['wci_unique_key']]['session_val'] );	
	        $prod_page = isset($prod_page) ? $prod_page : "";	
		if( $current_url == $cart_url || $current_url == $shop_page_url || $current_url == $myaccount_page_url || $current_url == $checkout_url || $current_url == $logout_url || $current_url == $prod_page || $post_id != 0 )
		{
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('d3-funnel.js');
	                wp_enqueue_script('d3.min.js');

			wp_enqueue_script('timeSpent',plugins_url('wp-leads-builder-any-crm/assets/js/wci_timespent.js'));
			wp_localize_script( 'timeSpent', 'time_spent_global_vars',
			   array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wci_global_vals' => json_encode($js_glob_vars),
			    	) );
		}
	}

                        
	public function WCI_order_status_completed( $order_id ) {
		$order_status = "Completed";
		global $wpdb;
		$check_order_id = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_id=%d" , $order_id) );		
		if( !empty( $check_order_id ) ) 
		{
			$wpdb->update( 'wci_successful_purchases' , array( 'order_status' => $order_status  ) , array( 'order_id' => $order_id ) );
		}
		$checkorder_in_session = $wpdb->get_results( $wpdb->prepare( "select order_id from wci_user_session where order_id=%d" , $order_id ) );		       if( !empty( $checkorder_in_session ) )
		{
			$wpdb->update( 'wci_user_session' ,array( 'payment_success' => '1' ) , array( 'order_id' => $order_id ) );
		}
	}

	public function WCI_order_status_failed( $order_id ) {
		$order_status = "Failed";
		global $wpdb;
		$check_order_id = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_id=%d", $order_id) );
		if( !empty( $check_order_id ) )
		{
			$wpdb->update( 'wci_successful_purchases' , array( 'order_status' => $order_status ) , array( 'order_id' => $order_id ) );
		}
		$checkorder_in_session = $wpdb->get_results( $wpdb->prepare( "select order_id from wci_user_session where order_id=%d" , $order_id ) );
                if( !empty( $checkorder_in_session ) )
                {
                        $wpdb->update( 'wci_user_session' ,array( 'payment_failure' => '1' ) , array( 'order_id' => $order_id ) );
                }

	}

}
