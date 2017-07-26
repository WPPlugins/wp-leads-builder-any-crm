<?php

/******************************************************************************************
 * Copyright (C) Smackcoders 2016 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

class SaveCRMConfig
{
    public function CheckCRMType( $config )
    {
        $Activated_crm = $config['action'];
        switch( $Activated_crm )
        {
		case 'wptigerproSettings':
			$save_result = $this->tigerproSettings($config);
			return $save_result;
			break;
		case 'wpsugarproSettings':
			$save_result = $this->sugarproSettings($config);
			return $save_result;
			break;
		case 'wpsuiteproSettings':
			$save_result = $this->suiteproSettings($config);
			return $save_result;
			break;
		case 'wpzohoproSettings':
			$save_result = $this->zohoproSettings($config);
			return $save_result;
			break;
		case 'wpzohoplusproSettings':
			$save_result = $this->zohoplusproSettings($config);
			return $save_result;
			break;
		case 'wpsalesforceproSettings':
			$save_result = $this->salesforceproSettings($config);
			return $save_result;
			break;
		case 'freshsalesSettings':
			$save_result = $this->freshsalesSettings($config);
			return $save_result;
			break;
        }
    }

	public function freshsalesSettings( $configData ) {
		$freshsales_config_array = $configData['REQUEST'];
		$fieldNames = array(
			'username' => __('Freshsales Username' , SM_LB_URL ),
			'password' => __('Freshsales Password' , SM_LB_URL ),
			'domain_url' => __('Freshsales Domain URL' , SM_LB_URL ),
			'smack_email' => __('Smack Email' , SM_LB_URL ),
			'email' => __('Email id' , SM_LB_URL ),
			'emailcondition' => __('Emailcondition' , SM_LB_URL ),
			'debugmode' => __('Debug Mode' , SM_LB_URL ),
		);

		foreach ($fieldNames as $field=>$value){
			if(isset($freshsales_config_array[$field]))
			{
				$config[$field] = trim($freshsales_config_array[$field]);
			}
		}
		require_once(SM_LB_PRO_DIR . "includes/freshsalesFunctions.php");
		$FunctionsObj = new PROFunctions();
		$testlogin_result = $FunctionsObj->testLogin( $freshsales_config_array['domain_url'] ,$freshsales_config_array['username'], $freshsales_config_array['password'] );
		$check_is_valid_login = json_decode($testlogin_result);
		if(isset($check_is_valid_login->login) && $check_is_valid_login->login == 'success') {
			$successresult = "Settings Saved ";
			$result['error'] = 0;
			$result['success'] = $successresult;
			$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
			$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
			$config['auth_token'] = $check_is_valid_login->auth_token;
			update_option("wp_{$activateplugin}_settings", $config);
		}
		else
		{
			$freshsales_crm_config_error = "Please Verify your Freshsales Credentials";

			$result['error'] = 1;
			$result['errormsg'] = $freshsales_crm_config_error ;
			$result['success'] = 0;
		}
		return $result;
	}

    public function zohoproSettings( $zohoSettArray )
    {
        $zoho_config_array = $zohoSettArray['REQUEST'];
        $fieldNames = array(
            'username' => __('Zoho Username' , SM_LB_URL ),
            'password' => __('Zoho Password' , SM_LB_URL ),    
	    'TFA_check'      => __('Two Factor Authentication' , SM_LB_URL ),
            'smack_email' => __('Smack Email' , SM_LB_URL ),
            'email' => __('Email id' , SM_LB_URL ),
            'emailcondition' => __('Emailcondition' , SM_LB_URL ),
            'debugmode' => __('Debug Mode' , SM_LB_URL ),
                    );

        foreach ($fieldNames as $field=>$value){
            if(isset($zoho_config_array[$field]))
            {
                $config[$field] = $zoho_config_array[$field];
            }
        }
	require_once(SM_LB_PRO_DIR . "includes/wpzohoproFunctions.php");
        $FunctionsObj = new PROFunctions( );
        $jsonData = $FunctionsObj->getAuthenticationKey( $config['username'] , $config['password'] );
        if($jsonData['result'] == "TRUE")
        {
            $successresult = "Settings Saved ";
            $result['error'] = 0;
            $result['success'] = $successresult;
            $config['authtoken'] = $jsonData['authToken'];
            $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            update_option("wp_{$activateplugin}_settings", $config);
        }
	else if( $jsonData['result'] == "FALSE" && $jsonData['cause'] == 'WEB_LOGIN_REQUIRED'){
		$TFA_get_authtoken = get_option('TFA_zoho_authtoken' );
		$uri = "https://crm.zoho.com/crm/private/xml/Info/getModules?"; // Check Auth token present in Zoho //ONLY FOR TFA CHECK
           	$postContent = "scope=crmapi";
           	$postContent .= "&authtoken={$TFA_get_authtoken}";
           	$ch = curl_init($uri );
          	curl_setopt($ch, CURLOPT_POST, true);
           	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           	curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
           	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
           	$curl_result = curl_exec($ch);
           	$xml = simplexml_load_string($curl_result);
           	$json = json_encode($xml);
           	$result_array = json_decode($json,TRUE);
           	curl_close($ch);
		$TFA_result_array = $result_array['error'];
	   	if( $TFA_result_array['code'] = "4834" && $TFA_result_array['message'] == "Invalid Ticket Id" )
	   	{
			$successresult = "TFA is enabled in ZOHO CRM. Please Enter Valid Authtoken Below. <a target='_blank' href='https://crm.zoho.com/crm/ShowSetup.do?tab=developerSpace&subTab=api'>To Genrate Authtoken</a>";

		//https://crm.zoho.com/crm/ShowSetup.do?tab=webInteg&subTab=api

			$result['error'] = 11;
			$result['errormsg'] = $successresult;
	   	}
	   	else
	   	{

			$successresult = " Settings Saved";
		  	$result['error'] = 0;
            		$result['success'] = $successresult;
	   	}
            	$config['authtoken'] = get_option( "TFA_zoho_authtoken" );
            	$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            	$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            	update_option("wp_{$activateplugin}_settings", $config);
	   	}
        else
        {
            if($jsonData['cause'] == 'EXCEEDED_MAXIMUM_ALLOWED_AUTHTOKENS') {
                $zohocrmerror = "Please log in to <a target='_blank' href='https://accounts.zoho.com'>https://accounts.Zoho.com</a> - Click Active Authtoken - Remove unwanted Authtoken, so that you could generate new authtoken..";
            }
            else{
                $zohocrmerror = "Please Verify Username and Password.";
            }
            $result['error'] = 1;
            $result['errormsg'] = $zohocrmerror ;
            $result['success'] = 0;
        }
        return $result;
    }

    public function zohoplusproSettings( $zohoSettArray )
    {
        $zoho_config_array = $zohoSettArray['REQUEST'];
	$fieldNames = array(
            'username' => __('Zoho Plus Username' , SM_LB_URL ),
            'password' => __('Zoho Plus Password' , SM_LB_URL ),
	    'TFA_check'      => __('Two Factor Authentication' , SM_LB_URL ),
            'smack_email' => __('Smack Email' , SM_LB_URL ),
            'email' => __('Email id' , SM_LB_URL ),
            'emailcondition' => __('Emailcondition' , SM_LB_URL ),
            'debugmode' => __('Debug Mode' , SM_LB_URL ),
                    );

        foreach ($fieldNames as $field=>$value){
            if(isset($zoho_config_array[$field]))
            {
                $config[$field] = $zoho_config_array[$field];
            }
        }
	require_once(SM_LB_PRO_DIR . "includes/wpzohoproFunctions.php");	
        $FunctionsObj = new PROFunctions( );
        $jsonData = $FunctionsObj->getAuthenticationKey( $config['username'] , $config['password'] );
        if($jsonData['result'] == "TRUE")
        {
            $successresult = " Settings Saved ";
            $result['error'] = 0;
            $result['success'] = $successresult;
            $config['authtoken'] = $jsonData['authToken'];
            $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            update_option("wp_{$activateplugin}_settings", $config);
        }
	else if( $jsonData['result'] == "FALSE" && $jsonData['cause'] == 'WEB_LOGIN_REQUIRED'){
		$TFA_get_authtoken = get_option('TFA_zoho_plus_authtoken' );
		$uri = "https://crm.zoho.com/crm/private/xml/Info/getModules?"; // Check Auth token present in Zoho //ONLY FOR TFA CHECK
           	$postContent = "scope=crmapi";
           	$postContent .= "&authtoken={$TFA_get_authtoken}";
           	$ch = curl_init($uri );
          	curl_setopt($ch, CURLOPT_POST, true);
           	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           	curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
           	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
           	$curl_result = curl_exec($ch);
           	$xml = simplexml_load_string($curl_result);
           	$json = json_encode($xml);
           	$result_array = json_decode($json,TRUE);
           	curl_close($ch);
		$TFA_result_array = $result_array['error'];
	   	if( $TFA_result_array['code'] = "4834" && $TFA_result_array['message'] == "Invalid Ticket Id" )
	   	{
			$successresult = "TFA is enabled in ZOHO CRM Plus. Please Enter Valid Authtoken Below. <a target='_blank' href='https://crm.zoho.com/crm/ShowSetup.do?tab=webInteg&subTab=api'>To Genrate Authtoken</a>";

			$result['error'] = 11;
			$result['errormsg'] = $successresult;
	   	}
	   	else
	   	{

			$successresult = " Settings Saved ";
		  	$result['error'] = 0;
            		$result['success'] = $successresult;
	   	}
            	$config['authtoken'] = get_option( "TFA_zoho_plus_authtoken" );
            	$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            	$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            	update_option("wp_{$activateplugin}_settings", $config);
	   	}
        else
        {
            if($jsonData['cause'] == 'EXCEEDED_MAXIMUM_ALLOWED_AUTHTOKENS') {
                $zohocrmerror = "Please log in to <a target='_blank' href='https://accounts.zoho.com'>https://accounts.Zoho.com</a> - Click Active Authtoken - Remove unwanted Authtoken, so that you could generate new authtoken..";
            }
            else{
                $zohocrmerror = "Please Verify Username and Password.";
            }
            $result['error'] = 1;
            $result['errormsg'] = $zohocrmerror ;
            $result['success'] = 0;
        }
        return $result;
    }
	

    public function tigerproSettings( $tigerSettArray )
    {
        $tiger_config_array = $tigerSettArray['REQUEST'];
        $fieldNames = array(

            'url' => __('Vtiger Url' , SM_LB_URL ),
            'username' => __('Vtiger User Name' , SM_LB_URL ),
            'accesskey' => __('Vtiger Access Key' , SM_LB_URL ),
            'smack_email' => __('Smack Email' , SM_LB_URL ),
            'email' => __('Email id' , SM_LB_URL ),
            'emailcondition' => __('Emailcondition' , SM_LB_URL ),
            'debugmode' => __('Debug Mode' , SM_LB_URL ),
        );

        foreach ($fieldNames as $field=>$value){
            if(isset($tiger_config_array[$field]))
            {
                $config[$field] = trim($tiger_config_array[$field]);
            }
        }
	require_once(SM_LB_PRO_DIR . "includes/wptigerproFunctions.php");
        $FunctionsObj = new PROFunctions();
        $testlogin_result = $FunctionsObj->testLogin( $tiger_config_array['url'] , $tiger_config_array['username'] , $tiger_config_array['accesskey'] );
        if($testlogin_result == 1)
        {
            $successresult = "Settings Saved";
            $result['error'] = 0;
            $result['success'] = $successresult;
            $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            update_option("wp_{$activateplugin}_settings", $config);
        }
        else
        {
            $vtigercrmerror = "Please Verify your Vtiger Credentials.";

            $result['error'] = 1;
            $result['errormsg'] = $vtigercrmerror ;
            $result['success'] = 0;
        }
        return $result;
    }

    public function sugarproSettings( $sugarSettArray )
    {
        $sugar_config_array = $sugarSettArray['REQUEST'];
        $fieldNames = array(
            'url' => __('Sugar Host Address', SM_LB_URL ),
            'username' => __('Sugar Username' , SM_LB_URL ),
            'password' => __('Sugar Password' , SM_LB_URL ),
            'smack_email' => __('Smack Email'),
            'email' => __('Email id'),
            'emailcondition' => __('Emailcondition'),
            'debugmode' => __('Debug Mode'),
                    );

        foreach ($fieldNames as $field=>$value){
            if(isset($sugar_config_array[$field]))
            {
                $config[$field] = $sugar_config_array[$field];
            }
        }
	require_once(SM_LB_PRO_DIR . "includes/wpsugarproFunctions.php");
        $FunctionsObj = new PROFunctions( );
        $testlogin_result = $FunctionsObj->testlogin( $config['url'] , $config['username'] , $config['password'] );
        if(isset($testlogin_result['login']) && ($testlogin_result['login']['id'] != -1) && is_array($testlogin_result['login']))
        {
            $successresult = "Settings Saved";
            $result['error'] = 0;
            $result['success'] = $successresult;
            $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            update_option("wp_{$activateplugin}_settings", $config);
        }
        else
        {
            $sugarcrmerror = "Please Verify Your Sugar CRM credentials";
            $result['error'] = 1;
            $result['errormsg'] = $sugarcrmerror ;
            $result['success'] = 0;
        }
        return $result;
        $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
        $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
        update_option("wp_{$activateplugin}_settings", $config);
    }

    public function suiteproSettings( $sugarSettArray )
    {
        $sugar_config_array = $sugarSettArray['REQUEST'];
        $fieldNames = array(
            'url' => __('Suite Host Address', SM_LB_URL ),
            'username' => __('Suite Username' , SM_LB_URL ),
            'password' => __('Suite Password' , SM_LB_URL ),
            'smack_email' => __('Smack Email'),
            'email' => __('Email id'),
            'emailcondition' => __('Emailcondition'),
            'debugmode' => __('Debug Mode'),
                    );

        foreach ($fieldNames as $field=>$value){
            if(isset($sugar_config_array[$field]))
            {
                $config[$field] = $sugar_config_array[$field];
            }
        }
	require_once(SM_LB_PRO_DIR . "includes/wpsuiteproFunctions.php");
        $FunctionsObj = new PROFunctions( );
        $testlogin_result = $FunctionsObj->testlogin( $config['url'] , $config['username'] , $config['password'] );
        if(isset($testlogin_result['login']) && ($testlogin_result['login']['id'] != -1) && is_array($testlogin_result['login']))
        {
            $successresult = "Settings Saved";
            $result['error'] = 0;
            $result['success'] = $successresult;
            $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
            $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
            update_option("wp_{$activateplugin}_settings", $config);
        }
        else
        {
            $sugarcrmerror = "Please Verify Your Suite CRM credentials";
            $result['error'] = 1;
            $result['errormsg'] = $sugarcrmerror ;
            $result['success'] = 0;
        }
        return $result;
        $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
        $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
        update_option("wp_{$activateplugin}_settings", $config);
    }
	
    public function salesforceproSettings( $salesSettArray  )
    {
	$sales_config_array = $salesSettArray['REQUEST'];
        $fieldNames = array(
            'key' => __('Consumer Key' , SM_LB_URL ),
            'secret' => __('Consumer Secret' , SM_LB_URL ),
            'callback' => __('Callback URL' , SM_LB_URL ),
            'smack_email' => __('Smack Email' , SM_LB_URL ),
            'email' => __('Email id' , SM_LB_URL ),
            'emailcondition' => __('Emailcondition' , SM_LB_URL ),
            'debugmode' => __('Debug Mode', SM_LB_URL ),
                    );

        foreach ($fieldNames as $field=>$value){

            if(isset($sales_config_array[$field]))
            {
                $config[$field] = $sales_config_array[$field];
            }
        }
                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$exist_config = get_option( "wp_{$activateplugin}_settings" );
                if( !empty( $exist_config ) )
                        $config = array_merge($exist_config, $config);
                $resp =  update_option("wp_{$activateplugin}_settings", $config);
		$sales_resp['resp'] = $resp;
		$result['error'] = 0;
		$successresult = "Settings Saved";
		$result['success'] = $successresult;
		return $result;
	}	
}
