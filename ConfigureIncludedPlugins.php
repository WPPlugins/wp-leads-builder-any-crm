<?php

/******************************************************************************************
 * Copyright (C) Smackcoders 2016 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

global $IncludedPluginsPRO , $DefaultActivePluginPRO , $crmdetailsPRO , $ThirdPartyPlugins , $custom_plugins;
$IncludedPluginsPRO = Array(
		'wptigerpro' =>  "VtigerCRM",		
		'wpsugarpro'  => "SugarCRM" ,
		'wpsuitepro' => "SuiteCRM",		
		'wpzohopro' => "ZohoCRM" ,
		'wpzohopluspro' => "ZohoCRM Plus",	
		'freshsales'    => 'FreshSales',
		'wpsalesforcepro' => 'SalesForceCRM',

	);
$ThirdPartyPlugins = array('none' => "None",
			   'ninjaform' => "Ninja Forms",
			   'contactform' => "Contact Form",
			   'gravityform' => "Gravity Form" ,
			);

$WpMappingModule = array(
			'Leads' => 'Leads',
			'Contacts' => 'Contacts',
			);

$custom_plugins = array('none' => "None",
			'wp-members' => "Wp-members",
			'acf' => "ACF" ,
			'acfpro' => "ACF Pro" ,	
			'member-press' => "MemberPress" ,
			'ultimate-member'=> "UltimateMember"
		      ); 

$crmdetailsPRO =array( 
'wptigerpro'=> array("Label" => "WP Tiger pro" , "crmname" => "VtigerCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpsugarpro' => array( "Label" => "WP Sugar pro" , "crmname" => "SugarCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpsuitepro' => array( "Label" => "WP Suite pro" , "crmname" => "SuiteCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpzohopro' => array("Label" => "WP Zoho pro" , "crmname" => "ZohoCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts")),  
'wpzohopluspro' => array("Label" => "WP Zoho Plus pro" , "crmname" => "ZohoCRM Plus" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts")),
'wpsalesforcepro' => array("Label" => "WP Salesforce pro" , "crmname" => "SalesforceCRM" , "modulename" => array("Leads" => "Lead" ,"Contacts" => "Contact") ),
'freshsales'=> array("Label" => "Fresh Sales" , "crmname" => "FreshSales" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
	);

$DefaultActivePluginPRO = "wptigerpro";

