<?php

/******************************************************************************************
 * Copyright (C) Smackcoders 2016 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

if(!function_exists("Getaccess_token"))
{
function Getaccess_token( $config , $code ) {
	$token_url = "https://login.salesforce.com/services/oauth2/token";
	if (!isset($code) || $code == "") {
	    die("Error - code parameter missing from request!");
	}
	$params = "code=" . $code
	    . "&grant_type=authorization_code"
	    . "&client_id=" . $config['key']
	    . "&client_secret=" . $config['secret']
	    . "&redirect_uri=" . urlencode($config['callback']);
	$curl = curl_init($token_url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if ( $status != 200 ) {
	    die("Error: call to token URL $token_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	}
	curl_close($curl);
	$response = json_decode($json_response, true);
	return $response;
}

/*function refresh_token( $config ) {

        $token_url = "https://login.salesforce.com/services/oauth2/token";
        $params = "grant_type=refresh_token"
            . "&client_id=" . $config['key']
            . "&client_secret=" . $config['secret']
            . "&refresh_token=" . $config['access_token'];
        $curl = curl_init($token_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 200 ) {
            die("Error: call to token URL $token_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        return $response;
}*/

function GetCrmModuleFields( $instance_url, $access_token , $module = "Lead" )
{
    $url = "$instance_url/services/data/v20.0/sobjects/{$module}/describe/";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function Getuser( $instance_url, $access_token , $module = "Lead" )
{
    $url = "$instance_url/services/data/v20.0/sobjects/user/";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function GetRecord( $instance_url, $access_token , $module = "Lead" , $extraparams = array() )
{
    $params = "";
    if(!empty($extraparams) )
    {
	$params = "+where";
	foreach( $extraparams as $key => $value )
	{
		$params .= "+{$key}+=+'{$value}'";
	}
    }
    $url = "$instance_url/services/data/v20.0/query/?q=SELECT+Id,Email+from+{$module}{$params}";
	
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function check_product_present($instance_url, $access_token , $module = "Product2" )
{
    $url = "$instance_url/services/data/v20.0/query/?q=SELECT+Id,Name+from+{$module}";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

//Get Record by ID
function getRecordById($instance_url, $access_token , $module = "Lead" , $id )
{
    $url = "$instance_url/services/data/v20.0/sobjects/Lead/{$id}";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

//Remove Converted Lead
function remove_converted_lead($instance_url, $access_token , $module = "Lead" , $lead_no )
{
    $url = "$instance_url/services/data/v20.0/sobjects/Lead/{$lead_no}";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE"); 
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}
//Get Account ID
function GetAccountId( $instance_url, $access_token , $module = "Account" , $extraparams = array() )
{
    $params = "";
    if(!empty($extraparams) )
    {
        $params = "+where";
        foreach( $extraparams as $key => $value )
        {
                $params .= "+{$key}+=+'{$value}'";
        }
    }
    $url = "$instance_url/services/data/v20.0/query/?q=SELECT+Id+from+{$module}{$params}";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function create_record( $data_array, $instance_url, $access_token , $module = "Lead" ) {
    $url = "$instance_url/services/data/v20.0/sobjects/{$module}/";
    $content = json_encode($data_array);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ( $status != 201 ) {
    }
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function update_record( $data_array, $instance_url, $access_token ,  $id , $module = "Lead" ) {
    $url = "$instance_url/services/data/v20.0/sobjects/{$module}/$id";
    $content = json_encode($data_array);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ( $status != 201 && $status != 204) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    else
    {
	$response['id'] = $id;
    }
    curl_close($curl);
    return $response;
}
}
?>
