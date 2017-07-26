<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

include_once(SM_LB_PRO_DIR.'lib/FreshsalesAnalytics.php');

class PROFunctions {

	public $domain = null;

	public $auth_token = null;

	public $username = null;

	public $password = null;

	public $result_emails;

	public $result_ids;

	public $result_products;

	public function __construct() {
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$get_freshsales_settings_info = get_option("wp_{$activateplugin}_settings");
		$this->auth_token = $get_freshsales_settings_info['auth_token'];
		$this->domain = $get_freshsales_settings_info['domain_url'];
		$this->username = $get_freshsales_settings_info['username'];
		$this->password = $get_freshsales_settings_info['password'];
	}

	public function testLogin( $domain_url , $login, $password )
	{
		$domain_url = $domain_url . '/api/sign_in';
		$process = curl_init($domain_url);
		curl_setopt($process, CURLOPT_POST, true);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($process, CURLOPT_USERPWD, "$login:$password");
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
		$login = curl_exec($process);
		return $login;
	}

	public function getCrmFields($module) {
		#$this->getUsersList();
		#Fetch all fields based on the module
		$url = $this->domain . '/api/settings/' . strtolower($module) . '/fields';
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";
		curl_setopt_array($ch, array(
			CURLOPT_HTTPGET        => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$fieldsArray = json_decode($response);
		$config_fields = array();
		if(!empty($fieldsArray)) {
			$i = 0;
			foreach ( $fieldsArray->fields as $item => $fieldInfo ) {
				if($fieldInfo->required == 1) {
					$config_fields['fields'][$i]['wp_mandatory'] = 1;
					$config_fields['fields'][$i]['mandatory'] = 2;
				} else {
					$config_fields['fields'][$i]['wp_mandatory'] = 0;
					$config_fields['fields'][$i]['mandatory'] = 0;
				}
				if($fieldInfo->type == 'dropdown') {
					$optionindex = 0;
					$picklistValues = array();
					foreach($fieldInfo->choices as $option)
					{
						$picklistValues[$optionindex]['id'] = $option->id;
						$picklistValues[$optionindex]['label'] = $option->value;
						$picklistValues[$optionindex]['value'] = $option->value;
						$optionindex++;
					}
					$config_fields['fields'][$i]['type'] = Array ( 'name' => 'picklist', 'picklistValues' => $picklistValues );
				} elseif($fieldInfo->type == 'checkbox') {
					$config_fields['fields'][$i]['type'] = array("name" => 'boolean');
				} elseif($fieldInfo->type == 'number') {
					$config_fields['fields'][$i]['type'] = array("name" => 'integer');
				} else {
					$config_fields['fields'][$i]['type'] = array("name" => $fieldInfo->type);
				}
				if($fieldInfo->base_model == 'LeadCompany') {
					$field_name = 'company_' . $fieldInfo->name;
				} elseif($fieldInfo->base_model == 'LeadDeal') {
					$field_name = 'deal_' . $fieldInfo->name;
				} else {
					$field_name = $fieldInfo->name;
				}
				$config_fields['fields'][$i]['name'] = str_replace(" " , "_", $field_name);
				$config_fields['fields'][$i]['fieldname'] = $field_name;
				$config_fields['fields'][$i]['label'] = $fieldInfo->label;
				$config_fields['fields'][$i]['display_label'] = $fieldInfo->label;
				$config_fields['fields'][$i]['publish'] = 1;
				$config_fields['fields'][$i]['order'] = $fieldInfo->position;
				$config_fields['fields'][$i]['base_model'] = $fieldInfo->base_model;
				$i++;
			}
			$config_fields['check_duplicate'] = 0;
			$config_fields['isWidget'] = 0;
			$users_list = $this->getUsersList();
			$config_fields['assignedto'] = $users_list['id'][0];
			$config_fields['module'] = $module;
			return $config_fields;
		}
	}

	public function getUsersList($module = 'users') {
		$url = $this->domain . '/settings/' . strtolower($module);
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";
		curl_setopt_array($ch, array(
			CURLOPT_HTTPGET        => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$userInfo = json_decode($response);
		$user_details = array();
		foreach($userInfo->users as $data) {
			$user_details['user_name'][] = $data->email;
			$user_details['id'][] = $data->id;
			$user_details['first_name'][] = '';
			$user_details['last_name'][] = $data->display_name;
		}
		return $user_details;
	}

	public function getUsersListHtml( $shortcode = "" )
	{
		$HelperObj = new WPCapture_includes_helper_PRO();
		$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
		$formObj = new CaptureData();
		if(isset($shortcode) && ( $shortcode != "" ))
		{
			$config_fields = $formObj->getFormSettings( $shortcode );  // Get form settings
		}
		$users_list = get_option('crm_users');
		$users_list = $users_list[$activatedplugin];
		$html = "";
		$html = '<select name="assignedto" class="selectpicker form-control" id="assignedto">';
		$content_option = "";
		if(isset($users_list['user_name']))
			for($i = 0; $i < count($users_list['user_name']) ; $i++)
			{
				$content_option.="<option id='{$users_list['id'][$i]}' value='{$users_list['id'][$i]}'";
				if($users_list['id'][$i] == $config_fields->assigned_to)
				{
					$content_option.=" selected";
				}
				$content_option.=">{$users_list['first_name'][$i]} {$users_list['last_name'][$i]}</option>";
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

	public function duplicateCheckEmailField()
	{
		return "email";
	}

	public function assignedToFieldId()
	{
		return "owner_id";
	}

	public function checkEmailPresent( $module , $email )
	{
		$module = strtolower($module);
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$result_emails = array();
		$result_ids = array();
		if($module == 'leads') {
			$search_filter = 'filtered_search/lead';
			$postArray = array(
				'filter_rule' => json_encode(array(
						0 => array(
							'attribute' => 'lead_email.email',
							'operator'  => 'is_in',
							'value'     => $email,
						)
					)
				));
		} else if($module == 'contacts') {
			$search_filter = 'filtered_search/contact';
			$postArray = array(
				'filter_rule' => json_encode(array(
						0 => array(
							'attribute' => 'contact_email.email',
							'operator'  => 'is_in',
							'value'     => $email,
						)
					)
				));
		}
		$url = $this->domain . '/api/' . $search_filter;
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";

		curl_setopt_array($ch, array(
			CURLOPT_POST           => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POSTFIELDS     => $postArray,
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$records = json_decode($response);
		$email_present = "no";
		if( $records->meta->total >= 0 ) {
			$result_lastnames[] = isset($records->{$module}[0]) ? $records->{$module}[0]->display_name : ""; //"Last Name";
			$result_emails[] = isset($records->{$module}[0]) ? $records->{$module}[0]->email : "";
			$result_ids[] = isset($records->{$module}[0]) ? $records->{$module}[0]->id : "";
			if(isset($records->{$module}[0])) {
			if($email == $records->{$module}[0]->email)
				$email_present = "yes";}
		}
		$this->result_emails = $result_emails;
		$this->result_ids = $result_ids;
		if($email_present == 'yes')
			return true;
		else
			return false;
	}

	public function mapUserCaptureFields( $user_firstname , $user_lastname , $user_email )
	{
		$post = array();
		$post['first_name'] = $user_firstname;
		$post['last_name'] = $user_lastname;
		$post[$this->duplicateCheckEmailField()] = $user_email;
		return $post;
	}

	public function createRecordOnUserCapture( $module , $module_fields )
	{
		return $this->createRecord( $module , $module_fields );
	}

	public function createRecord($module, $lead_info )
	{
		$module = strtolower($module);
		$url = $this->domain . '/api/' . $module;
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";
		if($module == 'leads') {
			$index = 'lead';
		} elseif ($module == 'contacts') {
			$index = 'contact';
		}
		$data_array = array();
		foreach($lead_info as $key => $val) {
			if(strpos($key, 'company_') !== false) {
				$key = str_replace('company_', '', $key);
				$data_array[$index]['company'][$key] = $val;
			} elseif(strpos($key, 'deal_') !== false) {
				if($key === 'deal_deal_product_id') {
					$key = 'deal_product_id';
				} else {
					$key = str_replace( 'deal_', '', $key );
				}
				$data_array[$index]['deal'][$key] = $val;
			} else {
				$data_array[$index][ $key ] = $val;
			}
		}
		$data_array = json_encode($data_array);
		curl_setopt_array($ch, array(
			CURLOPT_POST           => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POSTFIELDS     => $data_array,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			#throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$records = json_decode($response);
		if(isset($records->{$index}->id)) {
			$data['result'] = "success";
			$data['failure'] = 0;
		} else {
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response; #"failed adding entry";
		}
		return $data;
	}

	public function createEcomRecord($module, $lead_info , $order_id )
        {
		$module = strtolower($module);
		$url = $this->domain . '/api/' . $module;
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";

		if($module == 'leads') {
			$index = 'lead';
		} elseif ($module == 'contacts') {
			$index = 'contact';
		}
		$data_array = array();
		foreach($lead_info as $key => $val) {
			if(strpos($key, 'company_') !== false) {
				$key = str_replace('company_', '', $key);
				$data_array[$index]['company'][$key] = $val;
			} elseif(strpos($key, 'deal_') !== false) {
				if($key === 'deal_deal_product_id') {
					$key = 'deal_product_id';
				} else {
					$key = str_replace( 'deal_', '', $key );
				}
				$data_array[$index]['deal'][$key] = $val;
			} else {
				$data_array[$index][ $key ] = $val;
			}
		}
		$data_array = json_encode($data_array);
		curl_setopt_array($ch, array(
			CURLOPT_POST           => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POSTFIELDS     => $data_array,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			#throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$records = json_decode($response);
		if( $records->errors->code == '500' && $records->errors->message[0] == 'Contact with this email already exists' )
		{
			$this->checkEmailPresent( 'Contacts' , $lead_info['email'] );
			$contact_id = $this->result_ids[0];
			$records = $this->updateEmailPresentRecord( 'Contacts' , $contact_id , $data_array);
		}

                global $wpdb;
                if($records->{$index}->id)
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;

                        if( $module == "leads" )
                        {
                                $crm_id = $records->{$index}->id;
                                $my_leadid = $crm_id;
                                $crm_name = 'freshsales';
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
			if( $module == 'contacts' )
                        {
                                $crm_id = $records->{$index}->id;
                                $crm_name = 'freshsales';
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
		}
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed adding entry";
                }
                return $data;
        }


	public  function convertLead( $module , $crm_id , $order_id , $lead_no , $sales_order)
        {
		$module = strtolower($module);
                $url = $this->domain . "/api/leads/{$lead_no}/convert" ;
                $ch = curl_init($url);
                $auth_string = "$this->username:$this->password";
		
		$lead_details = $this->fetchLead( $lead_no );
		$last_name = $lead_details->lead->last_name;
		$email = $lead_details->lead->email;
		$company_name = $lead_details->lead->company->name;
	$postArray = array( 
                'last_name' => $last_name,
                'email' => $email,
                'company' => array(
                                'name' => $company_name
                                ),
 ); 
		$post_content = json_encode( $postArray );
		$curl = curl_init( $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, $auth_string);
		curl_setopt($curl, CURLOPT_HTTPHEADER , array(
			       'Content-type: application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_content);
		$result = curl_exec($curl);
		$final_result = json_decode( $result );
		global $wpdb;
                $wpdb->update( 'wp_smack_ecom_info' , array('contact_no' => $final_result->contact->id) , array( 'order_id' => $order_id ) );
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

	public function fetchLead( $lead_no )
	{
                $url = $this->domain . '/api/leads/'.$lead_no;
                $ch = curl_init($url);
                $auth_string = "$this->username:$this->password";	
		$curl = curl_init( $url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, $auth_string);
		curl_setopt($curl, CURLOPT_HTTPHEADER , array(
			       'Content-type: application/json'));
		$result = curl_exec($curl);
		$final_result = json_decode( $result );	
		return $final_result;
	}
	
	public function updateRecord( $module , $lead_info , $ids_present )
	{
		$leadId = $this->result_ids;

		$module = strtolower($module);
		$url = $this->domain . '/api/' . $module . '/' . $leadId[0];
		$ch = curl_init($url);
		$auth_string = "$this->username:$this->password";
		if($module == 'leads') {
			$index = 'lead';
		} elseif ($module == 'contacts') {
			$index = 'contact';
		}
		$data_array = array();
		foreach($lead_info as $key => $val) {
			if(strpos($key, 'company_') !== false) {
				$key = str_replace('company_', '', $key);
				$data_array[$index]['company'][$key] = $val;
			} elseif(strpos($key, 'deal_') !== false) {
				if($key === 'deal_deal_product_id') {
					$key = 'deal_product_id';
				} else {
					$key = str_replace( 'deal_', '', $key );
				}
				$data_array[$index]['deal'][$key] = $val;
			} else {
				$data_array[$index][ $key ] = $val;
			}
		}

		$data_array = json_encode($data_array);
		curl_setopt_array($ch, array(
			CURLOPT_CUSTOMREQUEST  => "PUT",
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_USERPWD        => $auth_string,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POSTFIELDS     => $data_array,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		));
		$response  = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_status != 200){
			#throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
		}
		$records = json_decode($response);

		if($records->{$index}->id) {
			$data['result'] = "success";
			$data['failure'] = 0;
		} else {
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response; #"failed adding entry";
		}
		return $data;
	}

	public function updateEcomRecord( $module , $lead_info , $ids_present )
        {
                $leadId = $this->result_ids;

                $module = strtolower($module);
                $url = $this->domain . '/api/' . $module . '/' . $leadId[0];
                $ch = curl_init($url);
                $auth_string = "$this->username:$this->password";
                if($module == 'leads') {
                        $index = 'lead';
                } elseif ($module == 'contacts') {
                        $index = 'contact';
                }
                $data_array = array();
                foreach($lead_info as $key => $val) {
                        if(strpos($key, 'company_') !== false) {
                                $key = str_replace('company_', '', $key);
                                $data_array[$index]['company'][$key] = $val;
                        } elseif(strpos($key, 'deal_') !== false) {
                                if($key === 'deal_deal_product_id') {
                                        $key = 'deal_product_id';
                                } else {
                                        $key = str_replace( 'deal_', '', $key );
                                }
                                $data_array[$index]['deal'][$key] = $val;
                        } else {
                                $data_array[$index][ $key ] = $val;
                        }
                }

                $data_array = json_encode($data_array);
                curl_setopt_array($ch, array(
                        CURLOPT_CUSTOMREQUEST  => "PUT",
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_USERPWD        => $auth_string,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_POSTFIELDS     => $data_array,
                        CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
                ));
		$response  = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($http_status != 200){
                        #throw new Exception("Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response);
                }
                $records = json_decode($response);

                if($records->{$index}->id) {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                } else {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "Freshsales encountered an error. CODE: " . $http_status . " Response: " . $response; #"failed adding entry";
                }
                return $data;
        }
	
	public function updateEmailPresentRecord( $module , $contact_id , $contact_info)
	{
		$module = strtolower($module);
                $url = $this->domain . '/api/' . $module . '/' . $contact_id;
                $ch = curl_init($url);
                $auth_string = "$this->username:$this->password";
                if($module == 'leads') {
                        $index = 'lead';
                } elseif ($module == 'contacts') {
                        $index = 'contact';
                }
                $data_array = $contact_info;
                curl_setopt_array($ch, array(
                        CURLOPT_CUSTOMREQUEST  => "PUT",
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_USERPWD        => $auth_string,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_POSTFIELDS     => $data_array,
                        CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
                ));
                $response  = curl_exec($ch);
                curl_close($ch);
                $records = json_decode($response);
		return $records;
	}
}
