<?php

/******************************************************************************************
 * Copyright (C) Smackcoders 2016 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

include_once(SM_LB_PRO_DIR.'lib/SmackSalesForceApi.php');
class PROFunctions{
	public $consumerkey;
	public $consumersecret;
	public $callback;
	public $instanceurl;
 	public $accesstoken;
	public $result_emails;
	public $result_ids;
	public $result_products;
	public function __construct()
	{
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$SettingsConfig = get_option("wp_{$activateplugin}_settings");
                if(isset($_REQUEST['crmtype']))
                {
                        $SettingsConfig = get_option("wp_{$_REQUEST['crmtype']}_settings");
                }
                else
                {
                        $SettingsConfig = get_option("wp_{$activateplugin}_settings");
                }
		$this->consumerkey = $SettingsConfig['key'];
		$this->consumersecret = $SettingsConfig['secret'];
		$this->url = "";//$SettingsConfig['url'];
		$this->callback = $SettingsConfig['callback'];
		$this->instanceurl = $SettingsConfig['instance_url'];
 		$this->accesstoken = $SettingsConfig['access_token'];
	}

	public function getCrmFields( $module )
	{
	$module = $this->moduleMap( $module );
	$recordInfo = GetCrmModuleFields( $this->instanceurl, $this->accesstoken , $module );
	$config_fields = array();
		$AcceptedFields = Array( 'textarea' => 'text' , 'string' => 'string' , 'email' => 'email' , 'boolean' => 'boolean', 'picklist' => 'picklist' , 'varchar' => 'string' , 'url' => 'url' , 'phone' => 'phone' , 'multipicklist' => 'multipicklist',  'radioenum' => 'radioenum', 'currency' => 'currency' , 'date' => 'date' , 'datetime' => 'date' , 'int' => 'string' , 'double' => 'string');
                if($recordInfo)
                {
                        $j=0;
                        for($i=0;$i<count($recordInfo['fields']);$i++)
                        {
if(( $recordInfo['fields'][$i]['type'] != 'id' ) && ( $recordInfo['fields'][$i]['updateable'] == 1 ) && ( $recordInfo['fields'][$i]['type'] != 'reference' ) && ( $recordInfo['fields'][$i]['name'] != 'EmailBouncedReason' ) && ( $recordInfo['fields'][$i]['type'] != 'datetime' ) )
{
					$config_fields['fields'][$j]['name'] = $recordInfo['fields'][$i]['name'];
					$config_fields['fields'][$j]['label'] = $recordInfo['fields'][$i]['label'];
                                        $config_fields['fields'][$j]['order'] = $j;
                                        $config_fields['fields'][$j]['publish'] = 1;
                                        $config_fields['fields'][$j]['display_label'] = $recordInfo['fields'][$i]['label'];
                                       	if( ($recordInfo['fields'][$i]['nillable'] != 1 ) && ( $recordInfo['fields'][$i]['type'] != 'boolean' ))
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 1;
                                                $config_fields['fields'][$j]['mandatory'] = 2;
                                        }
                                        else
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 0;
                                        }
					if($recordInfo['fields'][$i]['type'] == 'picklist' || $recordInfo['fields'][$i]['type'] == 'multipicklist' )
					{
						foreach( $recordInfo['fields'][$i]['picklistValues'] as $picklistkey => $picklistvalue )
						{
							$config_fields['fields'][$j]['type']['picklistValues'][$picklistkey] = $picklistvalue;
						}
						$config_fields['fields'][$j]['type']['defaultValue'] = "";
						$config_fields['fields'][$j]['type']['name'] = $AcceptedFields[$recordInfo['fields'][$i]['type']];
					}
					else
					{
						$config_fields['fields'][$j]['type']['name'] = $AcceptedFields[$recordInfo['fields'][$i]['type']];
					}

                                       $j++;
}
                        }
                        $config_fields['check_duplicate'] = 0;
                        $config_fields['isWidget'] = 0;
                        $config_fields['update_record'] = 0;
                        $users_list = $this->getUsersList();
                        $config_fields['assignedto'] = $users_list['id'][0];
                        $config_fields['module'] = $module;
			return $config_fields;
                }
	}
	public function getUsersList()
	{
		$records = Getuser( $this->instanceurl, $this->accesstoken );
		foreach($records['recentItems'] as $record) {
	                $user_details['user_name'][] = $record['Name'] ;
			$Name = explode(" ",$record['Name']);
			$user_details['first_name'][]= $Name[0];
			$user_details['last_name'][] = $Name[1];
			$user_details['id'][] = $record['Id'];
		}
           return $user_details;
	}
	
	public function getUsersListHtml( $shortcode = "" )
	{
		$HelperObj = new WPCapture_includes_helper_PRO();
		$module = $this->moduleMap( $HelperObj->Module );
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
                if($shortcode != "")
                {
                        $option = "smack_fields_shortcodes";
                        $edit_config_fields = get_option($option);
                        $config_fields = $edit_config_fields[$shortcode];
                }
                else
                {
                        $option = "smack_{$activatedplugin}_{$moduleslug}_fields-tmp";
                        $config_fields = get_option($option);
                }
                $users_list = get_option('crm_users');
                $users_list = $users_list[$activatedplugin];
		$html = "";
		$html = '<select class="selectpicker form-control" name="assignedto" id="assignedto">';
                $content_option = "";
                if(isset($users_list['user_name']))
                for($i = 0; $i < count($users_list['user_name']) ; $i++)
                {
			$content_option.="<option id='{$users_list['user_name'][$i]}' value='{$users_list['id'][$i]}'";
			if(isset($users_list['user_name'][$i]) &&( $users_list['id'][$i]== $config_fields->assigned_to))
			{
				$content_option.=" selected";
			}
			$content_option.=">{$users_list['user_name'][$i]}</option>";
		}
		$content_option .= "<option id='owner_rr' value='Round Robin'";
                if( $config_fields->assigned_to == 'Round Robin' )
                {
                        $content_option .= "selected";
                }
                $content_option .= "> Round Robin </option>";

		$html .= $content_option;
		$html .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		return $html;
	}

        public function getAssignedToList()
        {
                $users_list = $this->getUsersList();
                for($i = 0; $i < count($users_list['user_name']) ; $i++)
                {
                        $user_list_array[$users_list['id'][$i]] = $users_list['user_name'][$i];
                }
                return $user_list_array;
        }
	
	public function mapUserCaptureFields( $user_firstname , $user_lastname , $user_email )
	{
		$post = array();
		$post['FirstName'] = $user_firstname;
		$post['LastName'] = $user_lastname;
		$post[$this->duplicateCheckEmailField()] = $user_email;
		return $post;
	}

        public function assignedToFieldId()
        {
                return "OwnerId";
        }

	public function createRecordOnUserCapture( $module , $module_fields )
	{
		$module = $this->moduleMap( $module );

                $record = create_record( $module_fields , $this->instanceurl, $this->accesstoken , "Contact" );

		if( isset($record['result']['message']) && ( $record['result']['message'] == "Record(s) added successfully" ) )
		{
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		else
		{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed adding entry";
		}
		return $data;
	}


	public function createRecord( $module , $module_fields )
	{	
	$module = $this->moduleMap( $module );
		global $HelperObj;
                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$moduleslug = $this->ModuleSlug = rtrim( strtolower($module) , "s");
                $record = create_record( $module_fields , $this->instanceurl, $this->accesstoken , $module );
		if( isset($record['id']))
		{
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		else
		{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed adding entry";
		}
		return $data;
	}
	//Ecom integraion
	public function createEcomRecord($module, $module_fields , $order_id)	
	{
		if( $module == 'Leads' || $module == 'Contacts' )
                {
			$module = $this->moduleMap( $module );
			$record = create_record( $module_fields , $this->instanceurl, $this->accesstoken , $module );
                }
		
		if( $module == 'Products' )
		{
			$module = 'Product2';
			$record = create_record( $module_fields , $this->instanceurl, $this->accesstoken , $module );
		}
		global $wpdb;
                if($record['success'] == 1 )
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;

                        if( $module == "Lead" )
                        {
                                $crm_id = $record['id'];
                                $my_leadid = $crm_id;
                                $crm_name = 'wpsalesforcepro';
                                if( is_user_logged_in() )
                                {
                                        $user_id = get_current_user_id();
                                        $is_user = 1;
                                }else
                                {
                                        $user_id = 'guest';
                                        $is_user = 0;
                                }
                                $lead_no = $crm_id;
                                $wpdb->insert( 'wp_smack_ecom_info' , array( 'crmid' => $crm_id , 'crm_name' => $crm_name , 'wp_user_id' => $user_id , 'is_user' => $is_user , 'lead_no' => $my_leadid , 'order_id' => $order_id ) );
                        }
                        if( $module == 'Contact' )
                        {
                                $crm_id = $record['id'];
                                $crm_name = 'wpsalesforcepro';
                                $my_contactid = $crm_id;
                                if( is_user_logged_in() )
                                {
                                        $user_id = get_current_user_id();
                                        $is_user = 1;
                                }else
                                {
                                        $user_id = '';
                                        $is_user = 0;
                                }
                                $contact_no = $crm_id;
                                $wpdb->insert( 'wp_smack_ecom_info' , array( 'crmid' => $crm_id , 'crm_name' => $crm_name , 'wp_user_id' => $user_id , 'is_user' => $is_user , 'contact_no' => $my_contactid , 'order_id' => $order_id ) );

                        }
			if( $module == 'Product2' )
                        {
                                $crm_id = $record['id'];
                                $crm_name = 'wpsalesforcepro';
                                $get_product = $wpdb->get_results( $wpdb->prepare( "select product_id from wp_smack_ecom_info where order_id=$order_id" ) );
                                $prod_id = $get_product[0]->product_id;
                                if( !empty( $prod_id ) )
                                {
                                        $crm_id = $prod_id.",".$crm_id;
                                }
                                $wpdb->update( 'wp_smack_ecom_info' , array( 'product_id' => $crm_id ) , array( 'order_id' => $order_id ));             
                        }
			else
			{
				$data['result'] = "failure";
				$data['failure'] = 1;
				$data['reason'] = "failed adding entry";
			}
			return $data;

		}
	}

	public function checkProductPresent( $module , $item_name )
        {
		$records = check_product_present($this->instanceurl, $this->accesstoken , $module = "Product2" );
		
		if( isset( $records['records'] ) && is_array($records['records']))
                {
                        foreach( $records['records'] as $key => $record )
                        {
				$result_products[] = $record['Name'];
				$result_ids[] = $record['Id'];
				if($item_name == $record['Name'])
				{
					$code = $record['Id'];
					$product_present = "yes";
				}

                        }
                }
                
		$this->result_products = $result_products;
                $this->result_ids = $result_ids;
                if($product_present == 'yes')
                        return true;
                else
                        return false;

	}

	public function updateEcomRecord( $module , $module_fields , $ids_present, $order_id )
        {
                global $wpdb;
		
		$module = 'Product2';
		$record = update_record( $module_fields , $this->instanceurl, $this->accesstoken , $ids_present , $module );
                if( $module == 'Product2' )
                        {
                                $crm_id = $record['id'];
                                $crm_name = 'wpsalesforcepro';
                                $get_product = $wpdb->get_results( $wpdb->prepare( "select product_id from wp_smack_ecom_info where order_id=$order_id" ) );
                                $prod_id = $get_product[0]->product_id;
                                if( !empty( $prod_id ) )
                                {
                                        $crm_id = $prod_id.",".$crm_id;
                                }
                                $wpdb->update( 'wp_smack_ecom_info' , array( 'product_id' => $crm_id ) , array( 'order_id' => $order_id ));
                        }

                if($record)
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                }
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed updating entry";
                }

                return $data;
        }

	//ConvertLead Method 
	public  function convertLead( $module , $crm_id , $order_id , $lead_no, $sales_order)
        {
                global $wpdb;
		//Convert Lead Method
		$selected_module = getRecordById($this->instanceurl, $this->accesstoken , $module = "Lead" , $lead_no );
		//Create Convert Lead mapping array
		$update_client = $selected_module;
                $field_replace_array = array('Street' => 'MailingStreet' , 'City' => 'MailingCity' , 'State' => 'MailingState' ,'Country' => 'MailingCountry' , 'PostalCode' => 'MailingPostalCode' );
		$extra_fields = array('FirstName','LastName','Salutation','Title','Email','Phone','MobilePhone');
		$selected_module = array();
                if( !empty( $update_client ))
                {
                foreach($update_client as $key => $value){
                        foreach( $field_replace_array as $rep_key => $rep_val )
                        {
                                if( $rep_key == $key )
                                {
                                        $selected_module[$rep_val] = $value;
                                }
                                else
                                {
					if(in_array($key , $extra_fields))
					{
                                        	$selected_module[$key] = $value;
					}
                                }
                        }
                }
                }
		$get_contact_id = create_record( $selected_module , $this->instanceurl, $this->accesstoken , $module = 'Contact' );
		$cont_no = $get_contact_id['id'];
		//Remove Lead
		$delete_reponse = remove_converted_lead($this->instanceurl, $this->accesstoken , $module = 'Lead' , $lead_no );
		//Get Account Id
		$module = 'Account';
		$get_acc_id = GetAccountId( $this->instanceurl, $this->accesstoken , $module = "Account" , $extraparams = array() );
		$acc_id = $get_acc_id['records'][0]['Id'];

		//Create Contract 
		$contract_array = array();
		$contract_array['AccountId'] = $acc_id;
		$contract_array['Status'] = 'Draft';
		$contract_array['StartDate'] = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
		$contract_array['BillingStreet'] = $sales_order['BillingStreet'];
		$contract_array['BillingCity'] = $sales_order['BillingCity'];
		$contract_array['BillingState'] = $sales_order['BillingState'];
		$contract_array['BillingPostalCode'] = $sales_order['BillingPostalCode'];
		$contract_array['BillingCountry'] = $sales_order['BillingCountry'];
		$contract_array['ContractTerm'] = '1';
		$get_contract_id = create_record( $contract_array, $this->instanceurl, $this->accesstoken , $module = "Contract" );
		
		$contract_id = $get_contract_id['id'];
		$sales_order['ContractId'] = $contract_id;
		$sales_order['AccountId'] = $acc_id;
		$get_sales_order_id = create_record( $sales_order , $this->instanceurl, $this->accesstoken , $module = "Order" );
		$sales_orderid = $get_sales_order_id['id'];
		$wpdb->update( 'wp_smack_ecom_info' , array('contact_no' => $cont_no) , array( 'order_id' => $order_id ) );
                $wpdb->update( 'wp_smack_ecom_info' , array('sales_orderid' => $sales_orderid) , array( 'order_id' => $order_id ) );
                if($sales_orderid)
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                }
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed updating entry";
                }
                return $data;

	}

	public function create_sales_order( $module , $order_id , $contact_id , $sales_order)
        {
                global $wpdb;
		//Get Account Id
                $module = 'Account';
                $get_acc_id = GetAccountId( $this->instanceurl, $this->accesstoken , $module = "Account" , $extraparams = array() );
                $acc_id = $get_acc_id['records'][0]['Id'];
                //Create Contract 
                $contract_array = array();
                $contract_array['AccountId'] = $acc_id;
                $contract_array['Status'] = 'Draft'; 
                $contract_array['StartDate'] = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
                $contract_array['BillingStreet'] = $sales_order['BillingStreet'];
                $contract_array['BillingCity'] = $sales_order['BillingCity'];
                $contract_array['BillingState'] = $sales_order['BillingState'];
                $contract_array['BillingPostalCode'] = $sales_order['BillingPostalCode'];
                $contract_array['BillingCountry'] = $sales_order['BillingCountry'];
                $contract_array['ContractTerm'] = '1';
                $get_contract_id = create_record( $contract_array, $this->instanceurl, $this->accesstoken , $module = "Contract" );
                $contract_id = $get_contract_id['id'];
                $sales_order['ContractId'] = $contract_id;
                $sales_order['AccountId'] = $acc_id;
                $get_sales_order_id = create_record( $sales_order , $this->instanceurl, $this->accesstoken , $module = "Order" );
                $sales_orderid = $get_sales_order_id['id'];
                $wpdb->update( 'wp_smack_ecom_info' , array('sales_orderid' => $sales_orderid) , array( 'order_id' => $order_id ) );
                if($sales_orderid)
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                }
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed updating entry";
                }
                return $data;
	}

	public function updateRecord( $module , $module_fields , $ids_present )
	{
		$module = $this->moduleMap( $module );
		global $HelperObj;
                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$moduleslug = $this->ModuleSlug = rtrim( strtolower($module) , "s");
                $record = update_record( $module_fields , $this->instanceurl, $this->accesstoken , $ids_present , $module );
                if( isset($record['id'] ) )
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                }
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed adding entry";
                }
                return $data;
	}

	public function checkEmailPresent( $module , $email )
	{
		$module = $this->moduleMap( $module );
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$result_emails = array();
		$result_ids = array();
		$records = GetRecord( $this->instanceurl , $this->accesstoken , $module , array( "Email" => $email ) );
		if( isset( $records['records'] ) && is_array($records['records']))
		{
			foreach( $records['records'] as $key => $record )
			{
				$result_lastnames[] = "Last Name";
				$result_emails[] = $email; 
				$result_ids[] = $record['Id'];
				$email_present = "yes";
			}
		}
		$this->result_emails = $result_emails;
		$this->result_ids = $result_ids;
		if($email_present == 'yes')
			return true;
		else
			return false;
	}

	public function duplicateCheckEmailField()
	{
		return "Email";
	}

	public function moduleMap( $module )
	{
		$modules_Map = array( "Lead" => "Lead" , "Leads" => "Lead" , "Contact" => "Contact" , "Contacts" => "Contact" );
		return $modules_Map[$module];
	}
}
