<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

global $wci_global;
class CustomerInsight
{
	public $current_url = '';

        public function __construct() {
                //$this->wci_includes();
                //$this->wci_plugin_actions();
                $this->current_url = $_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
        }


public  static function wci_tracking_history() {
		$helperObj = new WooCustomerInsightHelper();
		$helperObj->trackingHistory();
	}

	//Event - AddToCart
	public function wci_track_cart_info() {

		global $wci_global;
		global $wpdb;
		global $woocommerce;
		$prodId = intval($_POST['add-to-cart']);
		$ajax_prod_Id = intval($_POST['product_id']);
    		$prodQty = intval($_POST['quantity']);
	
		if( !empty( $prodId ))
		{
			$new_prod_id = $prodId;
		}
		else
		{
			$new_prod_id = $ajax_prod_Id;
		}

		$my_prod_title = get_the_title( $new_prod_id );
		$date = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
	        $date_without_time = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
		$button_name= "AddToCart";
		$cartQty = $woocommerce->cart->get_cart_item_quantities();
		$cartItems = $woocommerce->cart->cart_contents;
		foreach( $wci_global as $global_key => $global_val  )
		{
			$key = $global_val['req'];
			$ip = $global_val['ip'];
			$country = $global_val['country'];
			$page_url = $global_val['page_url'];
			$cart_url= $global_val['cart_url'];
			$prod_title = $global_val['prod_title'];
			$cart_items= $global_val['cart_items'];
			$user_email= $global_val['user_email']; 
			$user_name= $global_val['user_name']; 
			$is_user= $global_val['is_user']; 
			$session_id= $global_val['session_id'];
			$session_key= $global_val['session_key']; 
			$session_val= $global_val['session_val']; 
 		}
		//New code
		$product = $my_prod_title;
		$prod_id = $new_prod_id;
		$count = "1";
		if( empty( $user_email ) )
		{		
			$user_email = "";
		}
		$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key , 'user_id' => $user_name, 'user_email' => $user_email , 'user_ip' => $ip , 'country'=> $country , 'prod_id' => $prod_id , 'product' => $product , 'button_name' => $button_name , 'page_url' => $page_url , 'date' => $date , 'count' => $count , 'date_without_time' => $date_without_time ), '%s' );
                                                                       
	        $wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' => $user_name , 'country' => $country , 'is_cart' => '1' , 'product_key' => $prod_id ,'product_data' => $product , 'session_value' => "{$session_val}", 'date' => $date) , array( '%d','%s','%s','%s','%d','%d','%s','%s','%s' ));

	}

	public static function wci_track_checkout_info($checkout_info) {
			global $wci_global;
	                global $wpdb;
			global $woocommerce;

			$date = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
			$date_without_time = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
                	$button_name= "Checkout";
                	foreach( $wci_global as $global_key => $global_val  )
                	{
				$key = isset($global_val['req']) ? $global_val['req'] : "";
				$ip = isset($global_val['ip']) ? $global_val['ip'] : "";
				$country = isset($global_val['country']) ? $global_val['country'] : "";
				$page_url = isset($global_val['page_url']) ? $global_val['page_url'] : "";
				$cart_url= isset($global_val['cart_url']) ? $global_val['cart_url'] : "";
				$prod_id= isset($global_val['prod_id']) ? $global_val['prod_id'] : "";
				$prod_title = isset($global_val['prod_title']) ? $global_val['prod_title'] : "";
				$cart_items= isset($global_val['cart_items']) ? $global_val['cart_items'] : "";
				$user_email= isset($global_val['user_email']) ? $global_val['user_email'] : "";
				$user_name= isset($global_val['user_name']) ? $global_val['user_name'] : "";
				$is_user= isset($global_val['is_user']) ? $global_val['is_user'] : "";
				$session_id= isset($global_val['session_id']) ? $global_val['session_id'] : "";
				$session_key= isset($global_val['session_key']) ? $global_val['session_key'] : "";
				$session_val= isset($global_val['session_val']) ? $global_val['session_val'] : "";
                	}

		$decode_cart_items = json_decode( $cart_items );
		foreach( $decode_cart_items as $cart_vals )
		{
			$product .= $cart_vals.",";
		}		
		$product = rtrim( $product , "," );

                $count = "1";
                if( empty( $user_email ) )
                {
                        $user_email = "";
                }
			$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key , 'user_id'=> $user_name , 'user_email' => $user_email , 'user_ip' => $ip, 'country' => $country, 'prod_id' => $prod_id, 'product' => $product, 'button_name' => $button_name, 'page_url' => $page_url, 'date' => $date, 'count' => $count, 'date_without_time' => $date_without_time ), '%s' );
			 $wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>$user_name , 'country' => $country , 'product_data' => $product , 'is_checkout' => '1' , 'session_value' => "{$session_val}" , 'date' => $date), array( '%d' , '%s' , '%s' ,'%s', '%s', '%d' , '%s','%s' ) );

	}
		public static function wci_track_payment_info($order_id) {
			
			global $wci_global;
                        global $wpdb;
                        global $woocommerce;
			$date = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
			$date_without_time = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
                        $get_payment_method = sanitize_text_field( $_POST['payment_method'] );
			switch( $get_payment_method )
			{
				case 'cod':
				$button_name = "CashOnDelivery";
				break;

				case 'bacs':
				$button_name = "DirectBankTransfer";
				break;

				case 'paypal':
				$button_name = "ProceedToPaypal";
				break;

				case 'cheque':
				$button_name = "ChequePayment";
				break;

				default :
				$button_name = "Payment";
				break;
			}
			
			foreach( $wci_global as $global_key => $global_val  )
                        {
				$key = $global_val['req'];
				$ip = $global_val['ip'];
				$country = $global_val['country'];
				$page_url = $global_val['page_url'];
				$prod_id= $global_val['prod_id'];
				$prod_title = $global_val['prod_title'];
				$cart_items= $global_val['cart_items'];
				$user_email= $global_val['user_email'];
				$user_name= $global_val['user_name'];
				$is_user= $global_val['is_user'];
				$session_id= $global_val['session_id'];
				$session_key= $global_val['session_key'];
				$session_val= $global_val['session_val'];
                	}
			
			

                $count = "1";
                if( empty( $user_email ) )
                {
                        $user_email = "";
                }

		$decode_cart_items = json_decode( $cart_items );
                foreach( $decode_cart_items as $cart_vals )
                {
                        $product .= $cart_vals.",";
                }
                $product = rtrim( $product , "," );
                	$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key , 'user_id'=> $user_name , 'user_email' => $user_email , 'user_ip' => $ip, 'country' => $country, 'prod_id' => $prod_id, 'product' => $product, 'button_name' => $button_name, 'page_url' => $page_url, 'date' => $date, 'count' => $count, 'date_without_time' => $date_without_time ), '%s' );
			 $wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>$user_name , 'country' => $country , 'product_data' => $product ,'is_payment' => '1' , 'session_value' => "{$session_val}", 'date' => $date) , array( '%d' , '%s' , '%s' ,'%s', '%s', '%d' , '%s','%s' ) );

		//Get and relate guest id with user id when GUEST => USER
			if(isset($_POST['createaccount']) && $_POST['createaccount'] == '1' )	
			{
				$new_wci_guest_email = sanitize_email($_POST['billing_email']);
				$get_wci_guest_user_id = get_user_by('email' , $new_wci_guest_email );
				$wci_guest_converted_userid = $get_wci_guest_user_id->data->ID;
				$wci_display_name = $get_wci_guest_user_id->data->display_name;
				update_option('wci_converted_id_'.$session_key , $wci_guest_converted_userid);

				//Update the tables with user information ( Guest converted to user )
				$wpdb->update('wci_activity' , array('session_key' => $wci_guest_converted_userid , 'user_id' => $wci_display_name , 'user_email' => $new_wci_guest_email , 'is_user' => '1') , array('session_key' => $session_key) , array('%d','%s','%s','%d') , array('%s') );


				$wpdb->update('wci_events' , array('session_key' => $wci_guest_converted_userid , 'user_id' => $wci_display_name , 'user_email' => $new_wci_guest_email) , array('session_key' => $session_key) , array('%d','%s','%s') , array('%s') );

				$wpdb->update('wci_user_session' , array('user_id' => $wci_guest_converted_userid , 'user_name' => $wci_display_name ) , array('user_id' => $session_key) , array('%d','%s') , array('%s'));
			}	

}
		public function wci_track_coupon_info()
		{
			global $wci_global;
	                global $wpdb;
			global $woocommerce;

			$date = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
			$date_without_time = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
                	$button_name= "ApplyCoupon";
                	foreach( $wci_global as $global_key => $global_val  )
                	{
				$key = $global_val['req'];
				$ip = $global_val['ip'];
				$country = $global_val['country'];
				$page_url = $global_val['page_url'];
				$cart_url= $global_val['cart_url'];
				$prod_id= $global_val['prod_id'];
				$prod_title = $global_val['prod_title'];
				$cart_items= $global_val['cart_items'];
				$user_email= $global_val['user_email'];
				$user_name= $global_val['user_name'];
				$is_user= $global_val['is_user'];
				$session_id= $global_val['session_id'];
				$session_key= $global_val['session_key'];
				$session_val= $global_val['session_val'];
                	}

		$decode_cart_items = json_decode( $cart_items );
		foreach( $decode_cart_items as $cart_vals )
		{
$product .= $cart_vals.",";
		}		
		$product = rtrim( $product , "," );

                $count = "1";
                if( empty( $user_email ) )
                {
                        $user_email = "";
                }
			$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key , 'user_id'=> $user_name , 'user_email' => $user_email , 'user_ip' => $ip, 'country' => $country, 'prod_id' => $prod_id, 'product' => $product, 'button_name' => $button_name, 'page_url' => $page_url, 'date' => $date, 'count' => $count, 'date_without_time' => $date_without_time ), '%s' );
			$wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>$user_name , 'country' => $country , 'product_data' => $product , 'session_value' => "{$session_val}" , 'date' => $date), array( '%d' , '%s' , '%s' ,'%s', '%s', '%d' , '%s','%s' ) );

		}

		}
function wci_enqueue() {

    wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/assets/js/my-ajax-script.js', array('jquery') );
    wp_localize_script( 'ajax-script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'wci_enqueue' );

if( !is_admin() )
{
	$active_plugins_list = get_option('active_plugins');
        if( in_array('woocommerce/woocommerce.php' , $active_plugins_list) )
	add_action('init', array('CustomerInsight', 'wci_tracking_history'));
}
add_action( 'woocommerce_add_to_cart', array('CustomerInsight', 'wci_track_cart_info'));
add_action('woocommerce_checkout_order_review', array('CustomerInsight','wci_track_checkout_info' ));
add_action( 'woocommerce_order_status_failed' , array( 'WooCustomerInsightHelper' , 'WCI_order_status_failed'));
add_action( 'woocommerce_order_status_completed' , array( 'WooCustomerInsightHelper' , 'WCI_order_status_completed'));

#add_action('woocommerce_new_order',array('SM_Woo_Customer_Insight','wci_track_payment_info'));
add_action('woocommerce_checkout_order_processed' , array('CustomerInsight','wci_track_payment_info'));
//Hook for Coupon 
add_action('woocommerce_applied_coupon',array('CustomerInsight' , 'wci_track_coupon_info'));
new CustomerInsight();
add_action( 'woocommerce_thankyou' , 'thankyou' );
function thankyou( $order_id ) {
	global $wpdb;
	$current_user = wp_get_current_user();
	if( is_user_logged_in() ) {
		$session_key = $current_user->ID;
	}
	else
	{
		$session_key = $_COOKIE['wci_customer_cookie_key'];
	}
	$user_session = $wpdb->get_results( $wpdb->prepare( "select session_id,user_id from wci_activity where session_key=%s order by id desc" , $session_key ) );
	$session_id = $user_session[0]->session_id;
	$get_id = $wpdb->get_results( "select id from wci_user_session where session_id='$session_id' and is_payment ='1' and order_id=0" );
	$id = isset($get_id[0]->id) ? $get_id[0]->id : "";
	if( !empty( $id ))
	{
		$wpdb->update( 'wci_user_session' , array( 'order_id' => $order_id ) , array( 'session_id' => $session_id , 'order_id' => '0' ));
	}

	$order = new WC_Order( $order_id );
	$total_price = $order->get_total();
	$coupon = $order->get_used_coupons();
	if( !empty($coupon) )
	{
		$coupon_code = $coupon[0];
		$coupon_details = new WC_Coupon( $coupon_code );
		$coupon_amount = $coupon_details->coupon_amount;
		$coupon_discount = $coupon_details->discount_type;
	}
	else
	{
		$coupon_code = "None";
		$coupon_amount = "--";
		$coupon_discount = "--";
	}
	$order_status = $order->post->post_status;
	$items = $order->get_items();
	foreach( $items as $item_vals )
	{
		$guest_prods[] = $item_vals['name'];
	}

	foreach( $guest_prods as $vall )
	{
		$pro_name .= $vall.",";
	}
	$pro_name = rtrim( $pro_name , ",");
	$order_email = $order->billing_email;
	$customer = get_userdata($order->customer_user);
	if( !empty( $customer ) )
	{
		$order_cust_name =  $customer->display_name;
	}
	else
	{
		$order_cust_name = $user_session[0]->user_id;
	}
	$order_date = $order->order_date;
	$wpdb->insert( 'wci_successful_purchases' , array( 'user_name' => $order_cust_name , 'user_email' => $order_email , 'order_id' => $order_id , 'order_status' => $order_status , 'date' => $order_date , 'products' => $pro_name , 'coupon_code' => $coupon_code , 'coupon_amount' => $coupon_amount , 'total_price' => $total_price , 'discount_type' => $coupon_discount ) );
}
