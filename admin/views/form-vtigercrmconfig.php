<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly
 
global $wpdb;
$siteurl = site_url();
$siteurl = esc_url( $siteurl );
$active_plugin = get_option('WpLeadBuilderProActivatedPlugin');
//Check Shortcode available
$check_shortcode = $wpdb->get_results( $wpdb->prepare("select shortcode_name from wp_smackleadbulider_shortcode_manager where crm_type=%s", $active_plugin));
$check_field_manager = $wpdb->get_results( $wpdb->prepare("select field_name from wp_smackleadbulider_field_manager where crm_type=%s", $active_plugin));
$count_shortcode=0;
$count_shortcode = count($check_shortcode);
   if( !empty( $check_field_manager)){
         if( $count_shortcode>1 ){
		$shortcode_available = 'yes';
	}else{
		$shortcode_available = 'no';
	}
}else{
	$shortcode_available = 'yes';
}
echo "<input type='hidden' id='check_shortcode_availability' value='$shortcode_available'>";
echo "<input type='hidden' id='count_shortcode' value='$count_shortcode'>";
//END

$config = get_option("wp_{$active_plugin}_settings");
if( $config == "" )
{
        $config_data = 'no';
}
else
{
        $config_data = 'yes';
}

?>
<div class="mt20">
 <div class="form-group col-md-5 col-md-offset-7">    
        <div class="col-md-6">
            <label id="inneroptions" class="leads-builder-crm"><?php echo esc_html__("Select the CRM you use " , "wp-leads-builder-any-crm" ); ?>
            </label>
        </div>
        <div class="col-md-5">          
            <?php $ContactFormPluginsObj = new ContactFormPROPlugins();echo $ContactFormPluginsObj->getPluginActivationHtml();?>
        </div>
</div><!-- form group close -->
</div>  
<div class="clearfix"></div>      
<div>
  <div class="panel" style="width:99%;">
    <div class="panel-body">
	<img src="<?php echo SM_LB_DIR?>assets/images/vtiger-logo.png" width=168 height=42>
      <!-- <div class="form-group">    
        <div class="col-md-3 col-md-offset-3">
            <label id="inneroptions" class="leads-builder-heading"><?php echo esc_html__("Select the CRM you use " , "wp-leads-builder-any-crm" ); ?>
            </label>
        </div> -->
        <div>
            <!--<div id="loading-image" style="display: none; background:url(<?php echo esc_url(WP_PLUGIN_URL);?>/wp-leads-builder-any-crm/images/ajax-loaders.gif) no-repeat center #fff;"><?php echo esc_html__("Please Wait" , "wp-leads-builder-any-crm" ); ?>...</div> -->
			<input type="hidden" id="plug_URL" value="<?php echo esc_url(SM_LB_URL);?>" />
        </div>
        <!-- <div class="col-md-3">			
            <?php $ContactFormPluginsObj = new ContactFormPROPlugins();echo $ContactFormPluginsObj->getPluginActivationHtml();?>
        </div>
      </div> --><!-- form group close -->
    <input type="hidden" id="get_config" value="<?php echo $config_data ?>" >
    <input type="hidden" id="revert_old_crm_pro" value="wptigerpro">
    <span id="save_config" style="font:14px;width:200px;">
    </span>
<div>
<!--  Start -->
         <form id="smack-vtiger-settings-form" action="" method="post">
                <input type="hidden" name="smack-vtiger-settings-form" value="smack-vtiger-settings-form" />
                <input type="hidden" id="plug_URL" value="<?php echo esc_url(SM_LB_URL);?>" />
                <!-- <div class="wp-common-crm-content" style="width: 900px;float: left;"> -->
<div class="clearfix"></div>
    <hr> 
  <div class="mt30">
    <div class="form-group col-md-12">              
        <label id="inneroptions" class="leads-builder-heading">VTiger CRM Settings</label>
    </div>
  </div>
<div class="clearfix"></div>  
 <div class="mt20">   
    <div class="form-group col-md-12">
        <div class="col-md-2 label-space">
            <label id="innertext" class="leads-builder-label"> <?php echo esc_html__('CRM Url' , "wp-leads-builder-any-crm" ); ?> </label>
        </div>
        <div class="col-md-8">
            <input type='text' class='smack-vtiger-settings form-control' name='url' id='smack_tiger_host_address'  value="<?php echo esc_url($config['url']) ?>"/>
        </div>
    </div> 
<div class="form-group col-md-12">
        <div class="col-md-2 label-space">
            <label id="innertext" class="leads-builder-label"> <?php echo esc_html__('Username' , "wp-leads-builder-any-crm" ); ?> </label>
        </div>
        <div class="col-md-3">
            <input type='text' class='smack-vtiger-settings form-control' name='username' id='smack_host_username' value="<?php echo sanitize_text_field($config['username']) ?>"/>
        </div>
        <div class="col-md-2 label-space">
            <label id="innertext" class="leads-builder-label"> <?php echo esc_html__('Access Key' , "wp-leads-builder-any-crm" ); ?> </label>
        </div>
        <div class="col-md-3">
            <input type='text' class='smack-vtiger-settings form-control' name='accesskey' id='smack_host_access_key' value="<?php echo sanitize_text_field($config['accesskey']) ?>"/>  
        </div>
    </div> 
</div>
    
    <input type="hidden" name="posted" value="<?php echo 'posted';?>">
	<input type="hidden" id="site_url" name="site_url" value="<?php echo esc_attr($siteurl) ;?>">
	<input type="hidden" id="active_plugin" name="active_plugin" value="<?php echo esc_attr($active_plugin); ?>">
	<input type="hidden" id="leads_fields_tmp" name="leads_fields_tmp" value="smack_wptigerpro_leads_fields-tmp">
	<input type="hidden" id="contact_fields_tmp" name="contact_fields_tmp" value="smack_wptigerpro_contacts_fields-tmp">
<div class="col-md-offset-9">
	<span id="SaveCRMConfig">
            <input type="button" value="<?php echo esc_attr__('Save CRM Configuration' , "wp-leads-builder-any-crm" );?>" id="save" class="smack-btn smack-btn-primary btn-radius" onclick="saveCRMConfiguration(this.id);" />
        </span>
</div>
<!-- </div> -->
</form>
</div>	   <!-- End-->
<div id="loading-sync" style="display: none; background:url(<?php echo esc_url(WP_PLUGIN_URL);?>/wp-leads-builder-any-crm/assets/images/ajax-loaders.gif) no-repeat center ;"><?php echo esc_html__('' , 'wp-leads-builder-any-crm' ); ?></div>
<div id="loading-image" style="display: none; background:url(<?php echo esc_url(WP_PLUGIN_URL);?>/wp-leads-builder-any-crm/assets/images/ajax-loaders.gif) no-repeat center;"><?php echo esc_html__('' , "wp-leads-builder-any-crm"  ); ?> </div>
</div>
</div>
</div>
