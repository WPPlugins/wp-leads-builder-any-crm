<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

class Chart_Data {

	public function wci_opportunity_filter_oneday(){
		global $wpdb;
		$oppor_range = sanitize_text_field( $_REQUEST['abandon_range']);
		
		$today = date('Y-m-d');
	
		$failed_payments = $wpdb->get_results( "select * from wci_successful_purchases where date like '$today%' and order_status='Failed'" ,ARRAY_A );
	if(empty( $failed_payments )  )
	{
        	$wci_failed = '';
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";

}
else
{
        	$wci_failed  = '';
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                  ";
               
                $i = 0;
                foreach( $failed_payments as  $failed_payments_users )
                {
			$i++;
                        if( $i % 2 == 0 )
                        {
                                $wci_failed .= "<tr class='rowone'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";
                        }
                        else
                        {
                                $wci_failed .= "<tr class='rowtwo'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";

                        }
                }
        
                $wci_failed .= "</table>";
}

//SUCCESS PAYMENTS
	
$success_payments = $wpdb->get_results( "select * from wci_successful_purchases where date like '$today%' and order_status='Completed'",ARRAY_A );

if(empty( $success_payments )  )
{
       		$success_payment = '';
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";
         
}
else
{
       		$success_payment = '';
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                ";
	
                $i = 0;
                foreach( $success_payments as  $success_payments_users )
                {
                $i++;
                        if( $i % 2 == 0 )
                        {
                                $success_payment .= "<tr class='rowone'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                        else
                        {
                                $success_payment .= "<tr class='rowtwo'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                }
       
                $success_payment .= "</table>";
               
}


//END SUCCESS PAYMENT

//ABUNDANT
$get_session_ids = array();
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where date like '$today%' and is_cart='1'" , ARRAY_A);
$check_abandon_empty_flag = 0;
if(empty( $get_session_ids )  )
{
             $wci_abundant_cart = '';
             $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                
                <tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>
                </table>";
               
}
else
{
		$wci_abundant_cart = '';
                $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                ";

                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Checkout_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        $Payment_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_payment=%d" , $sess_val['session_id'] , '1' ));
                        
if( empty( $Payment_users ))
                        {
                                if( !empty( $Checkout_users ))
                                {
                                        $i = 0;
                                        foreach( $Checkout_users as $Checkout_key => $Checkout_val )
                                        {
                                        $i++;
                                        if( $i %  2 == 0 )
                                        {
                                                $wci_abundant_cart .= "<tr class='rowone'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                                $wci_abundant_cart .= "</tr>";


 }
                                          else
                                        {
                                                $wci_abundant_cart .= "<tr class='rowtwo'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                               
$wci_abundant_cart .= "</tr>";

                                        }
                                        }
				$check_abandon_empty_flag = 1;
                                }
				                        }
                }
      	      if( $check_abandon_empty_flag == 0 ){
		  $wci_abundant_cart .= "<tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>";
	      }  
              $wci_abundant_cart .=  "</table>";
                
}

//END ABUNDANT

//ADD to cart
 
$wci_addtocart = '';
$check_cart_empty_flag = 0;
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where date like '$today%' and is_cart='1'" , ARRAY_A);
if(empty( $get_session_ids )  )
{
               $wci_addtocart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>
                </table>";
               
}
else
{
        
                
               $wci_addtocart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>

                </tr>
                ";
//New code
                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Cart_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_cart=%d" , $sess_val['session_id'] , '1') );
                        $Checkout_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        if( empty( $Checkout_users ))
                        {
                                if( !empty( $Cart_users ))
                                {
                                $i = 0;
                                        foreach( $Cart_users as $Cart_key => $Cart_val )
                                        {
                                                $i++;
                                                if( $i % 2 == 0 )
                                                {
                                                        $wci_addtocart .= "<tr class='rowone'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                }
                                                else
                                                {
                                                        $wci_addtocart .= "<tr class='rowtwo'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                }

                                        }
				$check_cart_empty_flag = 1;
                                }
				                        }
                }
	       if( $check_cart_empty_flag == 0 ){
		    $wci_addtocart .= "<tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>";
	       }
               $wci_addtocart .= "</table>";
}


//END Addtocart
	$oppor_arr['failed_payment'] = $wci_failed;
	$oppor_arr['success_payment'] = $success_payment;	
	$oppor_arr['abundant_cart'] = $wci_abundant_cart;
	$oppor_arr['addtocart'] = $wci_addtocart;
	$wci_opportunity_array = json_encode( $oppor_arr );
	print_r( $wci_opportunity_array );die;
		
	}

	public function wci_opportunity_filter_oneweek(){
		global $wpdb;
		$oppor_range = sanitize_text_field($_REQUEST['abandon_range']);
		$from_date =  date('Y-m-d', strtotime('-7 days'));
                $to_date =  date('Y-m-d' , strtotime('-1 day'));
	
		$failed_payments = $wpdb->get_results( "select * from wci_successful_purchases where date between '$from_date' and '$to_date' and order_status='Failed'" ,ARRAY_A );
	if(empty( $failed_payments )  )
	{
        	$wci_failed = '';
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";

                
}
else
{
        	$wci_failed  = '';
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                ";
               
                $i = 0;
                foreach( $failed_payments as  $failed_payments_users )
                {
			$i++;
                        if( $i % 2 == 0 )
                        {
                                $wci_failed .= "<tr class='rowone'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";
                        }
                        else
                        {
                                $wci_failed .= "<tr class='rowtwo'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";

                        }
                }
        
                $wci_failed .= "</table>";
}

//SUCCESS PAYMENTS
	
$success_payments = $wpdb->get_results( "select * from wci_successful_purchases where date between '$from_date' and '$to_date' and order_status='Completed'",ARRAY_A );

$success_payment = '';
if(empty( $success_payments )  )
{
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";
         
}
else
{
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                ";
	
                $i = 0;
                foreach( $success_payments as  $success_payments_users )
                {
                $i++;
                        if( $i % 2 == 0 )
                        {
                                $success_payment .= "<tr class='rowone'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                        else
                        {
                                $success_payment .= "<tr class='rowtwo'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                }
       
                $success_payment .= "</table>";
               
}

//END SUCCESS PAYMENT

//ABUNDANT
$check_abandon_empty_flag = 0;
$get_session_ids = array();
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where date between '$from_date' and '$to_date' and is_cart='1'" , ARRAY_A);

$wci_abundant_cart = '';
if(empty( $get_session_ids )  )
{
             $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                
                <tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>
                </table>";
               
}
else
{
                $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                ";
                

                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Checkout_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        $Payment_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_payment=%d" , $sess_val['session_id'] , '1' ));
                       

 
if( empty( $Payment_users ))
                        {
                                if( !empty( $Checkout_users ))
                                {
                                        $i = 0;
                                        foreach( $Checkout_users as $Checkout_key => $Checkout_val )
                                        {
                                        $i++;
                                        if( $i %  2 == 0 )
                                        {
                                                $wci_abundant_cart .= "<tr class='rowone'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                                $wci_abundant_cart .= "</tr>";
					}
                                        else
                                        {
                                                $wci_abundant_cart .= "<tr class='rowtwo'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                                $wci_abundant_cart .= "</tr>";

                                        }
                                        }
				$check_abandon_empty_flag = 1;
                                }
				                        }
                }
      	      if( $check_abandon_empty_flag == 0 ) {
		  $wci_abundant_cart .= "<tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>";
	      }
              $wci_abundant_cart .=  "</table>";
                
}

//END ABUNDANT

//ADD to cart 
$check_cart_empty_flag = 0;
$wci_addtocart = '';
$get_session_ids=array();
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where date between'$from_date' and '$to_date' and is_cart='1'" , ARRAY_A);

if(empty( $get_session_ids )  )
{
               $wci_addtocart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
            
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>
                </table>";
               
}
else
{
                $wci_addtocart .= "
                <table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>

                </tr>
                ";
               

//New code
                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Cart_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_cart=%d" , $sess_val['session_id'] , '1') );
                        $Checkout_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        if( empty( $Checkout_users ))
                        {
                                if( !empty( $Cart_users ))
                                {
                                $i = 0;
                                        foreach( $Cart_users as $Cart_key => $Cart_val )
                                        {
                                                $i++;
                                                if( $i % 2 == 0 )
                                                {
                                                        $wci_addtocart .= "<tr class='rowone'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                }
                                                else
                                                {
                                                        $wci_addtocart .= "<tr class='rowtwo'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                

}
                                        }
				$check_cart_empty_flag = 1;
                                }
				                        }
                }
	       if( $check_cart_empty_flag == 0 ){
		   $wci_addtocart .= "<tr class='rowtwo'><td></td><td style='text-align:center;'>--No Records Found--</td><td></td><td></td></tr>";
	       }
               $wci_addtocart .= "</table>";
}


//END Addtocart
	$oppor_arr['failed_payment'] = $wci_failed;
	$oppor_arr['success_payment'] = $success_payment;	
	$oppor_arr['abundant_cart'] = $wci_abundant_cart;
        $oppor_arr['addtocart'] = $wci_addtocart;
        $wci_opportunity_array = json_encode( $oppor_arr );
        print_r( $wci_opportunity_array );die;
	
	}

	public function wci_opportunity_filter_onemonth(){
		global $wpdb;
		$oppor_range = sanitize_text_field($_REQUEST['abandon_range']);
		$from_date =  date('Y-m-d', strtotime('today - 30 days'));
                $to_date =  date('Y-m-d', strtotime('-1 day'));
		$failed_payments = $wpdb->get_results( "select * from wci_successful_purchases where date between '$from_date' and '$to_date' and order_status='Failed'" ,ARRAY_A );
	
        $wci_failed = '';
	if(empty( $failed_payments )  )
	{
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";

                
}
else
{
                $wci_failed .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
            
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                ";
               
                $i = 0;
                foreach( $failed_payments as  $failed_payments_users )
                {
			$i++;
                        if( $i % 2 == 0 )
                        {
                                $wci_failed .= "<tr class='rowone'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";
                        }
                        else
                        {
                                $wci_failed .= "<tr class='rowtwo'>";
                                $wci_failed .= "<td>". $failed_payments_users['user_name'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['user_email'] ."</td>";
                                $wci_failed .= "<td style='padding-left:40px;'>". $failed_payments_users['order_id'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['products'] ."</td>";
                                $wci_failed .= "<td>". $failed_payments_users['date'] ."</td>";
                                $wci_failed .= "</tr>";

                        }
                }
        
                $wci_failed .= "</table>";
}

//SUCCESS PAYMENTS
	
$success_payments = $wpdb->get_results( "select * from wci_successful_purchases where date between '$from_date' and '$to_date' and order_status='Completed'",ARRAY_A );

$success_payment = '';
if(empty( $success_payments )  )
{
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name</th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
                </table>";
         
}
else
{
                $success_payment .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3'> Customer Name </th>
                <th class='col-md-2'> Email</th>
                <th class='col-md-2'> Order Id</th>
                <th class='col-md-3'> Products</th>
                <th class='col-md-2'> Date</th>
                </tr>
                ";
	
                $i = 0;
                foreach( $success_payments as  $success_payments_users )
                {
                $i++;
                        if( $i % 2 == 0 )
                        {
                                $success_payment .= "<tr class='rowone'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                        else
                        {
                                $success_payment .= "<tr class='rowtwo'>";
                                $success_payment .= "<td>". $success_payments_users['user_name'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['user_email'] ."</td>";
                                $success_payment .= "<td style='padding-left:40px;'>". $success_payments_users['order_id'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['products'] ."</td>";
                                $success_payment .= "<td>". $success_payments_users['date'] ."</td>";
                                $success_payment .= "</tr>";
                        }
                }
       
                $success_payment .= "</table>";
               
}


//END SUCCESS PAYMENT

//ABUNDANT
$get_session_ids = array();
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where date between '$from_date' and '$to_date' and is_cart='1'" , ARRAY_A);
$check_abandon_empty_flag = 0;
$wci_abundant_cart = '';
if(empty( $get_session_ids )  )
{
             $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                
                 <tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>

                </table>";
               
}
else
{
                $wci_abundant_cart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country</th>
                <th class='col-md-3;'> Product</th>
                <th class='col-md-3;'> Date</th>
                </tr>
                ";
                

                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Checkout_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        $Payment_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_payment=%d" , $sess_val['session_id'] , '1' ));
                        if( empty( $Payment_users ))
                        {
                                if( !empty( $Checkout_users ))
                                {
                                        $i = 0;
                                        foreach( $Checkout_users as $Checkout_key => $Checkout_val )
                                        {
                                        $i++;
                                        if( $i %  2 == 0 )
                                        {
                                                $wci_abundant_cart .= "<tr class='rowone'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                                $wci_abundant_cart .= "</tr>";
                                        }
                                        else
                                        {
                                                $wci_abundant_cart .= "<tr class='rowtwo'>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->user_name ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->country ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->product_data ."</td>";
                                                $wci_abundant_cart .= "<td class='col-md-3'>". $Checkout_val->date ."</td>";
                                                $wci_abundant_cart .= "</tr>";

                                        
}
                                        }
				$check_abandon_empty_flag = 1;
                                }
				                        }
                }
      	      if( $check_abandon_empty_flag == 0 ){
		  $wci_abundant_cart .= "<tr class='rowtwo'><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>";
	      }
              $wci_abundant_cart .=  "</table>";
                
}

//END ABUNDANT

//ADD to cart 
$check_cart_empty_flag = 0;
$wci_addtocart = '';
if(empty( $get_session_ids )  )
{
               $wci_addtocart .= "<table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone' style='width:100%;'>
                <th class='col-md-3;'> Customer Name</th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>
                </tr>
                
                <tr class='rowtwo'><td></td><td>--No Records Found--</td><td></td><td></td></tr>

                </table>";
               
}
else
{
                $wci_addtocart .= "
                <table class='table woocustomer-table' style='width:99%;border:1px solid #E5E5E5;'>
                
                <tr class='thone'>
                <th class='col-md-3;'> Customer Name </th>
                <th class='col-md-3;'> Country </th>
                <th class='col-md-3;'> Product </th>
                <th class='col-md-3;'> Date </th>

                </tr>
                ";
               

//New code
                foreach( $get_session_ids as $sess_key => $sess_val)
                {
		$Cart_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_cart=%d" , $sess_val['session_id'] , '1') );
                        $Checkout_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
                        if( empty( $Checkout_users ))
                        {
                                if( !empty( $Cart_users ))
                                {
                                $i = 0;
                                        foreach( $Cart_users as $Cart_key => $Cart_val )
                                        {
                                                $i++;
                                                if( $i % 2 == 0 )
                                                {
                                                        $wci_addtocart .= "<tr class='rowone'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                }
                                                else
                                                {
                                                        $wci_addtocart .= "<tr class='rowtwo'>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->user_name ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->country ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->product_data ."</td>";
                                                        $wci_addtocart .= "<td class='col-md-3'>". $Cart_val->date ."</td>";
                                                        $wci_addtocart .= "</tr>";
                                                }
                                        }
				$check_cart_empty_flag = 1;
                                }
				                        }
                }
	       if( $check_cart_empty_flag == 0 ){
		   $wci_addtocart .= "<tr class='rowtwo'><td></td><td>--No Records Found--</td><td></td><td></td></tr>";
	       }
               $wci_addtocart .= "</table>";
}


//END Addtocart
	$oppor_arr['failed_payment'] = $wci_failed;
	$oppor_arr['success_payment'] = $success_payment;	
	$oppor_arr['abundant_cart'] = $wci_abundant_cart;
	$oppor_arr['addtocart'] = $wci_addtocart;
	$wci_opportunity_array = json_encode( $oppor_arr );
	print_r( $wci_opportunity_array );die;
		
	}



	public function wci_abandon_filter()
	{
		global $wpdb;
		$abandon_range = sanitize_text_field( $_REQUEST['abandon_range']);
		$abandon_array =  array();
	$abandon_array = $wpdb->get_results("select * from wci_abandon_cart where time_difference <= $abandon_range order by order_id DESC limit 15");
	if(!empty($abandon_array)){
	//set scroll for contents
	$html = '';
	$html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
	$html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
	$i = 0;

	foreach($abandon_array as $abandon_key){
        $i++;
        $user_email = $abandon_key->user_email;
        $order_id = $abandon_key->order_id;
        $order_date = $abandon_key->date;
        if( $i %2 == 0 )
        {
                $html .= '<tr class="rowone">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
        else
        {
                $html .= '<tr class="rowtwo">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
}
$html .= "</table>";
}
else
{
	$html = '';
        $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
        $html .= '<tr class="rowtwo">';
        $html .= '<td></td>';
        $html .= '<td> -- No Records Found --</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= "</table>";
}
	$return_range = json_encode( $html );
	print_r( $return_range );die;	
	
}

	public function wci_abandon_filter_oneday()
	{
	global $wpdb;
                $abandon_range = sanitize_text_field($_REQUEST['abandon_range']);
                $abandon_array =  array();
		$today = date('Y-m-d');
		$abandon_array = $wpdb->get_results("select * from wci_abandon_cart where date like '$today%' order by order_id DESC limit 10");

        if(!empty($abandon_array)){
        //set scroll for contents
        $html = '';
        $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
        $i = 0;

        foreach($abandon_array as $abandon_key){
        $i++;
        $user_email = $abandon_key->user_email;
        $order_id = $abandon_key->order_id;
        $order_date = $abandon_key->date;
        if( $i %2 == 0 )
        {
                $html .= '<tr class="rowone">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
        else
        {
                $html .= '<tr class="rowtwo">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
}
$html .= "</table>";
        }
else
{
$html = '';
        $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
	$html .= '<tr class="rowtwo">';
        $html .= '<td></td>';
        $html .= '<td> -- No Records Found --</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
	$html .= "</table>";
}

        $return_range = json_encode( $html );
        print_r( $return_range );die;
	}
	
	public function wci_abandon_filter_oneweek()
	{
		global $wpdb;
                $abandon_range = sanitize_text_field($_REQUEST['abandon_range']);
                $abandon_array =  array();
		$from_date =  date('Y-m-d', strtotime('-7 days'));
                $to_date =  date('Y-m-d' , strtotime('-1 day'));

        $abandon_array = $wpdb->get_results("select * from wci_abandon_cart where date between '$from_date' and '$to_date' order by order_id DESC limit 10");
        if(!empty($abandon_array)){
        //set scroll for contents
        $html = '';
        $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
        $i = 0;

        foreach($abandon_array as $abandon_key){
        $i++;
        $user_email = $abandon_key->user_email;
        $order_id = $abandon_key->order_id;
        $order_date = $abandon_key->date;
        if( $i %2 == 0 )
        {
                $html .= '<tr class="rowone">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
        else
        {
                $html .= '<tr class="rowtwo">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
}
$html .= "</table>";
}
else
        {
		$html = '';
        	$html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        	$html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
                $html .= '<tr class="rowtwo">';
                $html .= '<td></td>';
                $html .= '<td>-- No Records Found --</td>';
                $html .= '<td></td>';
                $html .= '</tr>';
		$html .= '</table>';
        }

        $return_range = json_encode( $html );
        print_r( $return_range );die;
       
	}

	public function wci_abandon_filter_onemonth()
	{
		global $wpdb;
                $abandon_range = sanitize_text_field($_REQUEST['abandon_range']);
                $abandon_array =  array();
		$from_date =  date('Y-m-d', strtotime('today - 30 days'));
                $to_date =  date('Y-m-d', strtotime('-1 day'));

        $abandon_array = $wpdb->get_results("select * from wci_abandon_cart where date between '$from_date' and '$to_date' order by order_id DESC limit 10");
	
        $html = '';
        if(!empty($abandon_array)){
        //set scroll for contents
        $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
        $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
        $i = 0;

        foreach($abandon_array as $abandon_key){
        $i++;
        $user_email = $abandon_key->user_email;
        $order_id = $abandon_key->order_id;
        $order_date = $abandon_key->date;
        if( $i %2 == 0 )
        {
                $html .= '<tr class="rowone">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
        else
        {
                $html .= '<tr class="rowtwo">';
                $html .= '<td>'.$user_email.'</td>';
                $html .= '<td style="padding-left:40px;">'.$order_id.'</td>';
                $html .= '<td>'. $order_date.'</td>';
                $html .= '</tr>';
        }
}
$html .= "</table>";
        }
else
{
		$html = '';
                $html .= "<table class='table woocustomer-table' id='abandon_cart_list' style='width:99%;border:1px solid #E5E5E5;'>";
                $html .= "<tr class='thone'><th class='col-md-4'>Customer Email</th><th class='col-md-4'>Order ID</th><th class='col-md-4'>Order Date</th></tr>";
                $html .= '<tr class="rowtwo">';
                $html .= '<td></td>';
                $html .= '<td>-- No Records Found --</td>';
                $html .= '<td></td>';
                $html .= '</tr>';
                $html .= '</table>';
}
	
        $return_range = json_encode( $html );
        print_r( $return_range );die;
	}

	public  static function wci_funnel_chart()
	{
		global $wpdb;
		$funnel_date = '';
		if( isset( $_REQUEST['filter_type'] )) {
		$funnel_date = sanitize_text_field($_REQUEST['filter_type']);
		}
		//Overall Filter


		// Custom Date Filter
		if( $funnel_date == 'customdate' || $funnel_date == "last_week" || $funnel_date == "last_month" || $funnel_date == "last7days" || $funnel_date == "last30days" || $funnel_date == "" )
		{
			switch( $funnel_date )
			{
				case 'customdate':
				$from_date = date('Y-m-d' , strtotime(sanitize_text_field($_REQUEST['from_date'])));
                        	$user_to_date = sanitize_text_field($_REQUEST['to_date']);
				$to_date = date('Y-m-d' , strtotime($user_to_date . "+1 days"));
				break;

				case 'last_week':
				$to_date =  date('Y-m-d', strtotime('last sunday'));
                		$start = strtotime ( '-7 days' , strtotime ( $to_date ) ) ;
                		$from_date = date ( 'Y-m-d' , $start );
				break;

				case 'last_month':
				$from_date =  date('Y-m-d', strtotime('first day of previous month'));
                        	$to_date =  date('Y-m-d', strtotime('last day of previous month'));
				break;

				case 'last7days':
				$from_date =  date('Y-m-d', strtotime('-7 days'));
                        	$to_date =  current_time('Y-m-d' , strtotime('-1 day'));
				break;
	
				case '':
				$from_date =  date('Y-m-d', strtotime('today - 30 days'));
                                $to_date =  current_time('Y-m-d', strtotime('-1 day'));	
                                break;

				case 'last30days':
				$from_date =  date('Y-m-d' , strtotime('today - 30 days'));
                        	$to_date =  current_time('Y-m-d', strtotime('-1 day'));
				break;
			}
				$from_date = $from_date .' '.'00:00:01';
				$to_date = $to_date .' '.'23:59:59';
			$total_visitors = $wpdb->get_results( "select count( distinct session_key,user_id) as total from wci_activity where date between '$from_date' and '$to_date'" );
			if( !empty( $total_visitors ))
			{
                		$total_visitors_count =(int) $total_visitors[0]->total;
			}
			else
			{
				$total_visitors_count = 0 ;
			}
                	$AddToCart = $wpdb->get_results("select count(distinct user_id) as event1 from wci_user_session where date between '$from_date' and '$to_date'");
			if( !empty( $AddToCart ))
			{
                		$event1 = (int) $AddToCart[0]->event1;
			}
			else
			{
				$event1 = 0;
			}
	
                	$Checkout_count = $wpdb->get_results( "select count(distinct user_id) as checkout_count from wci_user_session where date between '$from_date' and '$to_date' and is_checkout='1'" );
			if( !empty( $Checkout_count ))
			{
                		$Checkout = (int) $Checkout_count[0]->checkout_count;
			}
			else
			{
				$Checkout = 0;
			}
                	$successful_purchase = $wpdb->get_results( "select count(distinct user_id) as total_purchase from wci_user_session where date between '$from_date' and '$to_date' and payment_success='1'",ARRAY_A );
                if(!empty( $successful_purchase )) {
                        foreach(  $successful_purchase as $total_purchase )
                        {
                                $total_order_completed = (int) $total_purchase['total_purchase'];
                        }
                } else
                        {
                                $total_order_completed = 0;
                        }

//TIME SPENT

$info_visited_time = $wpdb->get_results("SELECT visited_url,spent_time FROM wci_activity ORDER BY spent_time DESC",ARRAY_A);

foreach($info_visited_time as $key => $value)
{
$most_visit[$value['visited_url']][]=$value['spent_time'];

}
        $k = 0;

        $most_spent_time = array();
$most_visited_spent_time = $wpdb->get_results( "SELECT information from wci_activity where date between '$from_date' and '$to_date'",ARRAY_A );
        foreach( $most_visited_spent_time as $info_key => $info_vals )
        {       $k++;
                $unserialized_spent_array = unserialize( $info_vals['information'] );
                $most_spent_time[$k]['prod_id'] = $unserialized_spent_array['prodid'];
                $most_spent_time[$k]['spent_time'] = $unserialized_spent_array['timespent'];
                $most_spent_time[$k]['visited_url'] = $unserialized_spent_array['page'];
        }

        $most_time_spent_prod_array = array();
        $count = 0;
        foreach( $most_spent_time as $prods_time_spent_array )
        {
                if( preg_match( "/product/" , $prods_time_spent_array['visited_url']  ) )
                        {       $count++;
                                $most_spent_prod_id = $prods_time_spent_array['prod_id'];
                                $most_time_spent_product = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->posts." where ID=%d" , $most_spent_prod_id),ARRAY_A );
                                $name_of_prod = isset($most_time_spent_product[0]['post_title']) ? $most_time_spent_product[0]['post_title'] : "";
                                $spent_time_of_prod = $prods_time_spent_array['spent_time'];
                                $most_time_spent_prod_array[$name_of_prod][$count] = $spent_time_of_prod;
                        }
        }
                foreach( $most_time_spent_prod_array as $prod_name => $most_time_product)
                {
                        $product_list[$prod_name] = array_sum($most_time_product );
                }
if(empty($product_list))
{
$time_spent = '';
$time_spent ="
<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>
<thead>
<tr class='thone'>
<th class='col-md-4'> Product Name</th>
<th class='col-md-4'></th>
<th>Spent Time (in sec)</th>
</tr>
</thead>
<tr class='rowtwo'><td colspan='3' style='text-align:center;'>-- No Records Found --</td></tr>
</table>";
}
else
{
$time_spent = '';
$time_spent .= "
<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>

<tr class='thone'>
<th class='col-md-6'> Product Name</th>
<th class='col-md-5' style='text-align:right;'>Spent Time ( M & S)</th>
<th class='col-md-1'></th>
</tr>
";
arsort( $product_list );
$i = 0;
foreach($product_list as $most_visited_page_key=> $most_visited_page_value) {
                $i++;
                if( $i % 2 == 0 )
                {
                $time_spent .= '<tr class="rowone">';
		$time_spent .= '<td>'. $most_visited_page_key.'</td>';
                $time_spent .= '<td style="text-align:right;padding-right:15px;">'. gmdate('i:s' ,$most_visited_page_value).'</td>';
		$time_spent .= '<td></td>';
                $time_spent .= '</tr>';
                }
                else
                {
                $time_spent .= '<tr class="rowtwo">';
                $time_spent .= '<td>'. $most_visited_page_key.'</td>';
                $time_spent .= '<td style="text-align:right;padding-right:15px;">'. gmdate('i:s' ,$most_visited_page_value).'</td>';
		$time_spent .= '<td></td>';
                $time_spent .= '</tr>';
                }
}

$time_spent .= "</table>";
}


//NO OF VISITS

$visit_pages = $wpdb->get_results("select information from wci_activity where date between '$from_date' and '$to_date'" ,ARRAY_A );

        $visit_page = array();
        $j = 0;
        foreach( $visit_pages as $visit_key => $visit_info)
        {       $j++;
                $unserialized_info = unserialize( $visit_info['information'] );
                $visit_page[$j]['prod_id'] = $unserialized_info['prodid'];              
                $visit_page[$j]['prod_pageurl'] = $unserialized_info['page'];
        }
$product_name = array();
foreach( $visit_page as $value ){
        if( preg_match("/product/",$value['prod_pageurl'] ) )
        {
                $product_id = $value['prod_id'] ;
                $get_prod_from_db = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->posts." where ID=%d " , $product_id ),ARRAY_A );
                $product_name[] = isset($get_prod_from_db[0]['post_title']) ? $get_prod_from_db[0]['post_title'] : "";
                $prod_url = $value['prod_pageurl'] ;
        }
}
                $product_name = array_count_values( $product_name );
                arsort( $product_name );

$prod_no_of_visit = '';
if(empty($product_name))
{

$prod_no_of_visit = '<table class="table woocustomer-table" style="width:100%;border:1px solid #E5E5E5;" >

<tr class="thone">
<th class="col-md-4">product Name</th>
<th class="col-md-4"></th>
<th>Number Of Visits</th>
</tr>

<tr class="rowtwo"> <td colspan="3" style="text-align:center;">-- No Records Found --</td></tr>
</table>';
}
else
{
$prod_no_of_visit = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>

<tr class='thone'>
<th class='col-md-7'>Product Name</th>
<th class='col-md-4' style='text-align:right;'>Number Of Visits</th>
<th class='col-md-1'></th>
</tr>
";

 $i = 0;
 foreach ($product_name as $url_key => $url_val){
                $i++;

                if( $i % 2 == 0 ){
                $prod_no_of_visit .= '<tr class="rowone">';
                $prod_no_of_visit .= '<td>'. $url_key."</td>";
                $prod_no_of_visit .= '<td style="text-align:right;padding-right:15px;">'. $url_val.'</td>';
		$prod_no_of_visit .= '<td></td>';
		$prod_no_of_visit .= '</tr>';
                }
                else
                {
                $prod_no_of_visit .= '<tr class="rowtwo">';
                $prod_no_of_visit .= '<td>'. $url_key.'</td>';
                $prod_no_of_visit .= '<td style="text-align:right;padding-right:15px;">'. $url_val.'</td>';
		$prod_no_of_visit .= '<td></td>';
                $prod_no_of_visit .= '</tr>';
                }
            }
$prod_no_of_visit .= '</table>';
                                                                                                  
}


//ORDER DETAILS
$statuses = '';
$currency = '';
$active_plugins = get_option( "active_plugins" );
if( in_array("woocommerce/woocommerce.php" , $active_plugins))
{
	$all_statuses = $ids = array();
	$all_statuses = wc_get_order_statuses();
	$currency =  get_woocommerce_currency_symbol();
}
if( !empty($all_statuses)) {
foreach($all_statuses as $status_key => $status_value){
        $statuses .= '"'.$status_key.'",';
}
$statuses =  rtrim($statuses,',');
}
$orders_info = $wpdb->get_results( "select * from ".$wpdb->posts." where post_status IN ($statuses) and post_date between '$from_date' and '$to_date'" );
foreach($orders_info as $o_key => $o_value){
        $order = new WC_Order($o_value->ID);
        $ord_id = $o_value->ID;
        $ord_date = $order->order_date;
        $ord_email = $order->billing_email;
        $items = $order->get_items();
        $total_price = 0;
        foreach($items as $ikey => $ivalue ){

                $total_price = $total_price + $ivalue['line_total'];
        }
        $total_order_price = $order->get_total();
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
             $coupon_code = "--";
             $coupon_amount = "--";
             $coupon_discount = "--";
        }

        $customer_ranking[] = array( "order_id" => $ord_id,"order_date" => $ord_date, "price" => $total_order_price, "order_email" => $ord_email,"count" => 1, "coupon_code" => $coupon_code , "coupon_amount" => $coupon_amount );
}

$price = array();
if( !empty( $customer_ranking ) ){
foreach ($customer_ranking as $key => $row)
{
        $price[$key] = $row['price'];
}

array_multisort($price, SORT_DESC, $customer_ranking);
}

$sum_price = $sum_orders = array();
if( !empty( $customer_ranking ) ) {
$sum_price = array_reduce($customer_ranking, function ($a, $b) {
                isset($a[$b['order_email']]) ? $a[$b['order_email']]['price'] += $b['price'] : $a[$b['order_email']] = $b;
                return $a;
                });
}

$sort_price = array();

if( !empty($sum_price) ){
foreach ($sum_price as $key => $row)
{
        $sort_price[$key] = $row['price'];
}
array_multisort($sort_price, SORT_DESC, $sum_price);
$price_Array = array_slice($sum_price,0,5,true);
}
$customer_by_revenue = '';
if( !empty( $price_Array )) {
$customer_by_revenue = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$customer_by_revenue .= "<tr class='thone'>
<th class='col-md-6'>Customer Id</th>
<th class='col-md-5' style='text-align:right;'>Purchased For ( in $currency ) </th>
<th class='col-md-1'></th>
</tr>";

$i = 0;
foreach($price_Array as $price_key => $price_value){
        $i++;
        if( $i % 2 == 0 )
        {
        $price_decimal = number_format((float)$price_value['price'], 2, '.', '');
        $customer_by_revenue .= '<tr class="rowone">';
        $customer_by_revenue .= '<td>'.$price_value['order_email'].'</td>';
        $customer_by_revenue .= '<td style="text-align:right;padding-right:15px;">'.$price_decimal.'</td>';
	$customer_by_revenue .= '<td></td>';
        $customer_by_revenue .= '</tr>';
        }
        else
        {
        $price_decimal = number_format((float)$price_value['price'], 2, '.', '');
        $customer_by_revenue .= '<tr class="rowtwo">';
        $customer_by_revenue .= '<td>'.$price_value['order_email'].'</td>';
        $customer_by_revenue .= '<td style="text-align:right;padding-right:15px;">'.$price_decimal.'</td><td></td>';
        $customer_by_revenue .= '</tr>';
        }
}
}
else
{
	$customer_by_revenue = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
	$customer_by_revenue .= "<tr class='thone'>
	<th class='col-md-4'>Customer Id</th>
	<th class='col-md-3'></th>
	<th class='col-md-5'>Purchased For ( in $currency ) </th>
	</tr>";

	$customer_by_revenue .= '<tr class="rowtwo">';
        $customer_by_revenue .= '<td colspan="3" style="text-align:center;"> -- No Records Found --</td>';
        $customer_by_revenue .= '</tr>';
}
$customer_by_revenue .= "</table>";


	if( !empty( $customer_ranking )) {
$sum_orders = array_reduce($customer_ranking, function ($c, $d) {
                isset($c[$d['order_email']]) ? $c[$d['order_email']]['count'] += $d['count'] : $c[$d['order_email']] = $d;
                return $c;
                });
}
$sort_orders = array();
if( !empty($sum_orders) ) {
foreach ($sum_orders as $key => $row)
{
        $sort_orders[$key] = $row['count'];
}
array_multisort($sort_orders, SORT_DESC, $sum_orders);
$price_Array = array_slice($sum_orders,0,5,true);
}
$customers_by_orders = '';
if( !empty($price_Array) ){
$customers_by_orders = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$customers_by_orders .= "<tr class='thone'>
<th class='col-md-7'>Customer Id</th>
<th class='col-md-4' style='text-align:right;'>Purchased Orders</th>
<th class='col-md-1'></th>
</tr>";

$i =0;
foreach($price_Array as $orders_key => $orders_value){
        $i++;
        if( $i % 2 == 0 )
        {
       $customers_by_orders .= "<tr class='rowone'>";
       $customers_by_orders .= '<td>'.$orders_value['order_email'].'</td>';
       $customers_by_orders .=  '<td style="text-align:right;padding-right:15px;">'.$orders_value['count'].'</td><td></td>';
       $customers_by_orders .=  '</tr>';
        }
        else
        {
        $customers_by_orders .= "<tr class='rowtwo'>";
        $customers_by_orders .= '<td>'.$orders_value['order_email'].'</td>';
        $customers_by_orders .= '<td style="text-align:right;padding-right:15px;">'.$orders_value['count'].'</td><td></td>';
        $customers_by_orders .= '</tr>';
        }
}
}else
{
	$customers_by_orders = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
	$customers_by_orders .= "<tr class='thone'>
	<th class='col-md-4'>Customer Id</th>
	<th class='col-md-4'></th>
	<th>Purchased Orders</th>
	</tr>";

	$customers_by_orders .= "<tr class='rowtwo'>";
        $customers_by_orders .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
        $customers_by_orders .= '</tr>';
}

$customers_by_orders .= "</table>";

//Order - Summary 

$orders_info = $wpdb->get_results( "select * from ".$wpdb->posts." where post_status IN ($statuses) and post_date between '$from_date' and '$to_date'",ARRAY_A);
$sort_order_info = array();
$order_summary = '';
if( !empty( $orders_info ))
{
$order_summary = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$order_summary .= "<tr class='thone'><th class='col-md-2' style='text-align:left;'>Order Id </th><th class='col-md-1'></th><th class='col-md-4'>Order Holder ID</th>
<th class='col-md-4' style='text-align:right;'>Total(in ".$currency." )</th><th class='col-md-1'></th></tr>";
foreach ($orders_info as $key => $row)
{
        $sort_order_info[$key] = $row['ID'];
}
array_multisort($sort_order_info, SORT_DESC, $orders_info);
$recent_orders = array_slice($orders_info,0,5,true);
$order_query_string = admin_url()."post.php?post=";
$i = 0;
foreach($recent_orders as $o_key => $o_value){
        $i++;
        $order = new WC_Order($o_value['ID']);
        $ord_id = $o_value['ID'];
        $ord_date = $order->order_date;
        $ord_email = $order->billing_email;

        $items = $order->get_items();
        $total_price = 0;
        foreach($items as $ikey => $ivalue ){

                $total_price = $total_price + $ivalue['line_total'];

        }
        $total_order_price = $order->get_total();
        if( $i % 2 == 0 )
        {
        $Order_total_decimal = number_format((float)$total_order_price, 2, '.', '');
        $order_summary .= "<tr class='rowone'>";
        $order_summary .= '<td style="text-align:right;padding-right:88px;"><a target="_blank" id="modall" href="'.esc_url($order_query_string.$ord_id).'&action=edit" >'.$ord_id.'</a></td><td></td>';
        $order_summary .= '<td>'.$ord_email.'</td>';
        $order_summary .= '<td style="text-align:right;padding-right:15px;"">'.$Order_total_decimal.'</td><td></td>';
        $order_summary .= "</tr>";
        }
        else
        {
	        $Order_total_decimal = number_format((float)$total_order_price, 2, '.', '');
        $order_summary .= "<tr class='rowtwo'>";
        $order_summary .= '<td style="text-align:right;padding-right:88px;"><a target="_blank" id="modall" href="'.esc_url($order_query_string.$ord_id).'&action=edit" >'.$ord_id.'</a></td><td></td>';
        $order_summary .= '<td>'.$ord_email.'</td>';
        $order_summary .= '<td style="text-align:right;padding-right:15px;">'.$Order_total_decimal.'</td><td></td>';
        $order_summary .= "</tr>";
        }
}
}
else
{
	$order_summary = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $order_summary .= "<tr class='thone'><th class='col-md-4'>Order Id </th><th class='col-md-4'>Order Holder ID</th></th>
        <th class='col-md-4'>Total(in ".$currency." )</th></tr>";

	$order_summary .= "<tr class='rowtwo'>";
        $order_summary .= '<td colspan="3" style="text-align:center;"> -- No Records Found -- </td>';
        $order_summary .= "</tr>";
}
$order_summary .= "</table>";

//MOST SOLD

$post_title = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->prefix."posts where post_type=%s", 'product'));
        $prod_list = array();
        foreach( $post_title as $title=>$prod)
        {
                foreach( $prod as $key=>$productt )
                {
                        $prod_list[] = $productt;
                }
        }
        $i = 0;
	$total_sales = array();
        foreach( $prod_list as $products )
        {
                $i++;
                $total_sales[] = $wpdb->get_results( "select order_item_name,count(*) as total_count from {$wpdb->prefix}woocommerce_order_items as a join {$wpdb->prefix}posts as b on a.order_id=b.ID where order_item_name like '$products' and post_date between '$from_date%' and '$to_date'");
        }

	if( !empty( $total_sales ) ) {
        foreach( $total_sales as $sales_count )
        {
                if( ($sales_count[0]->total_count) > 0 )
                {
                        $sold_product_name = $sales_count[0]->order_item_name;
                        $sold_product_count = $sales_count[0]->total_count;
                        $most_sold_products[$sold_product_name] = $sold_product_count;
                }
        }
	}
	$most_sold = '';
        if( !empty($most_sold_products) ){
        arsort( $most_sold_products );
        $i = 0;

	$most_sold = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $most_sold .= "<tr class='thone'>
        <th class='col-md-7'>Product</th>
        <th class='col-md-4' style='text-align:right;'>No of times</th>
	<th class='col-md-1'></th>
        </tr>";

        foreach($most_sold_products as $ms_prod_key => $ms_prod_value ){
        $i++;
        if( $i % 2 == 0 )
        {
        $most_sold .= "<tr class='rowone'>";
        $most_sold .= '<td>'. $ms_prod_key.'</td>';
        $most_sold .= '<td style="padding-right:15px;text-align:right;">'.$ms_prod_value.'</td><td></td>';
        $most_sold .= '</tr>';
        }
        else
        {
        $most_sold .= "<tr class='rowtwo'>";
        $most_sold .= '<td>'. $ms_prod_key.'</td>';
        $most_sold .= '<td style="padding-right:15px;text-align:right;">'.$ms_prod_value.'</td><td></td>';
        $most_sold .= '</tr>';
        }
        }
}
else
{
	$most_sold .= "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $most_sold .= "<tr class='thone'>
        <th class='col-md-4'>Product</th>
	<th class='col-md-4'></th>
        <th class='col-md-4'>No of times</th>
        </tr>";

        $most_sold .= "<tr class='rowtwo'>";
        $most_sold .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
        $most_sold .= '</tr>';
}
$most_sold .= "</table>";

//END MOST SOLD

		}
	
		// Daily Filter
		if( $funnel_date == "today" || $funnel_date == "yesterday"  ) {

			switch( $funnel_date )
			{
				case 'today':
				$funnel_date = current_time('Y-m-d');
				break;
				case 'yesterday':
				$funnel_date = date('Y-m-d' , strtotime('-1 day'));
				break;
			}
		 $total_visitors = $wpdb->get_results( "select count( distinct session_key,user_id) as total from wci_activity where date like ('$funnel_date%')" );

                
		if( !empty( $total_visitors ))
		{
			$total_visitors_count =(int) $total_visitors[0]->total;
		}
		else
		{
			$total_visitors_count = 0;
		}

		$AddToCart = $wpdb->get_results("select count(distinct user_id) as event1 from wci_user_session where date like '$funnel_date%'");
		if( !empty( $AddToCart ))
		{
                	$event1 = (int) $AddToCart[0]->event1;
		}
		else
		{
			$event1 = 0;
		}
		$Checkout_count = $wpdb->get_results( "select count(distinct user_id) as checkout_count from wci_user_session where is_checkout='1' and date like '$funnel_date%'" );
		if( !empty( $Checkout_count ))
		{
                	$Checkout = (int) $Checkout_count[0]->checkout_count;		
		}
		else
		{
			$Checkout = 0;
		}	
		$successful_purchase = $wpdb->get_results( "select distinct count(order_id) as total_purchase from wci_successful_purchases where order_status='Completed' and date like '$funnel_date%'",ARRAY_A );
                if(!empty( $successful_purchase )) {
                        foreach(  $successful_purchase as $total_purchase )
                        {
                                $total_order_completed = (int) $total_purchase['total_purchase'];
                        }
                } else
                        {
                                $total_order_completed = 0;
                        }

//TIME SPENT

$info_visited_time = $wpdb->get_results("SELECT visited_url,spent_time FROM wci_activity ORDER BY spent_time DESC",ARRAY_A);

foreach($info_visited_time as $key => $value)
{
$most_visit[$value['visited_url']][]=$value['spent_time'];

}
        $k = 0;

        $most_spent_time = array();
$most_visited_spent_time = $wpdb->get_results( "SELECT * from wci_activity where date like '$funnel_date'",ARRAY_A );
        foreach( $most_visited_spent_time as $info_key => $info_vals )
        {       $k++;
                $unserialized_spent_array = unserialize( $info_vals['information'] );
                $most_spent_time[$k]['prod_id'] = $unserialized_spent_array['prodid'];
                $most_spent_time[$k]['spent_time'] = $unserialized_spent_array['timespent'];
                $most_spent_time[$k]['visited_url'] = $unserialized_spent_array['page'];
        }

        $most_time_spent_prod_array = array();
        $count = 0;
        foreach( $most_spent_time as $prods_time_spent_array )
        {
                if( preg_match( "/product/" , $prods_time_spent_array['visited_url']  ) )
                        {       $count++;
                                $most_spent_prod_id = $prods_time_spent_array['prod_id'];
                                $most_time_spent_product = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->posts." where ID=%d" , $most_spent_prod_id),ARRAY_A );
				$name_of_prod = isset($most_time_spent_product[0]['post_title']) ? $most_time_spent_product[0]['post_title'] : "";
                                $spent_time_of_prod = $prods_time_spent_array['spent_time'];
                                $most_time_spent_prod_array[$name_of_prod][$count] = $spent_time_of_prod;
                        }
        }
                foreach( $most_time_spent_prod_array as $prod_name => $most_time_product)
                {
                        $product_list[$prod_name] = array_sum($most_time_product );
                }
if(empty($product_list))
{
$time_spent = '';
$time_spent ="
<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>
<thead>
<tr class='thone'>
<th class='col-md-4'> Product Name</th>
<th class='col-md-4'></th>
<th>Spent Time (in sec)</th>
</tr>
</thead>
<tr class='rowtwo'><td colspan='3' style='text-align:center;'>-- No Records Found --</td></tr>
</table>";
}
else
{
$time_spent = '';
$time_spent .= "
<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>

<tr class='thone'>
<th class='col-md-6'> Product Name</th>
<th class='col-md-5' style='text-align:right;' >Spent Time ( M & S)</th>
<th class='col-md-1'></th>
</tr>
";
arsort( $product_list );
$i = 0;
foreach($product_list as $most_visited_page_key=> $most_visited_page_value) {
                $i++;
                if( $i % 2 == 0 )
                {
                $time_spent .= '<tr class="rowone">';
		$time_spent .= '<td>'. $most_visited_page_key.'</td>';
                $time_spent .= '<td style="text-align:right;padding-right:15px;">'. gmdate('i:s' ,$most_visited_page_value).'</td>';
		$time_spent .= '<td></td>';
                $time_spent .= '</tr>';
                }
                else
                {
                $time_spent .= '<tr class="rowtwo">';
                $time_spent .= '<td>'. $most_visited_page_key.'</td>';
                $time_spent .= '<td style="text-align:right;padding-right:15px;">'. gmdate('i:s' ,$most_visited_page_value).'</td>';
		$time_spent .= '<td></td>';
                $time_spent .= '</tr>';
                }
}

$time_spent .= "</table>";
}

	
//NO OF VISITS

$visit_pages = $wpdb->get_results( "select information from wci_activity where date like '$funnel_date'",ARRAY_A );

        $visit_page = array();
        $j = 0;
        foreach( $visit_pages as $visit_key => $visit_info)
        {       $j++;
                $unserialized_info = unserialize( $visit_info['information'] );
                $visit_page[$j]['prod_id'] = $unserialized_info['prodid'];              
                $visit_page[$j]['prod_pageurl'] = $unserialized_info['page'];
        }
$product_name = array();
foreach( $visit_page as $value ){
        if( preg_match("/product/",$value['prod_pageurl'] ) )
        {
                $product_id = $value['prod_id'] ;
                $get_prod_from_db = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->posts." where ID=%d " , $product_id ),ARRAY_A );
                $product_name[] = isset($get_prod_from_db[0]['post_title']) ? $get_prod_from_db[0]['post_title'] : "" ;
                $prod_url = $value['prod_pageurl'] ;
        }
}
                $product_name = array_count_values( $product_name );
                arsort( $product_name );
$prod_no_of_visit = '';
if(empty($product_name))
{

$prod_no_of_visit = '<table class="table woocustomer-table" style="width:100%;border:1px solid #E5E5E5;" >
<thead>
<tr class="thone">
<th class="col-md-4">product Name</th>
<th class="col-md-4"></th>
<th class="col-md-4">Number Of Visits</th>
</tr>
</thead>
<tr class="rowtwo"> <td colspan="3" style="text-align:center;">-- No Records Found --</td></tr>
</table>';
}
else
{
$prod_no_of_visit .= "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>
<thead>
<tr class='thone'>
<th class='col-md-7'>Product Name</th>
<th class='col-md-4' style='text-align:right;'>Number Of Visits</th>
<th class='col-md-1'></th>
</tr>
</thead>";

 $i = 0;
 foreach ($product_name as $url_key => $url_val){
                $i++;

                if( $i % 2 == 0 ){
                $prod_no_of_visit .= '<tr class="rowone">';
                $prod_no_of_visit .= '<td>'. $url_key."</td>";
                $prod_no_of_visit .= '<td style="text-align:right;padding-right:15px;">'. $url_val.'</td>';
		$prod_no_of_visit .= '<td></td>';
		$prod_no_of_visit .= '</tr>';
                }
                else
                {
                $prod_no_of_visit .= '<tr class="rowtwo">';
                $prod_no_of_visit .= '<td>'. $url_key."</td>";
                $prod_no_of_visit .= '<td style="text-align:right;padding-right:15px;">'. $url_val.'</td>';
		$prod_no_of_visit .= '<td></td>';
                $prod_no_of_visit .= '</tr>';
                }
            }
$prod_no_of_visit .= '</table>';
                                                                                                  
}

	
//ORDER DETAILS
$statuses = '';
$currency = '';
$active_plugins = get_option( "active_plugins" );
if( in_array("woocommerce/woocommerce.php" , $active_plugins))
{
	$all_statuses = $ids = array();
	$all_statuses = wc_get_order_statuses();
	$currency =  get_woocommerce_currency_symbol();
}
if( !empty( $all_statuses )) {
foreach($all_statuses as $status_key => $status_value){
        $statuses .= '"'.$status_key.'",';
}
$statuses =  rtrim($statuses,',');
}
$orders_info = $wpdb->get_results( "select * from ".$wpdb->posts." where post_date like '$funnel_date%' and post_status IN ($statuses)" );
foreach($orders_info as $o_key => $o_value){
        $order = new WC_Order($o_value->ID);
        $ord_id = $o_value->ID;
        $ord_date = $order->order_date;
        $ord_email = $order->billing_email;
        $items = $order->get_items();
        $total_price = 0;
        foreach($items as $ikey => $ivalue ){

                $total_price = $total_price + $ivalue['line_total'];
        }
        $total_order_price = $order->get_total();
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
             $coupon_code = "--";
             $coupon_amount = "--";
             $coupon_discount = "--";
        }

        $customer_ranking[] = array( "order_id" => $ord_id,"order_date" => $ord_date, "price" => $total_order_price, "order_email" => $ord_email,"count" => 1, "coupon_code" => $coupon_code , "coupon_amount" => $coupon_amount );
}

$price = array();
if( !empty( $customer_ranking ) ){
foreach ($customer_ranking as $key => $row)
{
        $price[$key] = $row['price'];
}

array_multisort($price, SORT_DESC, $customer_ranking);
}

$sum_price = $sum_orders = array();
if( !empty( $customer_ranking ) ) {
$sum_price = array_reduce($customer_ranking, function ($a, $b) {
                isset($a[$b['order_email']]) ? $a[$b['order_email']]['price'] += $b['price'] : $a[$b['order_email']] = $b;
                return $a;
                });
}

$sort_price = array();

if( !empty($sum_price) ){
foreach ($sum_price as $key => $row)
{
        $sort_price[$key] = $row['price'];
}
array_multisort($sort_price, SORT_DESC, $sum_price);
$price_Array = array_slice($sum_price,0,5,true);
}
$customer_by_revenue = '';
if( !empty( $price_Array )) {
$customer_by_revenue = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$customer_by_revenue .= "<tr class='thone'>
<th class='col-md-6'>Customer Id</th>
<th class='col-md-5' style='text-align:right;'>Purchased For ( in $currency ) </th>
<th class='col-md-1'></th>
</tr>";

$i = 0;
foreach($price_Array as $price_key => $price_value){
        $i++;
        if( $i % 2 == 0 )
        {
        $price_decimal = number_format((float)$price_value['price'], 2, '.', '');
        $customer_by_revenue .= '<tr class="rowone">';
        $customer_by_revenue .= '<td>'.$price_value['order_email'].'</td>';
        $customer_by_revenue .= '<td style="text-align:right;padding-right:15px;">'.$price_decimal.'</td>';
	$customer_by_revenue .= '<td></td>';
        $customer_by_revenue .= '</tr>';
        }
        else
        {
        $price_decimal = number_format((float)$price_value['price'], 2, '.', '');
        $customer_by_revenue .= '<tr class="rowtwo">';
        $customer_by_revenue .= '<td>'.$price_value['order_email'].'</td>';
        $customer_by_revenue .= '<td style="text-align:right;padding-right:15px;">'.$price_decimal.'</td>';
	$customer_by_revenue .= '<td></td>';
        $customer_by_revenue .= '</tr>';
        }
}
}
else
{
	$customer_by_revenue = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
	$customer_by_revenue .= "<tr class='thone'>
	<th class='col-md-4'>Customer Id</th>
	<th class='col-md-3'></th>
	<th class='col-md-5'>Purchased For ( in $currency ) </th>
	</tr>";

	$customer_by_revenue .= '<tr class="rowtwo">';
        $customer_by_revenue .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
        $customer_by_revenue .= '</tr>';
}
$customer_by_revenue .= "</table>";


	if( !empty( $customer_ranking )) {
$sum_orders = array_reduce($customer_ranking, function ($c, $d) {
                isset($c[$d['order_email']]) ? $c[$d['order_email']]['count'] += $d['count'] : $c[$d['order_email']] = $d;
                return $c;
                });
}
$sort_orders = array();
if( !empty($sum_orders) ) {
foreach ($sum_orders as $key => $row)
{
        $sort_orders[$key] = $row['count'];
}
array_multisort($sort_orders, SORT_DESC, $sum_orders);
$price_Array = array_slice($sum_orders,0,5,true);
}

$customers_by_orders = '';
if( !empty($price_Array) ){
$customers_by_orders .= "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$customers_by_orders .= "<tr class='thone'>
<th class='col-md-7'>Customer Id</th>
<th class='col-md-4' style='text-align:right;'>Purchased Orders</th>
<th class='col-md-1'></th>
</tr>";

$i =0;
foreach($price_Array as $orders_key => $orders_value){
        $i++;
        if( $i % 2 == 0 )
        {
       $customers_by_orders .= "<tr class='rowone'>";
       $customers_by_orders .= '<td>'.$orders_value['order_email'].'</td>';
       $customers_by_orders .=  '<td style="text-align:right;padding-right:15px;">'.$orders_value['count'].'</td><td></td>';
       $customers_by_orders .=  '</tr>';
        }
        else
        {
        $customers_by_orders .= "<tr class='rowtwo'>";
        $customers_by_orders .= '<td>'.$orders_value['order_email'].'</td>';
        $customers_by_orders .= '<td style="text-align:right;padding-right:15px;">'.$orders_value['count'].'</td><td></td>';
        $customers_by_orders .= '</tr>';
        }
}
}
else
{
	$customers_by_orders = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
	$customers_by_orders .= "<tr class='thone'>
	<th class='col-md-4'>Customer Id</th>
	<th class='col-md-4'></th>
	<th>Purchased Orders</th>
	</tr>";
	$customers_by_orders .= "<tr class='rowtwo'>";
        $customers_by_orders .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
	$customers_by_orders .= '</tr>';
}
$customers_by_orders .= "</table>";
//Order - Summary 

$orders_info = $wpdb->get_results( "select * from ".$wpdb->posts." where post_status IN ($statuses) and post_date like '$funnel_date%'",ARRAY_A);
$sort_order_info = array();
$order_summary = '';
if( !empty( $orders_info ))
{
$order_summary = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
$order_summary .= "<tr class='thone'><th class='col-md-2' style='text-align:left;'>Order Id </th><th class='col-md-1'></th><th class='col-md-4'>Order Holder ID</th>
<th class='col-md-4' style='text-align:right;'>Total(in ".$currency." )</th><th class='col-md-1'></th></tr>";

foreach ($orders_info as $key => $row)
{
        $sort_order_info[$key] = $row['ID'];
}
array_multisort($sort_order_info, SORT_DESC, $orders_info);
$recent_orders = array_slice($orders_info,0,5,true);
$order_query_string = admin_url()."post.php?post=";
$i = 0;
foreach($recent_orders as $o_key => $o_value){
        $i++;
        $order = new WC_Order($o_value['ID']);
        $ord_id = $o_value['ID'];
        $ord_date = $order->order_date;
        $ord_email = $order->billing_email;

        $items = $order->get_items();
        $total_price = 0;
        foreach($items as $ikey => $ivalue ){

                $total_price = $total_price + $ivalue['line_total'];

        }
        $total_order_price = $order->get_total();
        if( $i % 2 == 0 )
        {
        $Order_total_decimal = number_format((float)$total_order_price, 2, '.', '');
        $order_summary .= "<tr class='rowone'>";
        $order_summary .= '<td style="text-align:right;padding-right:88px;"><a target="_blank" id="modall" href="'.esc_url($order_query_string.$ord_id).'&action=edit" >'.$ord_id.'</a></td><td></td>';
        $order_summary .= '<td>'.$ord_email.'</td>';
        $order_summary .= '<td style="text-align:right;padding-right:15px;">'.$Order_total_decimal.'</td><td></td>';
        $order_summary .= "</tr>";
        }
        else
        {
        $Order_total_decimal = number_format((float)$total_order_price, 2, '.', '');
        $order_summary .= "<tr class='rowtwo'>";
        $order_summary .= '<td style="text-align:right;padding-right:88px;"><a target="_blank" id="modall" href="'.esc_url($order_query_string.$ord_id).'&action=edit" >'.$ord_id.'</a></td><td></td>';
        $order_summary .= '<td>'.$ord_email.'</td>';
        $order_summary .= '<td style="text-align:right;padding-right:15px;"">'.$Order_total_decimal.'</td><td></td>';
        $order_summary .= "</tr>";
        }
}
}
else
{
	$order_summary = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $order_summary .= "<tr class='thone'><th class='col-md-4'>Order Id </th><th class='col-md-4'>Order Holder ID</th></th>
	<th class='col-md-4'>Total(in ".$currency." )</th></tr>";

	$order_summary .= "<tr class='rowtwo'>";
        $order_summary .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
        $order_summary .= "</tr>";
}
$order_summary .= "</table>";


//MOST SOLD

$post_title = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->prefix."posts where post_type=%s", 'product'));
//      $post_title = $wpdb->get_results("select post_title from ".$wpdb->prefix."posts where post_type='product'");
        $prod_list = array();
        foreach( $post_title as $title=>$prod)
        {
                foreach( $prod as $key=>$productt )
                {
                        $prod_list[] = $productt;
                }
        }
        $i = 0;
	$total_sales = array();
        foreach( $prod_list as $products )
        {
                $i++;
                $total_sales[] = $wpdb->get_results( "select order_item_name,count(*) as total_count from {$wpdb->prefix}woocommerce_order_items as a join {$wpdb->prefix}posts as b on a.order_id=b.ID where order_item_name like '$products' and post_date like '$funnel_date%'");
        }
	if( !empty( $total_sales )) {
        foreach( $total_sales as $sales_count )
        {
                if( ($sales_count[0]->total_count) > 0 )
                {
                        $sold_product_name = $sales_count[0]->order_item_name;
                        $sold_product_count = $sales_count[0]->total_count;
                        $most_sold_products[$sold_product_name] = $sold_product_count;
                }
        }
	}
	$most_sold = '';
        if( !empty($most_sold_products) ){
        arsort( $most_sold_products );
        $i = 0;

	$most_sold = "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $most_sold .= "<tr class='thone'>
        <th class='col-md-7'>Product</th>
        <th class='col-md-4' style='text-align:right;'>No of times</th>
	<th class='col-md-1'></th>
        </tr>";

        foreach($most_sold_products as $ms_prod_key => $ms_prod_value ){
        $i++;
        if( $i % 2 == 0 )
        {
        $most_sold .= "<tr class='rowone'>";
        $most_sold .= '<td>'. $ms_prod_key.'</td>';
        $most_sold .= '<td style="text-align:right;padding-right:15px;">'.$ms_prod_value.'</td><td></td>';
        $most_sold .= '</tr>';
        }
        else
        {
        $most_sold .= "<tr class='rowtwo'>";
        $most_sold .= '<td>'. $ms_prod_key.'</td>';
        $most_sold .= '<td style="text-align:right;padding-right:15px;">'.$ms_prod_value.'</td><td></td>';
        $most_sold .= '</tr>';
        }
        }
}
else
{
	$most_sold .= "<table class='table woocustomer-table' style='width:100%;border:1px solid #E5E5E5;'>";
        $most_sold .= "<tr class='thone'>
        <th class='col-md-4'>Product</th>
	<th class='col-md-4'></th>
        <th class='col-md-4'>No of times</th>
        </tr>";

        $most_sold .= "<tr class='rowone'>";
        $most_sold .= '<td colspan="3" style="text-align:center;">-- No Records Found --</td>';
        $most_sold .= '</tr>';
}
$most_sold .= "</table>";

}
//END MOST SOLD

		//Common data	
		$overall_data = array("total_visitors" => $total_visitors_count , "AddToCart" => $event1, "Checkout" => $Checkout, "SuccessfulPurchases" => $total_order_completed);

		$arr['name'] =  "Count";
		$arr['info'] =  $overall_data;
		$arr['time_spent'] = $time_spent;
		$arr['no_of_visit'] = $prod_no_of_visit;
		$arr['customers_by_revenue'] = $customer_by_revenue;
		$arr['customers_by_orders'] = $customers_by_orders;
		$arr['order_summary'] = $order_summary;
		$arr['most_sold'] = $most_sold;
		$funnel_input_array = $arr;
		print_r(json_encode($funnel_input_array));die;
	}

	public function wci_dashboard_chart()
	{
		global $wpdb;

		if(isset($_POST['date']) != '') {
		$dashboard_date_frmt = strtotime( $_POST['date'] );
		$funnel_date = date( 'Y-m-d' , $dashboard_date_frmt );
//			$funnel_date =  $_POST['date'];
		} else {
			$funnel_date = date('Y-m-d');
		}

		$total_visitors = $wpdb->get_results( "select count( distinct session_id,session_key) as total from wci_activity where date like ('$funnel_date%')" );

                
		if( !empty( $total_visitors ))
		{
			$total_visitors_count =(int) $total_visitors[0]->total;
		}
		else
		{
			$total_visitors_count = 0;
		}

		$AddToCart = $wpdb->get_results("select count(distinct session_id) as event1 from wci_user_session where date like '$funnel_date%'");
		if( !empty( $AddToCart ))
		{
                	$event1 = (int) $AddToCart[0]->event1;
		}
		else
		{
			$event1 = 0;
		}
		$Checkout_count = $wpdb->get_results( "select count(distinct user_id) as checkout_count from wci_user_session where is_checkout='1' and date like '$funnel_date%'" );
		if( !empty( $Checkout_count ))
		{
                	$Checkout = (int) $Checkout_count[0]->checkout_count;		
		}
		else
		{
			$Checkout = 0;
		}	
		$successful_purchase = $wpdb->get_results( "select distinct count(order_id) as total_purchase from wci_successful_purchases where order_status='Completed' and date like '$funnel_date%'",ARRAY_A );
                if(!empty( $successful_purchase )) {
                        foreach(  $successful_purchase as $total_purchase )
                        {
                                $total_order_completed = (int) $total_purchase['total_purchase'];
                        }
                } else
                        {
                                $total_order_completed = 0;
                        }


		$overall_data = array("total_visitors" => $total_visitors_count , "AddToCart" => $event1, "Checkout" => $Checkout, "SuccessfulPurchases" => $total_order_completed);	
		
		$arr['name'] =  "Count";
		$arr['info'] =  $overall_data;
		$funnel_input_array = $arr;
		print_r(json_encode($funnel_input_array));die;
	
}
	public function wci_get_pie_data(){
		global $wpdb;
		$target_person = sanitize_text_field($_POST['selected_customer']);
		$target_user_id = sanitize_text_field($_POST['selected_user_id']);
		$target_visited = $wpdb->get_results($wpdb->prepare("select count(*) as visited from wci_activity where user_id=%s", $target_person ));

		$target_visisted_pages =(int) $target_visited[0]->visited;
		$target_spent = $wpdb->get_results($wpdb->prepare("select sum(spent_time) as spent from wci_activity where user_id=%s" , $target_person));
		$target_spent_time =(int) $target_spent[0]->spent;
		$target_AddToCart = $wpdb->get_results($wpdb->prepare("select count(button_name) as AddToCart from wci_events where user_id=%s and button_name=%s" , $target_person , 'AddToCart'));		
		$target_AddToCart_count =(int) $target_AddToCart[0]->AddToCart;
		$target_ApplyCoupon = $wpdb->get_results($wpdb->prepare( "select distinct user_id,date,count,button_name from wci_events where user_id=%s and button_name=%s", $target_person , 'ApplyCoupon'),ARRAY_A );
		foreach($target_ApplyCoupon as $AC_key => $AC_value){
			$AC_array[] = $AC_value['count'];
		}
		if( !empty( $AC_array ) ) {
			$target_ApplyCoupon_count = (int) array_sum($AC_array);
		}
		else
		{
			$target_ApplyCoupon_count = 0;
		}
		
		$target_UpdateCart = $wpdb->get_results( "select distinct user_id,date,count,button_name from wci_events where user_id ='$target_person' and button_name='UpdateCart'",ARRAY_A );
		foreach($target_UpdateCart as $UC_key => $UC_value){
			$UC_array[] = $UC_value['count'];
		}
		if( !empty( $UC_array ) ) {
			$target_UpdateCart_count = (int) array_sum($UC_array);
		}
		else
		{
			$target_UpdateCart_count = 0;
		}
	
		$target_Checkout =$wpdb->get_results( "select distinct user_id,date,count from wci_events where user_id ='$target_person' and button_name='Checkout'",ARRAY_A );
		foreach($target_Checkout as $C_key => $C_value){
			$C_array[] = $C_value['count'];
		}
		if( !empty( $C_array )) {
			$target_Checkout_count = (int) array_sum($C_array);
		}
		else
		{
			$target_Checkout_count = 0;
		}
	
		$target_CashOnDelivery = $wpdb->get_results( "select distinct user_id,date,count,button_name from wci_events where user_id ='$target_person' and button_name='CashOnDelivery'",ARRAY_A );
		foreach($target_CashOnDelivery as $COD_key => $COD_value){
			$COD_array[] = $COD_value['count'];
		}
		if( !empty( $COD_array )) {
			$target_CashOnDelivery_count = (int) array_sum($COD_array);
		}
		else
		{
			$target_CashOnDelivery_count = 0;
		}
				$target_ProceedToPaypal = $wpdb->get_results( "select distinct user_id,date,count,button_name from wci_events where user_id ='$target_person' and button_name='ProceedToPaypal'",ARRAY_A );
		foreach($target_ProceedToPaypal as $PTP_key => $PTP_value){
			$PTP_array[] = $PTP_value['count'];
		}
		if( !empty( $PTP_array ) ) {
			$target_ProceedToPaypal_count = (int) array_sum($PTP_array);
			}
			else
			{
			$target_ProceedToPaypal_count = 0;
			}
				$target_DirectBankTransfer = $wpdb->get_results( "select distinct user_id,date,count,button_name from wci_events where user_id ='$target_person' and button_name='DirectBankTransfer'",ARRAY_A );
		foreach($target_DirectBankTransfer as $DBT_key => $DBT_value){
			$DBT_array[] = $DBT_value['count'];
		}
		if( !empty( $DBT_array ) ) {
				$target_DirectBankTransfer_count = (int) array_sum($DBT_array);
			}
			else
			{
				$target_DirectBankTransfer_count = 0;
			}
		$target_ChequePayment = $wpdb->get_results( "select distinct user_id,date,count,button_name from wci_events where user_id ='$target_person' and button_name='ChequePayment'",ARRAY_A );
		foreach($target_ChequePayment as $CP_key => $CP_value){
			$CP_array[] = $CP_value['count'];
		}
		if( !empty( $CP_array ) ) {
				$target_ChequePayment_count = (int) array_sum($CP_array);
			}
			else
			{
				$target_ChequePayment_count = 0;
			}
		$target_success_purchase = $wpdb->get_results( $wpdb->prepare("select count(distinct user_name,user_email,order_id) as success_count from wci_successful_purchases where user_name=%s and order_status=%s", $target_person , 'Completed') , ARRAY_A );
		$target_successful_purchase_count = $target_success_purchase[0]['success_count'];

		$response = array();
		$sum_value = $target_visisted_pages + $target_spent_time + $target_AddToCart_count + $target_ApplyCoupon_count + $target_UpdateCart_count + $target_Checkout_count +$target_CashOnDelivery_count + $target_ProceedToPaypal_count + $target_DirectBankTransfer_count + $target_ChequePayment_count ;
		if($sum_value == 0) {
			$response = null;
		}
		else{
			$pie_Array = array( "visited_pages" => $target_visisted_pages , "AddToCart" => $target_AddToCart_count , "ApplyCoupon" => $target_ApplyCoupon_count, "UpdateCart" => $target_UpdateCart_count, "Checkout" => $target_Checkout_count, "CashOnDelivery" => $target_CashOnDelivery_count, "ProceedToPaypal" => $target_ProceedToPaypal_count , "DirectBankTransfer" =>$target_DirectBankTransfer_count, "ChequePayment" => $target_ChequePayment_count , "Success_purchase" => $target_successful_purchase_count );

			$html = '';
			$info = array();
			$info = $wpdb->get_results("select * from wci_activity where user_id='$target_person' order by id desc");
			$html .= '<table class="table woocustomer-table" style="width:99%;border: 1px solid #E5E5E5;">
                               <tr class="thone">
                                   <th class="col-md-2">Name</th>
                                   <th class="col-md-2">Location</th>
                                   <th class="col-md-2">Page Title</th>
				   <th class="col-md-3">Time Spent(HH:MH:SS) </th>
				   <th class="col-md-2"> Date </th>
                                </tr>';
			$i = 0;
			foreach($info as $info_key => $info_value) {
			$hrs = floor($info_value->spent_time / 3600);
			$hours = sprintf("%02d", $hrs);
                        $mins = floor(($info_value->spent_time / 60) % 60);
			$minutes = sprintf("%02d", $mins);
                        $secs = $info_value->spent_time % 60;
			$seconds = sprintf("%02d", $secs);
                                $wci_time_spent = $hours.":".$minutes.":".$seconds;
			$i++;
				if( $i % 2 == 0 )
				{
				$html .= '<tr class="rowone">';
				$html .= '<td>'.$info_value->user_id .'</td>';
				$html .= '<td>'.$info_value->country .'</td>';
				$html .=  '<td>'.$info_value->page_title .'</td>';
				$html .= '<td style="text-align:center;">' .$wci_time_spent. '</td>';
				$html .= '<td>' .$info_value->date_time. '</td>';
				$html .=  '</tr>';
				}
				else
				{
				$html .= '<tr class="rowtwo">';
                                $html .= '<td>'.$info_value->user_id .'</td>';
                                $html .= '<td>'.$info_value->country .'</td>';
                                $html .=  '<td>'.$info_value->page_title .'</td>';
                                $html .= '<td style="text-align:center;">' .$wci_time_spent. '</td>';
                                $html .= '<td>' .$info_value->date_time. '</td>';
                                $html .=  '</tr>';
				}
			}
			$html .=  '</table>';
//GET ALL USER DATA
$get_user_email = get_user_by( 'id' , $target_user_id );
$wci_user_email = $get_user_email->data->user_email;
if( empty($wci_user_email))
{
	$get_email_for_guest = $wpdb->get_results($wpdb->prepare("select distinct user_email from wci_activity where user_id=%s and session_key=%s and user_email!=%s", "$target_person" , "$target_user_id" , ''), ARRAY_A);
	if(!empty($get_email_for_guest))
	{
		$wci_user_email = $get_email_for_guest[0]['user_email'];
	}
	else
	{
		$wci_user_email = "--";
	}
}
$last_login = $wpdb->get_results( $wpdb->prepare( "select login_time from wci_user_profile_updated where user_id=%d" , $target_user_id ) );
$wci_last_login = $last_login[0]->login_time;
if( empty( $wci_last_login ))
{
	$wci_last_login = " -- ";
}
$get_location = $wpdb->get_results( $wpdb->prepare( "select country from wci_activity where session_key=%d and user_id=%s order by id desc limit 1" , $target_user_id , $target_person ) );
$wci_location = $get_location[0]->country;
if( $wci_location == ',')
$wci_location = '--';
$get_order_count = $wpdb->get_results( $wpdb->prepare( "select count(*) as total_order from wci_successful_purchases where user_name=%s" , $target_person ) );
$wci_order_count = $get_order_count[0]->total_order;

$wci_currency_symbol = get_woocommerce_currency_symbol();

$get_total_money_spent = $wpdb->get_results( $wpdb->prepare( "select sum(total_price) as total_price from wci_successful_purchases where user_email=%s and order_status=%s" , $wci_user_email , 'Completed' ) );
$wci_total_money_spent = $get_total_money_spent[0]->total_price;


if( empty( $wci_total_money_spent ))
{
	$wci_total_money_spent = 0 .$wci_currency_symbol;
}
else
{
	$wci_total_money_spent = $wci_currency_symbol.$wci_total_money_spent;
}

//Sucessfil orders

$get_success_orders = $wpdb->get_results( $wpdb->prepare( "select count(*) as success_orders from wci_successful_purchases where user_name=%s and order_status=%s" , $target_person , 'Completed' ) );
$wci_success_orders = $get_success_orders[0]->success_orders;

//Total Time spent

if($wci_user_email == '--')
{
	$get_time_spent = $wpdb->get_results( $wpdb->prepare( "select SUM(spent_time) as spent_time from wci_activity where user_id=%s" , $target_person ) );

}
else
{
$get_time_spent = $wpdb->get_results( $wpdb->prepare( "select SUM(spent_time) as spent_time from wci_activity where user_id=%s and user_email=%s" , $target_person , $wci_user_email ) );
}
$wci_time_spent = $get_time_spent[0]->spent_time;

$hrs = floor($wci_time_spent / 3600);
$hours = sprintf("%02d", $hrs);
$mins = floor(($wci_time_spent / 60) % 60);
$minutes = sprintf("%02d", $mins);
$secs = $wci_time_spent % 60;
$seconds = sprintf("%02d", $secs);

$time_spt = $hours.":".$minutes.":".$seconds;

//END GET ALL DATA
	//USER PROFILE DESIGN

	$profile_stats = '';
	$profile_stats .= "<div style='width:70%;height:100%;background-color: #FFFFFF;border-radius:3px;margin-left:9%;border:1px solid #D3D3D3;' id='profile'>";//PROFILE

        $profile_stats .= "<br><div id ='one' style='width:100%;height:25%;'>";
        $profile_stats .= "<div id='avatar'><canvas id='user-icon' width='95' height='95' style='border-radius:50%;'></canvas> </div> <div id='stat_user_name' style='font-weight:bold;'> ".ucwords($target_person)." <br><br>  ". $wci_location."</div>";
	?>
	<?php

	$profile_stats .= "<div id='last_login' style='color:#00a699;font-size:12px;width:100%;float:left;margin-left:80px;'> Last login :".$wci_last_login." </div>";
        $profile_stats .= "</div>";
        //EMAIL
        $profile_stats .= "<div id='wci_user_email'> 
        <div id='wci_sidebar'>
 </div>
<i style='padding-left:4%'class='fa fa-envelope' aria-hidden='true'></i>

<span class='wci_user_font' style='padding-left:5%;'> " .$wci_user_email. " </span>
        
        </div>";

// TIME SPENT
        $profile_stats .= "<div id='wci_time_spent'><i style='padding-left:5%;' class='fa fa-clock-o' aria-hidden='true'></i><span class='wci_user_font' style='padding-left:5%;'>

 Overall Time Spent : ".$time_spt." </span></div>";

//Page Visit
        $profile_stats .= "<div id='wci_page_visit'> <i style='padding-left:5%;' class='fa fa-money' aria-hidden='true'></i>
<span class='wci_user_font' style='padding-left:5%;'>Overall Money Spent : ". $wci_total_money_spent." </span> </div>";

// Total Orders 
        $profile_stats .= "<div id='wci_total_orders'><i style='padding-left:5%;' class='fa fa-shopping-cart' aria-hidden='true'></i>
 <span class='wci_user_font' style='padding-left:5%;'>Total Orders : ". $wci_order_count."</span> </div>";

	$profile_stats .= "<div id='wci_successful_orders'><i style='padding-left:5%;' class='fa fa-thumbs-o-up' aria-hidden='true'></i>
<span class='wci_user_font' style='padding-left:5%;'> Successful Orders : ".$wci_success_orders." </span></div>";

        $profile_stats .= "</div>";//END PROFILE

	//END USER PROFILE
		$response['chart'] = $pie_Array;
		$response['table'] = $html;
		$response['stats'] = $profile_stats;
		$response['target_person'] = ucwords($target_person);
	}
		print_r(json_encode($response)); die;
}
}
