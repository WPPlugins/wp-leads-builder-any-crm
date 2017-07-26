<?php
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

include_once ( plugin_dir_path(__FILE__) . '../includes/lb-main-helper.php' );

class SmackLBAdmin  extends SmackLBHelper{

	public function __construct() {
                //self::initializing_scheduler();
        }
	
	public static function admin_menus() {              
		global $submenu;
                add_menu_page(SM_LB_SETTINGS, SM_LB_NAME, 'manage_options', SM_LB_SLUG, array(__CLASS__, 'lb_screens'), plugins_url("assets/images/leadsIcon24.png", dirname(__FILE__)));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('CRM Forms', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-crmforms', array(__CLASS__, 'lb_screens'));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('Form Settings', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-formsettings', array(__CLASS__, 'lb_screens'));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('WP Users Sync', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-usersync', array(__CLASS__, 'lb_screens'));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('Ecom Integ', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-ecominteg', array(__CLASS__, 'lb_screens'));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('CRM Configuration', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-crmconfig', array(__CLASS__, 'lb_screens'));
                add_submenu_page(null, SM_LB_NAME,  esc_html__('', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-create-leadform', array(__CLASS__, 'lb_screens'));
		add_submenu_page(null, SM_LB_NAME,  esc_html__('', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-create-contactform', array(__CLASS__, 'lb_screens'));
		add_submenu_page(null, SM_LB_NAME,  esc_html__('', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-usermodulemapping', array(__CLASS__, 'lb_screens'));
		add_submenu_page(null, SM_LB_NAME,  esc_html__('', 'wp-leads-builder-any-crm'), 'manage_options', 'lb-mailsourcing', array(__CLASS__, 'lb_screens'));

                unset($submenu[SM_LB_SLUG][0]);
        }

        public static function lb_screens() {
                global $lb_admin;
		$active_plugin = get_option("WpLeadBuilderProActivatedPlugin");
               	if($active_plugin == "wpsuitepro") {
			$active_plugin = "wpsugarpro";
		} elseif($active_plugin == "wpzohopluspro") {
			$active_plugin = "wpzohopro";
		}
		$lb_admin->setActivatedPlugin($active_plugin);
		$page = sanitize_text_field($_REQUEST['page']);
		$url = site_url();
		$lb_admin->show_top_navigation_menus();
                switch (sanitize_title($_REQUEST['page'])) {
                        case 'lb-crmforms':
                                $lb_admin->show_form_crm_forms();
                                break;
                        case 'lb-formsettings':
                                $lb_admin->show_form_settings();
                                break;
                        case 'lb-usersync':
                                $lb_admin->show_usersync();
                                break;
                        case 'lb-ecominteg':
                                $lb_admin->show_ecom_integ();
                                break;
                        case 'lb-crmconfig':
			case 'wp-leads-builder-any-crm':
                                //$lb_admin->show_crm_config();
				if($active_plugin == "wpsugarpro") {
					$lb_admin->show_sugar_crm_config();
				} elseif($active_plugin == "wpsuitepro") {
					$lb_admin->show_suite_crm_config();
				} elseif($active_plugin == "wpzohopro") {
                                        $lb_admin->show_zoho_crm_config();
				} elseif($active_plugin == "wpzohopluspro") {
                                        $lb_admin->show_zohoplus_crm_config();
				} elseif($active_plugin == "freshsales") {
                                        $lb_admin->show_freshsales_crm_config();
				} elseif($active_plugin == "wptigerpro") {
                                        $lb_admin->show_vtiger_crm_config();
				} else {
					$lb_admin->show_salesforce_crm_config();
				}
                                break;
                        case 'lb-usermodulemapping':
				$lb_admin->user_module_mapping_view();
				break;
			case 'lb-create-leadform':
				$lb_admin->new_lead_view();
                                break;
			case 'lb-create-contactform':
                                $lb_admin->new_contact_view();
                                break;
			case 'lb-mailsourcing':
				$lb_admin->mail_sourcing_view();
				break;
                        default:
                                break;
                }
                return false;
        }
	

	public function user_module_mapping_view() {
		include ('views/form-usermodulemapping.php');
	}

	public function mail_sourcing_view() {
		include('views/form-campaign.php');
	}

	public function new_lead_view() {
	       global $lb_admin;
               include ('views/form-managefields.php');
        }

	public function new_contact_view() {
               global $lb_admin;
               $module = "Contacts";
               $lb_admin->setModule($module);
               include ('views/form-managefields.php');
        }


	public function show_form_crm_forms() {
               include ('views/form-crmforms.php');
        }

	public function show_form_settings() {
               include ('views/form-settings.php');
        }

	public function show_usersync() {
               include ('views/form-usersync.php');
        }

	public function show_ecom_integ() {
               include ('views/form-ecom-integration.php');
        }

	public function show_vtiger_crm_config() {
               include ('views/form-vtigercrmconfig.php');
        }

	public function show_sugar_crm_config() {
               include ('views/form-sugarcrmconfig.php');
        }

	public function show_suite_crm_config() {
               include ('views/form-suitecrmconfig.php');
        }

	public function show_zoho_crm_config() {
               include ('views/form-zohocrmconfig.php');
        }

	public function show_zohoplus_crm_config() {
               include ('views/form-zohocrmconfig.php');
        }

	public function show_freshsales_crm_config() {
               include ('views/form-freshsalescrmconfig.php');
        }

	public function show_salesforce_crm_config() {
		include('views/form-salesforcecrmconfig.php');
	}

	public function show_top_navigation_menus() {
		//Customer stats
		global $wpdb;
		$activate_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
        	$crmSettings = get_option("wp_{$activate_crm}_settings");
		$disabledMenu = '';
		if(!$crmSettings) {
			$disabledMenu = "pointer-events:none;opacity:0.7;";
		}
		$ecom_integ = "pointer-events:none;opacity:0.7;";
		$admin_url = 'admin.php';
		$latest_customer = $wpdb->get_results( "select session_key from wci_activity where length(session_key)<10 order by id desc limit 1" );
		if( !empty( $latest_customer ))
		{
			$wci_cust = $latest_customer[0]->session_key;
			$customerstats = add_query_arg(array('page' => 'lb-customerstats', 'user_id' => $wci_cust ),$admin_url);
		}
		else
		{
			$customerstats = add_query_arg(array('page' => 'lb-customerstats'),$admin_url);
		}
		echo '<div id="notifications"></div>';
                echo '<div class="lb_menu_bar wp-leads-builder-any-crm">
                <h2 class="nav-tab-wrapper">
                        <a href="'. esc_url (admin_url() .'admin.php?page=lb-crmforms') .'" class="nav-tab" id = "menu1" style="'.$disabledMenu.'">'.esc_html__('CRM Forms','wp-leads-builder-any-crm').'</a>
                        <a href="'. esc_url (admin_url() .'admin.php?page=lb-formsettings') . '" class="nav-tab" id = "menu2" style="'.$disabledMenu.'">'.esc_html__('Form Settings','wp-leads-builder-any-crm').'</a>
                        <a href="'. esc_url (admin_url() .'admin.php?page=lb-usersync') . '" class="nav-tab" id = "menu3" style="'.$disabledMenu.'">'.esc_html__('WP Users Sync','wp-leads-builder-any-crm').'</a>
                        <a href="" class="nav-tab" id = "menu4" style="'.$ecom_integ.' position: relative;">'.esc_html__('Ecom Integration','wp-leads-builder-any-crm').'<span class="label label-warning" style="position: absolute; top: -7px; right: -6px;"> Pro </span></a>
                        <a href="'. esc_url (admin_url() .'admin.php?page=lb-crmconfig') . '" class="nav-tab" id = "menu5" >'.esc_html__('CRM Configuration','wp-leads-builder-any-crm').'</a>
			<a href="" class="nav-tab" id = "menu5" style="'.$ecom_integ.' position: relative;">'.esc_html__('Customer Insight','wp-leads-builder-any-crm').'<span class="label label-warning" style="position: absolute; top: -7px; right: -6px;"> Pro </span></a>
			</h2>
                        </div>
                                <div id="notification_wp_csv"></div>';
	}
}
add_action('admin_menu', array('SmackLBAdmin', 'admin_menus'));
global $lb_admin;
$lb_admin = new SmackLBAdmin();
