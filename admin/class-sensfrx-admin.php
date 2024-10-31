<?php

/**
 * The admin-specific functionality of the SensFRX plugin.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/admin
 * 
 */
if (!defined('ABSPATH')) {
	die('ASPATH is required - Admin PAGE');
}
class SensFRX_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * 
	 * @var      string    $SensFrx    The ID of this plugin.
	 */

	private $SensFRX;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */

	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $SensFrx       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $SensFRX, $version ) {
		$this->SensFRX = $SensFRX;          // SensFRX
		$this->version = $version;            // 1.0.0
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_styles() {
		/**
		 * Include admin-specific CSS files.
		 */
		$s = wp_enqueue_style( $this->SensFRX, plugin_dir_url( __FILE__ ) . 'css/sensfrx-admin195.css', array(), $this->version, 'all' );	
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_scripts() {
		/**
		 * Include admin-specific Javascript files.
		 * 
		 */
		wp_enqueue_script( $this->SensFRX . '-admin', plugin_dir_url( __FILE__ ) . 'js/sensfrx-admin45.js', array(), '1.0.10', true );
		extract($this->sensfrx_settings_options());
		if ( ( isset($sensfrx_property_id) && !empty($sensfrx_property_id) ) && ( isset($sensfrx_property_secret) && !empty($sensfrx_property_secret) ) ) {
			wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
			if (is_user_logged_in()) {
				wp_add_inline_script( $this->SensFRX, '_sensfrx("userInit", "' . esc_attr(get_current_user_id()) . '");', 'after' );
			}
		}
	}

	public function add_sensfrx_menu() {
		$menu_slug = 'sensfrx_fpwoo';
		add_menu_page( 'Sensfrx', 'Sensfrx', ' ', 'sensfrx_fpwoo', array($this, 'mainMenuCallback'), 'dashicons-admin-generic', 20 );
		add_submenu_page( $menu_slug, 'Sensfrx Dashboard', 'Sensfrx Dashboard', 'manage_options', 'sensfrx_dashboard', array($this, 'woocommerce_dashboard_page') );
		add_submenu_page( $menu_slug, 'Sensfrx Integration', 'Sensfrx Integration', 'manage_options', 'sensfrx_integration', array($this, 'woocommerce_integration_tab_content') );
	}
	
	// =================== Woocommerce Extension Code Start ===========================
	public function woocommerce_settings_page($settings) {
		$sensfrx_settings = include(plugin_dir_path(__FILE__) . 'partials/sensfrx-woocommerce-settings-options.php');
		$settings[] = $sensfrx_settings;
		return $settings;
	}

	public function woocommerce_settings_tab($tabs) {
		$tabs['sensfrx'] = __('Sensfrx', 'sensfrx_fpwoo');
		return $tabs;
	}

	public function woocommerce_settings_tab_content() {
		?>
			<h3 class="sensfrx_tab_nav-tab-wrapper1">
				<!-- <a href="#general" class="sensfrx_tab_nav-tab1" onclick="showTab('general')">SensFRX General</a> | &nbsp; -->
				<a href="#validation-rules" class="sensfrx_tab_nav-tab1" onclick="showTab('validation-rules')">Validation Rules</a> | &nbsp;
				<a href="#policies" id="Policies" class="sensfrx_tab_nav-tab1" onclick="showTab('policies')">Policies Settings</a> | &nbsp;
				<a href="#notification-alert" class="sensfrx_tab_nav-tab1" onclick="showTab('notification-alert')">Notifications/Alerts</a> | &nbsp;
				<a href="#license-information" class="sensfrx_tab_nav-tab1" onclick="showTab('license-information')">License Information</a> | &nbsp;
				<a href="#account-privacy" class="sensfrx_tab_nav-tab1" onclick="showTab('account-privacy')">Account & Privacy</a> | &nbsp;
				<a href="#profile" class="sensfrx_tab_nav-tab1" onclick="showTab('profile')">Profile</a>  <!-- | &nbsp;
				<a href="#sensfrx_custom_slug" class="sensfrx_tab_nav-tab1" onclick="showTab('sensfrx_custom_slug')">Custom Data</a> -->
			</h3>
			<div id="validation-rules" class="sensfrx_sub_subtab-content">
				<?php do_action('woocommerce_validation_rules_tab_content'); ?>
			</div>
			<div id="policies" class="sensfrx_sub_subtab-content">
				<?php do_action('woocommerce_policies_tab_content'); ?>
			</div>
			<div id="notification-alert" class="sensfrx_sub_subtab-content">
				<?php do_action('woocommerce_notifications_alerts_tab_content'); ?>
			</div>
			<div id="license-information" class="sensfrx_sub_subtab-content">
				<?php do_action('woocommerce_license_information_tab_content'); ?>
			</div>
			<div id="account-privacy" class="sensfrx_sub_subtab-content">
				<?php do_action('account_privacy_tab_content'); ?>
			</div>
			<div id="profile" class="sensfrx_sub_subtab-content">
				<?php do_action('woocommerce_profile_tab_content'); ?>
			</div>
			<div id="sensfrx_custom_slug" class="sensfrx_sub_subtab-content">
				<?php require_once SENSFRX_DIR . 'admin/partials/sensfrx-custom-slug.php'; ?>
			</div>
		<?php
	}

	public function woocommerce_integration_tab_content() {
		$sensfrx_options = get_option('sensfrx_options', SensFRX::default_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-integration-tab.php';
	}
	
	public function woocommerce_validation_rules_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx_validation_rule.php';
	}
	
	public function woocommerce_policies_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-policies-settings.php';
	}
	
	public function woocommerce_notifications_alerts_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-notification-alert.php';
	}
	
	public function woocommerce_license_information_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx_license.php';
	}
	
	public function account_privacy_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-account-privacy.php';
	}
	
	public function woocommerce_profile_tab_content() {
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-profile-settings.php';
	}

	// public function woocommerce_sensfrx_custom_content() {
	// 	$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
	// 	require_once SENSFRX_DIR . 'admin/partials/sensfrx-custom-slug.php';
	// }

	public function woocommerce_dashboard_page() {
		require_once SENSFRX_DIR . 'admin/partials/sensfrx-general-tab.php';
	}
	// =================== Woocommerce Extension Code End ===========================
	public function add_settings() {
		register_setting('sensfrx_plugin_options', 'sensfrx_options', array($this, 'validate_settings'));
		register_setting('sensfrx_plugin_policy_options', 'sensfrx_policy_options', array($this, 'validate_policy_settings'));
	}

	public function validate_settings($input) {
		$input['sensfrx_property_id'] = wp_filter_nohtml_kses($input['sensfrx_property_id']);
		if ( isset( $input['sensfrx_property_id'] ) && ( ! is_numeric( $input['sensfrx_property_id'] ) || ( strlen( $input['sensfrx_property_id'] ) != 16 ) ) ) {
			$input['sensfrx_property_id'] = '';
			$message  = esc_html__('Error: Property ID is invalid', 'sensfrx_fpwoo');
			add_settings_error('sensfrx_property_id', 'invalid-property-id', $message, 'error');
		}
		if ( isset( $input['sensfrx_property_secret'] ) && ( empty($input['sensfrx_property_secret']) ) ) {
			$input['sensfrx_property_secret'] = '';
			$message  = esc_html__('Error: Property Secret is invalid', 'sensfrx_fpwoo');
			add_settings_error('sensfrx_property_secret', 'invalid-property-secret', $message, 'error');
		}
		return $input;
	}

	public function validate_policy_settings($input) {
		$input['sensfrx_medium_email'] = wp_filter_nohtml_kses($input['sensfrx_medium_email']);
		$input['sensfrx_challenge_email'] = wp_filter_nohtml_kses($input['sensfrx_challenge_email']);
		$input['sensfrx_deny_email'] = wp_filter_nohtml_kses($input['sensfrx_deny_email']);
		$input['sensfrx_allow_payment_email'] = wp_filter_nohtml_kses($input['sensfrx_allow_payment_email']);
		$input['sensfrx_challenge_payment_email'] = wp_filter_nohtml_kses($input['sensfrx_challenge_payment_email']);
		$input['sensfrx_deny_payment_email'] = wp_filter_nohtml_kses($input['sensfrx_deny_payment_email']);
		$input['sensfrx_allow_register_email'] = wp_filter_nohtml_kses($input['sensfrx_allow_register_email']);
		$input['sensfrx_challenge_register_email'] = wp_filter_nohtml_kses($input['sensfrx_challenge_register_email']);
		$input['sensfrx_deny_register_email'] = wp_filter_nohtml_kses($input['sensfrx_deny_register_email']);
		$input['sensfrx_webhook_allow'] = wp_filter_nohtml_kses($input['sensfrx_webhook_allow']);
		$input['sensfrx_whitle_list_email'] = wp_filter_nohtml_kses($input['sensfrx_whitle_list_email']);
		return $input;
	}

	public function action_links($links, $file) {
		if ( SENSFRX_FILE === $file && current_user_can('manage_options') ) {
			$settings = '<a href="' . admin_url(SENSFRX_PATH) . '">' . esc_html__('Settings', 'sensfrx_fpwoo') . '</a>';
			array_unshift($links, $settings);
		}
		return $links;
	}

	public function add_dashboard_notification() {
		$options = get_option('sensfrx_options', SensFRX::default_options());
		$site_title_of_your_domain = home_url();
		$p_id = $options['sensfrx_property_id'];
		$s_key = $options['sensfrx_property_secret'];
		$current_time = gmdate('Y-m-d H:i:s');
		// 24 Hour Notification Sleep if Property & Secret Key Is Not Configure 
		if ( !isset($_COOKIE['notification_closed']) ) {
			if (!$p_id || !$s_key) {
				echo '<div id="sensfrx_property_secrets_notification" class="notice notice-info is-dismissible" data-notification-value="sensfrx_sleep">';
				echo '<p><big><b>Sensfrx</b></big> - Please configure your Property ID and Secret Key. <a class="sensfrx_configure_here_link" href="' . esc_html($site_title_of_your_domain) . '/wp-admin/admin.php?page=sensfrx_integration" class="alert-link"> Click to Configure it</a></p>';
				echo '</div>';
			}
		} else {
			$last_notification = sanitize_text_field($_COOKIE['notification_closed']);
			$timestamp1 = $current_time;
			$timestamp2 = $last_notification;
			// Convert the timestamps to DateTime objects
			$datetime1 = new DateTime($timestamp1);
			$datetime2 = new DateTime($timestamp2);
			// Calculate the difference between the two DateTime objects
			$interval = $datetime1->diff($datetime2);
			if ($interval->h >= 24 || $interval->d > 0) {
				if (!$p_id || !$s_key) {
					echo '<div id="sensfrx_property_secrets_notification" class="notice notice-info is-dismissible" data-notification-value="sensfrx_sleep">';
					echo '<p><big><b>Sensfrx</b></big> - Please configure your Property ID and Secret Key. <a class="sensfrx_configure_here_link" href="' . esc_html($site_title_of_your_domain) . '/wp-admin/admin.php?page=sensfrx_integration" class="alert-link"> Click to Configure it</a></p>';
					echo '</div>';
				}
			}
		}

		// Activate & Webhook Url API Calling 
		
		$options = get_option('sensfrx_options', SensFRX::default_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = new SensFRX\SensFRX([
			'property_id' => $options['sensfrx_property_id'],
			'property_secret' => $options['sensfrx_property_secret']
		]);
		global $wpdb;
		global $wp_version;
		$version = $wp_version;
		$site_url = site_url();
		$site_url_without_https = str_replace('https://', '', $site_url);
		$postDataArray = [
			'app_type' => 'Woocommerce Extension',
			'app_version' => $version,
			'domain' => $site_url_without_https,
		];
		$webHookUrl = home_url() . '/wp-json/sensfrx-fraud-prevention-for-woocommerce/sensfrx_webhook';
		$webHookUrl_transaction = home_url() . '/wp-json/sensfrx-fraud-prevention-for-woocommerce/sensfrx__transaction_webhook';
		$table_name = 'wp_sensfrx_api_active_and_webhook';
		if (!null == $p_id && !null == $s_key) {
			$results = wp_cache_get('sensfrx_activate_and_webhook', 'sensfrx_group');
			if ($results !== false) {
				$results = $wpdb->get_results("SELECT * FROM wp_sensfrx_api_active_and_webhook");
				wp_cache_replace('sensfrx_activate_and_webhook', $results, 'sensfrx_group', 86400);
			} else {
				$results = $wpdb->get_results("SELECT * FROM wp_sensfrx_api_active_and_webhook");
				wp_cache_set('sensfrx_activate_and_webhook', $results, 'sensfrx_group', 86400);
			}

			if (!empty($results)) {
				
				$first_result = $results[0];
				$id = $first_result->id;
				$table_p_id = $first_result->p_id;
				$table_s_key = $first_result->s_key;
				$table_sensfrx_activate = $first_result->sensfrx_activate;
				$table_sensfrx_webhook = $first_result->sensfrx_webhook;

				if ( $table_p_id != $p_id) {
					// Plugin Activate API
					$Activation = $obj->integrateplugininfo($postDataArray);
					// Webhook URL API 
					$webhook_update =  $obj->addWebHook($webHookUrl);
					$webhook_transaction_update =  $obj->addWebHook($webHookUrl_transaction);

					if ( is_string($Activation) && 'HTTP CODE ERROR401' == $Activation ) {
						$sensfrx_activate = 'false';
					} else if ($Activation) {
						$sensfrx_activate = 'true';
					} else {
						$sensfrx_activate = 'false';
					}
					if ( is_string($webhook_update) && 'HTTP CODE ERROR403' == $webhook_update ) {
						$sensfrx_webhook = 'false';
					} else if ($webhook_update) {
						$sensfrx_webhook = 'true';
					} else {
						$sensfrx_webhook = 'false';
					}
					$results = wp_cache_get('sensfrx_activate_and_webhook_update', 'sensfrx_group');
					if ($results !== false) {
						// Prepare the data
						$data = array(
							'p_id' => $p_id,
							's_key' => $s_key,
							'sensfrx_activate' => $sensfrx_activate,
							'sensfrx_webhook' => $sensfrx_webhook
						);
						$where = array('id' => 1);
						$wpdb->update($table_name, $data, $where);
					} else {
						// Prepare the data
						$data = array(
							'p_id' => $p_id,
							's_key' => $s_key,
							'sensfrx_activate' => $sensfrx_activate,
							'sensfrx_webhook' => $sensfrx_webhook
						);
						$where = array('id' => 1);
						$wpdb->update($table_name, $data, $where);
					}
				} else if ( $table_s_key != $s_key ) {
					// Plugin Activate API
					$Activation = $obj->integrateplugininfo($postDataArray);
					// Webhook URL API 
					$webhook_update =  $obj->addWebHook($webHookUrl);
					$webhook_transaction_update =  $obj->addWebHook($webHookUrl_transaction);
					if ( is_string($Activation) && 'HTTP CODE ERROR401' == $Activation ) {
						$sensfrx_activate = 'false';
					} else if ($Activation) {
						$sensfrx_activate = 'true';
					} else {
						$sensfrx_activate = 'false';
					}
					if ( is_string($webhook_update) && 'HTTP CODE ERROR403' == $webhook_update ) {
						$sensfrx_webhook = 'false';
					} else if ($webhook_update) {
						$sensfrx_webhook = 'true';
					} else {
						$sensfrx_webhook = 'false';
					}
					$results = wp_cache_get('sensfrx_activate_and_webhook_update', 'sensfrx_group');
					if ($results !== false) {
						// Prepare the data
						$data = array(
							'p_id' => $p_id,
							's_key' => $s_key,
							'sensfrx_activate' => $sensfrx_activate,
							'sensfrx_webhook' => $sensfrx_webhook
						);
						$where = array('id' => 1);
						$wpdb->update($table_name, $data, $where);
					} else {
						// Prepare the data
						$data = array(
							'p_id' => $p_id,
							's_key' => $s_key,
							'sensfrx_activate' => $sensfrx_activate,
							'sensfrx_webhook' => $sensfrx_webhook
						);
						$where = array('id' => 1);
						$wpdb->update($table_name, $data, $where);
					}
				} 
			
			} else {
				// Plugin Activate API
				$Activation = $obj->integrateplugininfo($postDataArray);
				// Webhook URL API 
				$webhook_update =  $obj->addWebHook($webHookUrl);
				$webhook_transaction_update =  $obj->addWebHook($webHookUrl_transaction);
				if ($Activation) {
					$sensfrx_activate = 'true';
				} else {
					$sensfrx_activate = 'false';
				}
				if ($webhook_update) {
					$sensfrx_webhook = 'true';
				} else {
					$sensfrx_webhook = 'false';
				}
				$results = wp_cache_get('sensfrx_activate_and_webhook_insert', 'sensfrx_group');
				if ($results !== false) {
					// Prepare the data
					$data = array(
						'p_id' => $p_id,
						's_key' => $s_key,
						'sensfrx_activate' => $sensfrx_activate,
						'sensfrx_webhook' => $sensfrx_webhook
					);
					$wpdb->insert($table_name, $data);
				} else {
					// Prepare the data
					$data = array(
						'p_id' => $p_id,
						's_key' => $s_key,
						'sensfrx_activate' => $sensfrx_activate,
						'sensfrx_webhook' => $sensfrx_webhook
					);
					$wpdb->insert($table_name, $data);
				}
			}
		}

		// Using Cronjob Tables value are updating here
		$current_time = time();
		$logs = get_option('sensfrx_cron_logs', []);
		if (!empty($logs)) {
			$last_log = end($logs); 
			preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $last_log, $matches); // Use regular expression to extract the date and time (format: YYYY-MM-DD HH:MM:SS) from the last log entry 
			if (!empty($matches)) {
				$last_log_time = strtotime($matches[0]);
				if (($current_time - $last_log_time) >= 5) { // 300 seconds = 5 minutes
					global $wpdb;
					$options = get_option('sensfrx_options', SensFRX::default_options());
					if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret']) && null != $options['sensfrx_property_id'] && null != $options['sensfrx_property_secret']) {

						$obj = new SensFRX\SensFRX([
							'property_id' => $options['sensfrx_property_id'],
							'property_secret' => $options['sensfrx_property_secret']
						]);

						$itme_slug_api = $obj->getItem_sluginfo();
						if (isset($itme_slug_api['slugs']) && !empty($itme_slug_api['slugs'])) {
							$sensfrx_slugs = $itme_slug_api['slugs'];
							$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';
							$sensfrx_fetch_results_custom = $wpdb->get_results("SELECT sensfrx_custom_field FROM $table_name_custom", ARRAY_A);
							// echo '<pre>';
							// print_r($sensfrx_fetch_results_custom);
							// echo '<br>';
							$check_if_exist = true;
							$slugs = '';
							foreach ($sensfrx_slugs as $slugs) {
								foreach ($sensfrx_fetch_results_custom as $row) {
									if ($row['sensfrx_custom_field'] === $slugs) {
										$check_if_exist = false;
										break;
									} else {
										$check_if_exist = true;
									}
								}
								if ( true === $check_if_exist ) {
									$results = wp_cache_get('sensfrx_custom_data_using', 'sensfrx_group');
									if ($results !== false) {
										$data = array(
											'sensfrx_custom_field' => $slugs,
										);
										$results = $wpdb->insert($table_name_custom, $data);
									} else {
										$data = array(
											'sensfrx_custom_field' => $slugs,
										);
										$results = $wpdb->insert($table_name_custom, $data);
									}
								} 
							}
						} 
					}

					if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret']) && null != $options['sensfrx_property_id'] && null != $options['sensfrx_property_secret']) {
						$obj = new SensFRX\SensFRX([
							'property_id' => $options['sensfrx_property_id'],
							'property_secret' => $options['sensfrx_property_secret']
						]);
						$shadow_status = $obj->getshadowinfo(); 
						$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
						$sensfrx_shadow_status_table = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A);
						if (is_array($shadow_status)) {
							if ( isset($shadow_status['shadow_mode']) && isset($shadow_status['status']) && 'success' === $shadow_status['status'] ) {
								$shadow_status = $shadow_status['shadow_mode'];
								if('0' == $shadow_status || '1' == $shadow_status) {
									if (empty($sensfrx_shadow_status_table)) {
										$results = wp_cache_get('sensfrx_custom_data_using_shadow', 'sensfrx_group');
										if ($results !== false) {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$results = $wpdb->insert($table_name_shadow_table, $data);
										} else {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$results = $wpdb->insert($table_name_shadow_table, $data);
										}
									} else {
										$results = wp_cache_get('sensfrx_custom_data_using_shadow', 'sensfrx_group');
										if ($results !== false) {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$where = array(
												'id' => 1, // Condition to update the specific row (use appropriate field)
											);
											$results = $wpdb->update($table_name_shadow_table, $data, $where);
										} else {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$where = array(
												'id' => 1, // Condition to update the specific row (use appropriate field)
											);
											$results = $wpdb->update($table_name_shadow_table, $data, $where);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}	

	public function select_menu($items, $menu) {
		$options = get_option('sensfrx_options', SensFRX::default_options());
		$checked = '';
		$output = '';
		$class = '';
		foreach ($items as $item) {
			$key = isset($options[$menu]) ? $options[$menu] : '';
			$value = isset($item['value']) ? $item['value'] : '';
			$checked = ( $value == $key ) ? ' checked="checked"' : '';
			$output .= '<div class="ats-radio-inputs' . esc_attr($class) . '">';
			$output .= '<label>';
			$output .= '<input type="radio" name="sensfrx_options[' . esc_attr($menu) . ']" value="' . esc_attr($item['value']) . '"' . $checked . '> ';
			$output .= '<span>' . $item['label'] . '</span>'; //
			$output .= '</label>';
			$output .= '</div>';
		}
		return $output;
	}

	public function sensfrx_check_update() {
		global $wpdb;
		$table_exists_sensfrx_webhook_logout = null;
		$table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_webhook_logout = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_webhook_logout = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_webhook_logout) {
			$table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
			$charset_collate = $wpdb->get_charset_collate();
			$results = wp_cache_get('sensfrx_table_create_2', 'sensfrx_group');
			if ($results !== false) {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					client_id INTEGER,
					PRIMARY KEY (id)
				) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				$results = dbDelta( $sql );
				wp_cache_replace('sensfrx_table_create_2', $results, 'sensfrx_group', 86400);
			} else {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					client_id INTEGER,
					PRIMARY KEY (id)
				) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				$results = dbDelta( $sql );
				wp_cache_set('sensfrx_table_create_2', $results, 'sensfrx_group', 86400);
			}
		} 

		$table_exists_sensfrx_sleep = null;
		$table_name = $wpdb->prefix . 'sensfrx_sleep';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_sleep = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_sleep = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_sleep) {
			$table_name = $wpdb->prefix . 'sensfrx_sleep'; // Replace 'your_custom_table' with your desired table name
			$charset_collate = $wpdb->get_charset_collate();
			$results = wp_cache_get('sensfrx_table_create_1', 'sensfrx_group');
			if ($results !== false) {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					cid INT NOT NULL,
					c_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
				) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				$results = dbDelta( $sql );
				wp_cache_replace('sensfrx_table_create_1', $results, 'sensfrx_group', 86400);
			} else {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					cid INT NOT NULL,
					c_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
				) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				$results = dbDelta( $sql );
				wp_cache_set('sensfrx_table_create_1', $results, 'sensfrx_group', 86400);
			}
		} 

		$table_exists_sensfrx_api_active_and_webhook = null;
		$table_name = $wpdb->prefix . 'sensfrx_api_active_and_webhook';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_api_active_and_webhook = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_api_active_and_webhook = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_api_active_and_webhook) {
			$table_name = $wpdb->prefix . 'sensfrx_api_active_and_webhook';
			$charset_collate = $wpdb->get_charset_collate();
			$results = wp_cache_get('sensfrx_table_create_3', 'sensfrx_group');
			if ($results !== false) {
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					p_id VARCHAR(255) NOT NULL,
					s_key VARCHAR(255) NOT NULL,
					sensfrx_activate VARCHAR(255) NOT NULL,
					sensfrx_webhook VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			} else {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					p_id VARCHAR(255) NOT NULL,
					s_key VARCHAR(255) NOT NULL,
					sensfrx_activate VARCHAR(255) NOT NULL,
					sensfrx_webhook VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
		} 

		$table_exists_sensfrx_tab_data = null;
		$table_name = $wpdb->prefix . 'sensfrx_tab_data';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_tab_data = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_tab_data = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_tab_data) {
			$table_name_1 = $wpdb->prefix . 'sensfrx_tab_data';
			$charset_collate = $wpdb->get_charset_collate();
			$results_1 = wp_cache_get('sensfrx_table_create_1', 'sensfrx_group');

			if ($results_1 !== false) {
				$sql_1 = "CREATE TABLE $table_name_1 (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_tab VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_1);
			} else {
				$sql_1 = "CREATE TABLE $table_name_1 (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_tab VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_1);
			}
		}

		$table_exists_sensfrx_custome_filed = null;
		$table_name = $wpdb->prefix . 'sensfrx_custome_filed';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_custome_filed = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_custome_filed = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_custome_filed) {
			$table_name_2 = $wpdb->prefix . 'sensfrx_custome_filed';
			$results_2 = wp_cache_get('sensfrx_table_create_2', 'sensfrx_group');

			if ($results_2 !== false) {
				$sql_2 = "CREATE TABLE $table_name_2 (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_custom_field VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_2);
			} else {
				$sql_2 = "CREATE TABLE $table_name_2 (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_custom_field VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_2);
			}
		}

		$table_exists_sensfrx_real_activity = null;
		$table_name = $wpdb->prefix . 'sensfrx_real_activity';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_real_activity = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_real_activity = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_real_activity) {
			$table_name = $wpdb->prefix . 'sensfrx_real_activity';
			$charset_collate = $wpdb->get_charset_collate();
			$results = wp_cache_get('sensfrx_table_create_333', 'sensfrx_group');
			if ($results !== false) {
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_log_type VARCHAR(255) NOT NULL,
					sensfrx_log1 VARCHAR(255) NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			} else {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_log_type VARCHAR(255) NOT NULL,
					sensfrx_log1 VARCHAR(255) NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
		}

		$table_exists_sensfrx_shadow_activity = null;
		$table_name = $wpdb->prefix . 'sensfrx_shadow_activity';
		$results = wp_cache_get('sensfrx_admin_update_data', 'sensfrx_group');
		if ($results !== false) {
			$table_exists_sensfrx_shadow_activity = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_replace('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		} else {
			$table_exists_sensfrx_shadow_activity = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
			wp_cache_set('sensfrx_admin_update_data', $results, 'sensfrx_group', 86400);
		}

		if (false == $table_exists_sensfrx_shadow_activity) {
			$table_name = $wpdb->prefix . 'sensfrx_shadow_activity';
			$charset_collate = $wpdb->get_charset_collate();
			$results = wp_cache_get('sensfrx_table_create_3334', 'sensfrx_group');
			if ($results !== false) {
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_shadow_status VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			} else {
				// Define the SQL query to create the table
				$sql = "CREATE TABLE $table_name (
					id INT NOT NULL AUTO_INCREMENT,
					sensfrx_shadow_status VARCHAR(255) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;";
			
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
		}
	}

	public function sensfrx_webhook() {
		
		register_rest_route('sensfrx-fraud-prevention-for-woocommerce', '/sensfrx_webhook', array(
			'methods' => 'POST',
			'callback' => 'sensfrx_handle_webhook',
			'permission_callback' => '__return_true',
		));

		function sensfrx_handle_webhook(WP_REST_Request $request) {
		
			$body = $request->get_body();
			$data = json_decode($body, true);

			if (function_exists('error_log')) {
				error_log('Webhook data: ' . print_r($data, true));
			}
			
			if (is_null($data)) {
				return new WP_REST_Response(array(
					'status' => 'error',
					'message' => 'Invalid JSON data received',
					'received_body' => $body
				), 400);
			}

			$response_data = array(
				'status' => 'success',
				'message' => 'Sensfrx Webhook received and processed successfully',
				'received_data' => $data,
			);

			if (isset($data['status']) && isset($data['severity']) && isset($data['user']) && isset($data['user']['user_id'])) {
				$sensfrx_status  = $data['status'];
				$severity  = $data['severity'];
				$user  = $data['user'];
				$userId = $user['user_id'];
				$email = ( isset($user['email']) && !empty($user['email'])?$user['email']:false );
				$tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
				if (1 == $userId || '1' == $userId) {
					global $wpdb;
					$tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
					$result = wp_cache_get('sensfrx_admin_webhookcall_data', 'sensfrx_group');
					if ($result !== false) {
						$result = $wpdb->delete(
							$tableName,
							array('client_id' => $userId),
							array('%d') // Format for the client_id, assuming it's an integer
						); 
						$cache_set = wp_cache_replace('sensfrx_admin_webhookcall_data', $result, 'sensfrx_group', 86400);
						if ($cache_set) {
							echo 'Cache update successfully.';
						} else {
							echo 'Failed to update cache.';
						}
					} else {
						$result = $wpdb->delete(
							$tableName,
							array('client_id' => $userId),
							array('%d') // Format for the client_id, assuming it's an integer
						); 
						$cache_set = wp_cache_set('sensfrx_admin_webhookcall_data', $result, 'sensfrx_group', 86400);
						if ($cache_set) {
							echo 'Cache set successfully.';
						} else {
							echo 'Failed to set cache.';
						}
					}
					// $result = $wpdb->delete(
					// 	$tableName,
					// 	array('client_id' => $userId),
					// 	array('%d') // Format for the client_id, assuming it's an integer
					// ); 
				} else {
					if ( 'allow' == $sensfrx_status ) {
						global $wpdb;
						$tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
						$result = wp_cache_get('sensfrx_admin_webhookcall123_data', 'sensfrx_group');
						if ($result !== false) {
							$result = $wpdb->delete(
								$tableName,
								array('client_id' => $userId),
								array('%d') // Format for the client_id, assuming it's an integer
							); 
							$cache_set = wp_cache_replace('sensfrx_admin_webhookcall123_data', $result, 'sensfrx_group', 86400);
							if ($cache_set) {
								echo 'Cache update successfully.';
							} else {
								echo 'Failed to update cache.';
							}
						} else {
							$result = $wpdb->delete(
								$tableName,
								array('client_id' => $userId),
								array('%d') // Format for the client_id, assuming it's an integer
							); 
							$cache_set = wp_cache_set('sensfrx_admin_webhookcall123_data', $result, 'sensfrx_group', 86400);
							if ($cache_set) {
								echo 'Cache set successfully.';
							} else {
								echo 'Failed to set cache.';
							}
						}
						// $result = $wpdb->delete(
						// 	$tableName,
						// 	array('client_id' => $userId),
						// 	array('%d') // Format for the client_id, assuming it's an integer
						// ); 
					} elseif ( 'deny' == $sensfrx_status && 'critical' == $severity ) {
						global $wpdb;
						$result = wp_cache_get('sensfrx_admin_webhookcall_deny_data', 'sensfrx_group');
						if ($result !== false) {
							$tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
							$data = array(
								'client_id' => $userId,
							);
							$insert = $wpdb->insert($tableName, $data);
							$cache_set = wp_cache_replace('sensfrx_admin_webhookcall_deny_data', $result, 'sensfrx_group', 86400);
							if ($cache_set) {
								echo 'Cache update successfully.';
							} else {
								echo 'Failed to update cache.';
							}
						} else {
							$tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
							$data = array(
								'client_id' => $userId,
							);
							$insert = $wpdb->insert($tableName, $data);
							$cache_set = wp_cache_set('sensfrx_admin_webhookcall_deny_data', $result, 'sensfrx_group', 86400);
							if ($cache_set) {
								echo 'Cache set successfully.';
							} else {
								echo 'Failed to set cache.';
							}
						}
						// $tableName = $wpdb->prefix . 'sensfrx_webhook_logout';
						// $data = array(
						// 	'client_id' => $userId,
						// );
						// $insert = $wpdb->insert($tableName, $data);
					}
				}	
			}
			return new WP_REST_Response($response_data, 200);
		}
	}

	public function sensfrx__transaction_webhook() {
		
		register_rest_route('sensfrx-fraud-prevention-for-woocommerce', '/sensfrx__transaction_webhook', array(
			'methods' => 'POST',
			'callback' => 'sensfrx_handle_transaction_webhook',
			'permission_callback' => '__return_true',
		));

		function sensfrx_handle_transaction_webhook(WP_REST_Request $request) {
		
			$body = $request->get_body();
			$data = json_decode($body, true);

			if (function_exists('error_log')) {
				error_log('Webhook data: ' . print_r($data, true));
			}
			
			if (is_null($data)) {
				return new WP_REST_Response(array(
					'status' => 'error',
					'message' => 'Sensfrx Transaction Webhook Invalid JSON data received',
					'received_body' => $body
				), 400);
			}

			$response_data = array(
				'status' => 'success',
				'message' => 'Sensfrx Transaction Webhook received and processed successfully',
				'received_data' => $data,
			);

			if ( isset($data['status']) && isset($data['severity']) && isset($data['order_id']) && isset($data['risk_score']) ) {
				$order_id = $data['order_id'];
				$order_risk_score = $data['risk_score'];
				$sensfrx_order = wc_get_order($order_id);

				if (isset($order_risk_score) && $order_risk_score == "0" && isset($data['status']) && $data['status'] == "allow" ) {
					if ($sensfrx_order) {
						$sensfrx_order->update_status('processing', 'Sensfrx - Order status updated to Processing.');
					}
				}
				if (isset($order_risk_score) && $order_risk_score == "100" && isset($data['status']) && $data['status'] == "deny") {
					$sensfrx_order->update_status('cancelled', 'Sensfrx - Order marked as cancelled due to fraudulent activity.');
					$refund_args = array(
						'amount'         => $sensfrx_order->get_total(),
						'order_id'       => $order_id,
						'reason'         => 'Refund due to detected fraudulent activity.',
						'refund_payment' => true, 
					);
					try {
						$refund = wc_create_refund($refund_args);
				
						if (is_wp_error($refund)) {
							$sensfrx_order->add_order_note('Refund failed: ' . $refund->get_error_message());
						} else {
							$sensfrx_order->add_order_note('Refund processed successfully for the cancelled order.');
						}
					} catch (Exception $e) {
						$sensfrx_order->add_order_note('Error during refund: ' . $e->getMessage());
					}
				}
			}

			return new WP_REST_Response($response_data, 200);
		}
	}
	
	// Multi perpuse function - be aware before make in change
	public function webhook_implementation() {
		// $sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		// echo '<pre>';
		// print_r($sensfrx_policy_options);
		// Webhook Action 
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		$sensfrx_whitelisting_check_checkbox_allow = $sensfrx_policy_options['sensfrx_whitle_list_email'];
		if ($sensfrx_whitelisting_check_checkbox_allow) {
			$sensfrx_whitelisting_check_checkbox_allow = true;
		} else {
			$sensfrx_whitelisting_check_checkbox_allow = false;
		}
		global $wpdb;
		$table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
		wp_nonce_field('sensfrx_admin_custom_action', 'sensfrx_admin_custom_nonce');
		$options = get_option('sensfrx_options', SensFRX::default_policy_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id(); 
			$site_title = get_bloginfo( 'name' );
			$user_info = get_userdata($user_id);
			$email = $user_info->user_email;
			$first_name = $user_info->first_name;
			$last_name = $user_info->last_name;
			// Administrator Access Approved 
			$sensfrx_whitelisting_check = null;
			$sensfrx_whitelisting_check_email = $user_id;
			$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
			$sensfrx_white_list_admin_info_array = array();
			foreach ($sensfrx_white_list_admin_users as $user_data) {
				if ($user_data->ID === $sensfrx_whitelisting_check_email) {
					$sensfrx_whitelisting_check = true;
					break;
				}
			}
			
			if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
				return;
			} else {
				$user_id = intval($user_id);
				$table_exists = wp_cache_get('sensfrx_admin_data', 'sensfrx_group');
				if ($table_exists !== false) {
					require_once plugin_dir_path( __FILE__ ) . 'sql-query/sensfrx_table_exist.php';		
					$table_exists = SensFRX_table_exist::sensfrx_table_check($table_name);
				} else {
					require_once plugin_dir_path( __FILE__ ) . 'sql-query/sensfrx_table_exist.php';		
					$table_exists = SensFRX_table_exist::sensfrx_table_check($table_name);
				}
				// $table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
				// $sanitized_table_name = esc_sql($table_name);
				// $table_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) === $table_name;
				if ($table_exists) {
					$table_exists = wp_cache_get('sensfrx_admin_data_webhook_check', 'sensfrx_group');
					if ($table_exists !== false) {
						// require_once plugin_dir_path( __FILE__ ) . 'sql-query/sensfrx_where_query.php';
						// $results = SensFRX_Where_Query::sensfrx_where_query($user_id);
						$results = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM wp_sensfrx_webhook_logout WHERE client_id = %d', $user_id ));
					} else {
						// require_once plugin_dir_path( __FILE__ ) . 'sql-query/sensfrx_where_query.php';
						// $results = SensFRX_Where_Query::sensfrx_where_query($user_id);
						$results = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM wp_sensfrx_webhook_logout WHERE client_id = %d', $user_id ));
					}
					// $results = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM wp_sensfrx_webhook_logout WHERE client_id = %d', $user_id ));
					if ($results) {
						if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
							$obj = new SensFRX\SensFRX([
								'property_id' => $options['sensfrx_property_id'],
								'property_secret' => $options['sensfrx_property_secret']
							]);
							$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
							$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
							$checkbox_allow_webhook = $sensfrx_policy_options['sensfrx_webhook_allow'];
							if ($checkbox_allow_webhook) {
								$checkbox_allow_webhook = true;
							} else {
								$checkbox_allow_webhook = false;
							}
							if ($checkbox_allow_webhook) {
								if (is_array($res_shadow)) {
									if ('0' == $res_shadow[0]['sensfrx_shadow_status']) {
										$table_exists = wp_cache_get('sensfrx_admin_data_webhook_check', 'sensfrx_group');
										if ($table_exists !== false) {
											$where = array('client_id' => $user_id);
											$deleted = $wpdb->delete($table_name, $where);
											wp_cache_delete('sensfrx_admin_data_webhook_check');
										} else {
											$where = array('client_id' => $user_id);
											$deleted = $wpdb->delete($table_name, $where);
											wp_cache_delete('sensfrx_admin_data_webhook_check');
										}
										// $where = array('client_id' => $user_id);
										// $deleted = $wpdb->delete($table_name, $where);
										$to = $email;
										$subject = 'Urgent: Highly Suspicious Activity Detected on Your Account - Webhook [ Real Time Action ]';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<!-- START CENTERED WHITE CONTAINER -->' . 
																'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<!-- START MAIN CONTENT AREA -->' . 
																	'<tr>' . 
																		'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have blocked a highly suspicious activity from your account and log out the User.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Within the next few minutes, you will receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<!-- END MAIN CONTENT AREA -->' . 
																'</table>' . 
																'<!-- START FOOTER -->' . 
																'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
																'<!-- END FOOTER -->' . 
															'<!-- END CENTERED WHITE CONTAINER -->' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
										$current_user = wp_get_current_user();
										$results = retrieve_password($current_user->user_login);									
										$message = 'Your account is blocked due to suspicious activities.';
										$login_url = site_url( 'wp-login.php?action=as_ch_dn&as_msg=' . urlencode($message), 'login' );
										wp_logout();
										wp_safe_redirect( $login_url );
										exit;
									}
								}
							}
						}
					}
				}
			}
		}

		// Using Cronjob Tables value are updating here
		$current_time = time();
		$logs = get_option('sensfrx_cron_logs', []);
		if (!empty($logs)) {
			$last_log = end($logs); 
			preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $last_log, $matches); // Use regular expression to extract the date and time (format: YYYY-MM-DD HH:MM:SS) from the last log entry 
			if (!empty($matches)) {
				$last_log_time = strtotime($matches[0]);
				if (($current_time - $last_log_time) >= 5) { // 300 seconds = 5 minutes
					global $wpdb;
					$options = get_option('sensfrx_options', SensFRX::default_options());
					if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret']) && null != $options['sensfrx_property_id'] && null != $options['sensfrx_property_secret']) {

						$obj = new SensFRX\SensFRX([
							'property_id' => $options['sensfrx_property_id'],
							'property_secret' => $options['sensfrx_property_secret']
						]);

						$itme_slug_api = $obj->getItem_sluginfo();
						if (isset($itme_slug_api['slugs']) && !empty($itme_slug_api['slugs'])) {
							$sensfrx_slugs = $itme_slug_api['slugs'];

							$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';
							$sensfrx_fetch_results_custom = $wpdb->get_results("SELECT sensfrx_custom_field FROM $table_name_custom", ARRAY_A);
							// echo '<pre>';
							// print_r($sensfrx_fetch_results_custom);
							// echo '<br>';
							$check_if_exist = true;
							$slugs = '';
							foreach ($sensfrx_slugs as $slugs) {
								foreach ($sensfrx_fetch_results_custom as $row) {
									if ($row['sensfrx_custom_field'] === $slugs) {
										$check_if_exist = false;
										break;
									} else {
										$check_if_exist = true;
									}
								}
								if ( true === $check_if_exist ) {
									$results = wp_cache_get('sensfrx_custom_data_using', 'sensfrx_group');
									if ($results !== false) {
										$data = array(
											'sensfrx_custom_field' => $slugs,
										);
										$results = $wpdb->insert($table_name_custom, $data);
									} else {
										$data = array(
											'sensfrx_custom_field' => $slugs,
										);
										$results = $wpdb->insert($table_name_custom, $data);
									}
								} 
							}
						}
					}

					if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret']) && null != $options['sensfrx_property_id'] && null != $options['sensfrx_property_secret']) {
						$obj = new SensFRX\SensFRX([
							'property_id' => $options['sensfrx_property_id'],
							'property_secret' => $options['sensfrx_property_secret']
						]);
						
						$shadow_status = $obj->getshadowinfo(); 
						$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
						$sensfrx_shadow_status_table = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A);
						if (is_array($shadow_status)) {
							if ( isset($shadow_status['shadow_mode']) && isset($shadow_status['status']) && 'success' === $shadow_status['status'] ) {
								$shadow_status = $shadow_status['shadow_mode'];
								if('0' == $shadow_status || '1' == $shadow_status) {
									if (empty($sensfrx_shadow_status_table)) {
										$results = wp_cache_get('sensfrx_custom_data_using_shadow', 'sensfrx_group');
										if ($results !== false) {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$results = $wpdb->insert($table_name_shadow_table, $data);
										} else {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$results = $wpdb->insert($table_name_shadow_table, $data);
										}
									} else {
										$results = wp_cache_get('sensfrx_custom_data_using_shadow', 'sensfrx_group');
										if ($results !== false) {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$where = array(
												'id' => 1, // Condition to update the specific row (use appropriate field)
											);
											$results = $wpdb->update($table_name_shadow_table, $data, $where);
										} else {
											$data = array(
												'sensfrx_shadow_status' => $shadow_status,
											);
											$where = array(
												'id' => 1, // Condition to update the specific row (use appropriate field)
											);
											$results = $wpdb->update($table_name_shadow_table, $data, $where);
										}
									}
								}
							}
						}
					}
				}
			}
		}

		// Bot API Integration
		$device_id = null;
		$device_id_1 = null;
		$device_id_2 = null;
		$device_id_3 = null;
		$device_id_4 = null;
		$res_bot = null;
		if (isset($_COOKIE['device0'])) {   
			$device_id_1 = sanitize_text_field($_COOKIE['device0']);
			unset($_COOKIE['device0']);
			// setcookie("device0", "", time() - 3600, "/");
			// unset($_COOKIE["device0"]);
		} 
		if (isset($_COOKIE['device1'])) {   
			$device_id_2 = sanitize_text_field($_COOKIE['device1']);
			unset($_COOKIE['device1']);
			// setcookie("device1", "", time() - 3600, "/");
			// unset($_COOKIE["device1"]);
		}  
		if (isset($_COOKIE['device2'])) {   
			$device_id_3 = sanitize_text_field($_COOKIE['device2']);
			unset($_COOKIE['device2']);
			// setcookie("device2", "", time() - 3600, "/");
			// unset($_COOKIE["device2"]);
		}  
		if (isset($_COOKIE['device3'])) {   
			$device_id_4 = sanitize_text_field($_COOKIE['device3']);
			unset($_COOKIE['device3']);
			// setcookie("device3", "", time() - 3600, "/");
			// unset($_COOKIE["device3"]);
		}   
		$device_id = $device_id_1 . $device_id_2 . $device_id_3 . $device_id_4;
		if ( empty($device_id) ) {
			$device_id = ' ';
		} 
		if ( isset($_COOKIE['device0']) || isset($_COOKIE['device1']) || isset($_COOKIE['device2']) || isset($_COOKIE['device3']) ) {
			if ( !is_user_logged_in() ) {
				if ( isset($_COOKIE['device0']) ) {
					setcookie('device0', '', time() - 3600, '/');
					unset($_COOKIE['device0']);
				}
				if ( isset($_COOKIE['device1']) ) {
					setcookie('device1', '', time() - 3600, '/');
					unset($_COOKIE['device1']);
				}
				if ( isset($_COOKIE['device2']) ) {
					setcookie('device2', '', time() - 3600, '/');
					unset($_COOKIE['device2']);
				}
				if ( isset($_COOKIE['device3']) ) {
					setcookie('device3', '', time() - 3600, '/');
					unset($_COOKIE['device3']);
				}
			}
		}
		// Administrator Access Approved 
		$sensfrx_whitelisting_check = null;
		$sensfrx_whitelisting_check_email = $user_id;
		$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
		$sensfrx_white_list_admin_info_array = array();
		foreach ($sensfrx_white_list_admin_users as $user_data) {
			if ($user_data->ID === $sensfrx_whitelisting_check_email) {
				$sensfrx_whitelisting_check = true;
				break;
			}
		}
		if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
			return;
		} else {
			if (isset($user_id)) {
				if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
					$obj = new SensFRX\SensFRX([
						'property_id' => $options['sensfrx_property_id'],
						'property_secret' => $options['sensfrx_property_secret']
					]);
					$res_bot = $obj->isBot($device_id, $user_id);
					// echo 'this  is bot<br>';
					// echo '<pre>';
					// print_r($res_bot);
					// die();
				}	
			} else {
				if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
					$obj = new SensFRX\SensFRX([
						'property_id' => $options['sensfrx_property_id'],
						'property_secret' => $options['sensfrx_property_secret']
					]);
					$res_bot = $obj->isBot($device_id, '');
				}
			}
			$customer = WC()->customer;
			if ($customer) {
				$first_name = $customer->get_first_name(); 
				$last_name = $customer->get_last_name(); 
				$email = $customer->get_email();
			} else {
				$first_name = ''; 
				$last_name = ''; 
				$email = '';
			}
			
			if ($res_bot) {
				if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
					$obj = new SensFRX\SensFRX([
						'property_id' => $options['sensfrx_property_id'],
						'property_secret' => $options['sensfrx_property_secret']
					]);
					$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
					$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
					if (is_array($res_shadow)) {
						if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
							if ( gettype(!empty($res_bot)) == 'string' ) {
								// var_dump($res_bot);
								// die();
							} else {
								if (isset($res_bot['status']) && null !== $res_bot['status'] && isset($res_bot['severity']) && null !== $res_bot['severity']) {
									$status = $res_bot['status'];
									$severity = $res_bot['severity'];
									// $status = 'deny';
									// $severity = 'critical';
									$site_title = get_bloginfo( 'name' );
									$checkbox_allow = $sensfrx_policy_options['sensfrx_allow_payment_email'];
									if ($checkbox_allow) {
										$checkbox_allow = true;
									} else {
										$checkbox_allow = false;
									}
									$checkbox_challenge = $sensfrx_policy_options['sensfrx_challenge_payment_email'];
									if ($checkbox_challenge) {
										$checkbox_challenge = true;
									} else {
										$checkbox_challenge = false;
									}
									$checkbox_deny = $sensfrx_policy_options['sensfrx_deny_payment_email'];
									if ($checkbox_deny) {
										$checkbox_deny = true;
									} else {
										$checkbox_deny = false;
									}
									if ('allow' == $status && 'medium' == $severity && true == $checkbox_allow) {		
										$device = $res_bot['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Important: Recent Activity [ Bot Behaviour ] and Security Notification';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<tr>' . 
																		'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We wanted to bring to your attention a matter concerning a bot activity on your account. </p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have identified a successful bot activity from your account, that appeared to have some characteristics indicative of potential suspicious activity. </p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">To ensure your account\'s security, we recommend taking prompt action. Consider changing your password and reviewing your account settings. </p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
																'<div style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
									} 
									if ('challenge' == $status && 'high' == $severity && true == $checkbox_challenge) {
										$device = $res_bot['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Important: Suspicious Bot Activity Detected on Your Account';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<tr>' . 
																		'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We wanted to bring a matter to your immediate attention regarding a recent bot activity from your account.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We have identified a successful bot activity from your account.</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Click below to let us know if this was you or not. If not, we advise changing your password immediately to protect your account.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
																							'<tr>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' . 
																							'</tr>' . 
																							'<tr>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' . 
																							'</tr>' . 
																						'</table>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block;font-weight: 400;line-height: 1.5;color: #fff;text-align: center;text-decoration: none;vertical-align: middle;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;user-select: none;background-color: #0d6efd;border: 1px solid #0d6efd;padding: 4px 12px;font-size: 14px;border-radius: 0.25rem; margin-right: 10px;">This was me</a><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block;font-weight: 400;line-height: 1.5;color: #fff;text-align: center;text-decoration: none;vertical-align: middle;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;user-select: none;background-color: #bb2d3b;border: 1px solid #b02a37;padding: 4px 12px;font-size: 14px;border-radius: 0.25rem;">This was not me</a></p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
																'<div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );	
									}
									if ('deny' == $status && 'critical' == $severity && true == $checkbox_deny) {		
										$device = $res_bot['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Urgent: Highly Suspicious Bot Activity Detected on Your Account';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<!-- START CENTERED WHITE CONTAINER -->' . 
																'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<!-- START MAIN CONTENT AREA -->' . 
																	'<tr>' . 
																		'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have blocked a highly suspicious bot activity from your account.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Within the next few minutes, you will receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<!-- END MAIN CONTENT AREA -->' . 
																'</table>' . 
																'<!-- START FOOTER -->' . 
																'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
																'<!-- END FOOTER -->' . 
															'<!-- END CENTERED WHITE CONTAINER -->' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
										$current_user = wp_get_current_user();
										$results = retrieve_password($current_user->user_login);									
										if ( true === $results ) {
											$message = 'Your account is blocked due to Suspicious Bot Activities. We have sent a password reset email, please reset your password to activate your account again.';
										} else {
											$message = $results->get_error_message();
										}
										$login_url = site_url( 'wp-login.php?action=as_ch_dn&as_msg=' . urlencode($message), 'login' );
										wp_logout();
										wp_safe_redirect( $login_url );
										exit;
									} 
								}	
							}
						}
					}
				}
			}
		}
	}

	public function registration_function($errors, $username, $user_email) {
		// $device_id = '';
		// if ( isset($_POST['device_id_register']) && isset($_POST['woocommerce-register-nonce']) && wp_verify_nonce(sanitize_text_field($_POST['woocommerce-register-nonce']), 'woocommerce-register') ) {
		// 	$device_id = sanitize_text_field($_POST['device_id_register']);
		// }
		$device_id = null;
		$device_id_1 = null;
		$device_id_2 = null;
		$device_id_3 = null;
		$device_id_4 = null;
		$res_bot = null;
		if (isset($_COOKIE['device0'])) {   
			$device_id_1 = sanitize_text_field($_COOKIE['device0']);
			unset($_COOKIE['device0']);
			// setcookie("device0", "", time() - 3600, "/");
			// unset($_COOKIE["device0"]);
		} 
		if (isset($_COOKIE['device1'])) {   
			$device_id_2 = sanitize_text_field($_COOKIE['device1']);
			unset($_COOKIE['device1']);
			// setcookie("device1", "", time() - 3600, "/");
			// unset($_COOKIE["device1"]);
		}  
		if (isset($_COOKIE['device2'])) {   
			$device_id_3 = sanitize_text_field($_COOKIE['device2']);
			unset($_COOKIE['device2']);
			// setcookie("device2", "", time() - 3600, "/");
			// unset($_COOKIE["device2"]);
		}  
		if (isset($_COOKIE['device3'])) {   
			$device_id_4 = sanitize_text_field($_COOKIE['device3']);
			unset($_COOKIE['device3']);
			// setcookie("device3", "", time() - 3600, "/");
			// unset($_COOKIE["device3"]);
		}   
		$device_id = $device_id_1 . $device_id_2 . $device_id_3 . $device_id_4;
		if ( empty($device_id) ) {
			$device_id = '';
		} 
		$options = get_option('sensfrx_options', SensFRX::default_options());
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');

		$user_email = $user_email;
		$display_username = $username;
		global $wpdb;
		$sensfrx_approve = null;
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			$res_register = $obj->registerAttempt('register_succeeded', $device_id, array('email'=>$user_email,'name'=>$display_username,'phone'=>'','password'=>''));
			// echo '<pre>';
			// print_r($res_register);
			// die();
			if (isset($res_register) && gettype(!empty($res_register)) !== 'string') {
				$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
				$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
				if (is_array($res_shadow)) {
					if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
						if (isset($res_register['status']) && null !== $res_register['status'] && isset($res_register['severity']) && null !== $res_register['severity']) {
							$status = $res_register['status'];
							$severity = $res_register['severity'];
							if($status == 'allow' || $status == 'challenge' || $status == 'low') {
								$sensfrx_approve = 'approved';
							} else {
								$sensfrx_approve = $status;
							}
							$site_title = get_bloginfo( 'name' ); 
							$email = $user_email;
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $user_email;
							$insert_data = $user_email_ato . " attempted to register in on " . $current_date_ato . ". The new account was ". $sensfrx_approve ." due to risk score level of ". $severity .".";
							$data = array(
								'sensfrx_log_type' =>  'New Account',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							}
							/* The below code is checking the value of the variable . If the value is truthy
							(evaluates to true), it sets  to true. Otherwise, it sets  to
							false. */
							$checkbox_allow = $sensfrx_policy_options['sensfrx_allow_register_email'];
							if ($checkbox_allow) {
								$checkbox_allow = true;
							} else {
								$checkbox_allow = false;
							}
							$checkbox_challenge = $sensfrx_policy_options['sensfrx_challenge_register_email'];
							if ($checkbox_challenge) {
								$checkbox_challenge = true;
							} else {
								$checkbox_challenge = false;
							}
							$checkbox_deny = $sensfrx_policy_options['sensfrx_deny_register_email'];
							if ($checkbox_deny) {
								$checkbox_deny = true;
							} else {
								$checkbox_deny = false;
							}
							if ('allow' == $status && 'low' == $severity) {
								return $errors;
							} else if ('allow' == $status && 'medium' == $severity && true == $checkbox_allow) {		
								$device = $res_register['device'];
								$device = (array) $device;
								$to = $email;
								$subject = 'Important: Recent Registration and Security Notification';
								$body = '<!doctype html>' . 
								'<html>' . 
									'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
									'</head>' . 
									'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
										'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
											'<tr>' . 
												'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
													'<div style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
														'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
															'<tr>' . 
																'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td>' . 
																				'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We wanted to bring to your attention a matter concerning a recent registration attempt on your account. </p>' . 
																			'</td>' . 
																		'</tr>' . 
																		'<tr>' . 
																			'<td>' . 
																				'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have identified a recent registration attempt for your account that appears to have characteristics indicative of potential fraudulent activity.</p>' . 
																				'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">To ensure your account\'s security, we recommend taking prompt action. Consider changing your password and reviewing your account settings. If this registration was not initiated by you, please contact our support team immediately.</p>' . 
																			'</td>' . 
																		'</tr>' . 
																		'<tr>' . 
																			'<td>' . 
																				'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																			'</td>' . 
																		'</tr>' . 
																		'<tr>' . 
																			'<td>' . 
																				'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</td>' . 
															'</tr>' . 
														'</table>' . 
														'<div style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
															'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																'<tr>' . 
																	'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																		'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																	'</td>' . 
																'</tr>' . 
															'</table>' . 
														'</div>' . 
													'</div>' . 
												'</td>' . 
												'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
											'</tr>' . 
										'</table>' . 
									'</body>' . 
								'</html>';
								$headers = array('Content-Type: text/html; charset=UTF-8');
								$mailResult = wp_mail( $to, $subject, $body, $headers );
								return $errors;	
							} else if ('challenge' == $status && 'high' == $severity && true == $checkbox_challenge) {
								$device = $res_register['device'];
								$device = (array) $device;
								$to = $email;
								$subject = 'Important: Suspicious Registration Detected on Your Account';
								$body = '<!doctype html>' . 
								'<html>' . 
									'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
									'</head>' . 
									'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
										'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
										'<div style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
										'<!-- START CENTERED WHITE CONTAINER -->' . 
										'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
										'<!-- START MAIN CONTENT AREA -->' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We wanted to bring to your attention a matter concerning a challenge on your account.</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We have identified a suspicious registration from your account.</p>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Click below to let us know if this was you or not. If not, we advise changing your password immediately to protect your account.</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
										'<tr>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' . 
										'</tr>' . 
										'<tr>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' . 
										'</tr>' . 
										'</table>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #0d6efd; border: 1px solid #0d6efd; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem; margin-right: 10px;">This was me</a><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #bb2d3b; border: 1px solid #b02a37; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem;">This was not me</a></p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
										'</td>' . 
										'</tr>' . 
										'</table>' . 
										'</td>' . 
										'</tr>' . 
										'<!-- END MAIN CONTENT AREA -->' . 
										'</table>' . 
										'<!-- START FOOTER -->' . 
										'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
										'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
										'</td>' . 
										'</tr>' . 
										'</table>' . 
										'</div>' . 
										'<!-- END FOOTER -->' . 
										'<!-- END CENTERED WHITE CONTAINER -->' . 
										'</div>' . 
										'</td>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'</tr>' . 
										'</table>' . 
									'</body>' . 
								'</html>';

								$headers = array('Content-Type: text/html; charset=UTF-8');
								$mailResult = wp_mail( $to, $subject, $body, $headers );
								return $errors;			
							} else if ('deny' == $status && 'critical' == $severity && true == $checkbox_deny) {		
								$device = $res_register['device'];
								$device = (array) $device;
								$to = $email;
								$subject = 'Urgent: Highly Suspicious Registration Detected on Your Account';
								$body = '<!doctype html>' . 
								'<html>' . 
									'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
									'</head>' . 
									'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
										'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
										'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
											'<tr>' . 
												'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
												'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
													'<!-- START CENTERED WHITE CONTAINER -->' . 
													'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
														'<!-- START MAIN CONTENT AREA -->' . 
														'<tr>' . 
															'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
															'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																'<tr>' . 
																	'<td>' . 
																		'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We have blocked a highly suspicious registration from your account.</p>' . 
																		'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">For your security, please verify any recent activity on your account that seems unfamiliar.</p>' . 
																		'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">If you did not initiate this registration, Ignore it.</p>' . 
																	'</td>' . 
																'</tr>' . 
																'<tr>' . 
																	'<td>' . 
																		'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																	'</td>' . 
																'</tr>' . 
																'<tr>' . 
																	'<td>' . 
																		'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																	'</td>' . 
																'</tr>' . 
															'</table>' . 
															'</td>' . 
														'</tr>' . 
														'<!-- END MAIN CONTENT AREA -->' . 
													'</table>' . 
													'<!-- START FOOTER -->' . 
													'<div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
													'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
														'<tr>' . 
														'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
															'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
														'</td>' . 
														'</tr>' . 
													'</table>' . 
													'</div>' . 
													'<!-- END FOOTER -->' . 
												'<!-- END CENTERED WHITE CONTAINER -->' . 
												'</div>' . 
												'</td>' . 
												'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
											'</tr>' . 
										'</table>' . 
									'</body>' . 
								'</html>';
								$headers = array('Content-Type: text/html; charset=UTF-8');
								$mailResult = wp_mail( $to, $subject, $body, $headers );
								$message = 'Your account is blocked due to Suspicious Registration Activities.';
								$errors->add('registration_error', __('Sensfrx - Registration cannot be completed due to detected fraudulent activity.', 'woocommerce'));
								return $errors;
							} else {
								return $errors;
							}
						} else {
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $user_email;
							$insert_data = "User ". $user_email_ato ." attempted a register on " . $current_date_ato . ". Sensfrx encountered an error, so the new account was approved.";
							// $insert_data = $user_email_ato . " attempted registration on " . $current_date_ato . ". The Registration is ". $res_register['status'] . " due to risk score level " . $res_register['severity'] .". Error";
							$data = array(
								'sensfrx_log_type' =>  'New Account',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								return $errors;
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								return $errors;
							}
							return $errors;
						}
					} else {
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						$current_date_ato = date('Y-m-d H:i:s'); 
						$user_email_ato = $user_email;
						$insert_data = "User " . $user_email_ato . " attempted to register on " . $current_date_ato . ". Sensfrx flagged the new account with a " . $res_register['severity'] . " risk score. but since Shadow Mode was enabled, it was allowed.";
						// $insert_data = $user_email_ato . " attempted registration on " . $current_date_ato . ". But Shadow mode is on.";
						$data = array(
							'sensfrx_log_type' =>  'New Account',
							'sensfrx_log1' => $insert_data,
						);
						$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
						if ($results !== false) {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							return $errors;
						} else {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							return $errors;
						}
						return $errors;
					}
				} else {
					$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
					$current_date_ato = date('Y-m-d H:i:s'); 
					$user_email_ato = $user_email;
					$insert_data = "User " . $user_email_ato . " attempted a register on " . $current_date_ato . ". An error occurred while fetching Shadow Mode data.";
					// $insert_data = $user_email_ato . " attempted registration on " . $current_date_ato . ". Shodow Mode API response is unformatted. Type: " . gettype($res_shadow) . ".";
					$data = array(
						'sensfrx_log_type' =>  'New Account',
						'sensfrx_log1' => $insert_data,
					);
					$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
					if ($results !== false) {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						return $errors;
					} else {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						return $errors;
					}
					return $errors;
				}
			} else {
				$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
				$current_date_ato = date('Y-m-d H:i:s'); 
				$user_email_ato = $user_email;
				$insert_data = "User " . $user_email_ato. " attempted a register on " . $current_date_ato . ". However, Sensfrx returned an unformatted response, causing an issue.";
				// $insert_data = $user_email_ato . " attempted registration on " . $current_date_ato . ". API response is unformatted. Type: " . gettype($res_register) . ".";
				$data = array(
					'sensfrx_log_type' =>  'New Account',
					'sensfrx_log1' => $insert_data,
				);
				$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
				if ($results !== false) {
					$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					return $errors;
				} else {
					$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					return $errors;
				}
				return $errors;
			}
		}
	}

	public function wp_login_track($username, WP_User $user) {
		// $device_id = '';
		// if ( isset($_POST['device_id']) && isset($_POST['woocommerce-login-nonce']) && wp_verify_nonce(sanitize_text_field($_POST['woocommerce-login-nonce']), 'woocommerce-login') ) {
		// 	$device_id = sanitize_text_field($_POST['device_id']);
		// }
		// Bot API Integration
		$device_id = null;
		$device_id_1 = null;
		$device_id_2 = null;
		$device_id_3 = null;
		$device_id_4 = null;
		$res_bot = null;
		if (isset($_COOKIE['device0'])) {   
			$device_id_1 = sanitize_text_field($_COOKIE['device0']);
			unset($_COOKIE['device0']);
			// setcookie("device0", "", time() - 3600, "/");
			// unset($_COOKIE["device0"]);
		} 
		if (isset($_COOKIE['device1'])) {   
			$device_id_2 = sanitize_text_field($_COOKIE['device1']);
			unset($_COOKIE['device1']);
			// setcookie("device1", "", time() - 3600, "/");
			// unset($_COOKIE["device1"]);
		}  
		if (isset($_COOKIE['device2'])) {   
			$device_id_3 = sanitize_text_field($_COOKIE['device2']);
			unset($_COOKIE['device2']);
			// setcookie("device2", "", time() - 3600, "/");
			// unset($_COOKIE["device2"]);
		}  
		if (isset($_COOKIE['device3'])) {   
			$device_id_4 = sanitize_text_field($_COOKIE['device3']);
			unset($_COOKIE['device3']);
			// setcookie("device3", "", time() - 3600, "/");
			// unset($_COOKIE["device3"]);
		}   
		$device_id = $device_id_1 . $device_id_2 . $device_id_3 . $device_id_4;
		if ( empty($device_id) ) {
			$device_id = '';
		} 
		global $wpdb;
		$sensfrx_approve == null;
		$options = get_option('sensfrx_options', SensFRX::default_policy_options());
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			$res = $obj->loginAttempt('login_succeeded' , $user->data->ID, $device_id, array('email'=>$user->data->user_email,'username'=>$username));
			// echo $device_id.'<br>';
			// echo '<pre>';
			// print_r($res);
			// die();
			$sensfrx_whitelisting_check = null;
			$sensfrx_whitelisting_check_email = $user->data->user_email;
			$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
			$sensfrx_white_list_admin_info_array = array();
			foreach ($sensfrx_white_list_admin_users as $user_data) {
				if ($user_data->user_email === $sensfrx_whitelisting_check_email) {
					$sensfrx_whitelisting_check = true;
					break;
				}
			}
			$sensfrx_whitelisting_check_checkbox_allow = $sensfrx_policy_options['sensfrx_whitle_list_email'];
			if ($sensfrx_whitelisting_check_checkbox_allow) {
				$sensfrx_whitelisting_check_checkbox_allow = true;
			} else {
				$sensfrx_whitelisting_check_checkbox_allow = false;
			}
			if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
				return;
			} else {
				if (isset($res) && gettype($res) !== 'string') {
					$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
					$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
					if (is_array($res_shadow)) {
						if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
							if (isset($res['status']) && null !== $res['status'] && isset($res['severity']) && null !== $res['severity']) {
								$status = $res['status'];
								$severity = $res['severity'];
								if($status == 'allow' || $status == 'challenge' || $status == 'low') {
									$sensfrx_approve = 'approved';
								} else {
									$sensfrx_approve = $status;
								}
								$site_title = get_bloginfo( 'name' );
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $user->data->user_email;
								$insert_data = $user_email_ato . " attempted to log in on " . $current_date_ato . ". The login was ". $sensfrx_approve ." due to risk score level of ". $severity .".";
								$data = array(
									'sensfrx_log_type' =>  'Account Security',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
								}
								/* The below code is checking the value of the variable ['sensfrx_medium_email']
								and assigning the value to the variable . If the value is truthy,
								is set to true. Otherwise, it is set to false. */
								$checkbox_allow = $sensfrx_policy_options['sensfrx_medium_email'];
								if ($checkbox_allow) {
									$checkbox_allow = true;
								} else {
									$checkbox_allow = false;
								}
								$checkbox_deny = $sensfrx_policy_options['sensfrx_deny_email'];
								if ($checkbox_deny) {
									$checkbox_deny = true;
								} else {
									$checkbox_deny = false;
								}
								$checkbox_challenge = $sensfrx_policy_options['sensfrx_challenge_email'];
								if ($checkbox_challenge) {
									$checkbox_challenge = true;
								} else {
									$checkbox_challenge = false;
								}
								if ('challenge' == $status && 'high' == $severity && true == $checkbox_challenge) {
									$lost_pass_url = wp_lostpassword_url();
									$device = $res['device'];
									$device = (array) $device;
									$to = $user->data->user_email;
									$subject = 'Suspicious activity detected on this account';
									$body = '<!doctype html>' . 
									'<html>' . 
										'<head>' . 
											'<meta name="viewport" content="width=device-width">' . 
											'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
											'<title>' . $subject . '</title>' . 
										'</head>' . 
										'<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
											'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
											'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
												'<tr>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
														'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
															'<!-- START CENTERED WHITE CONTAINER -->' . 
															'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																'<!-- START MAIN CONTENT AREA -->' . 
																'<tr>' . 
																	'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																		'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $user->data->display_name . '</strong>,</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Suspicious login detected on the below device:</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
																						'<tr>' . 
																							'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' . 
																							'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
																							'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' . 
																						'</tr>' . 
																						'<tr>' . 
																							'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' . 
																							'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
																							'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' . 
																						'</tr>' . 
																					'</table>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We highly recommend to reset your password on this device if it was you. Click below to reset your password:</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . $lost_pass_url . '">' . $lost_pass_url . '</a></p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																				'</td>' . 
																			'</tr>' . 
																		'</table>' . 
																	'</td>' . 
																'</tr>' . 
																'<!-- END MAIN CONTENT AREA -->' . 
															'</table>' . 
															'<!-- START FOOTER -->' . 
															'<div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																	'<tr>' . 
																		'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																			'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
															'</div>' . 
															'<!-- END FOOTER -->' . 
															'<!-- END CENTERED WHITE CONTAINER -->' . 
														'</div>' . 
													'</td>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'</tr>' . 
											'</table>' . 
										'</body>' . 
									'</html>';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									$mailResult = wp_mail( $to, $subject, $body, $headers );
								} else if ('deny' == $status && 'critical' == $severity && true ==  $checkbox_deny) {
									$device = $res['device'];
									$device = (array) $device;
									$to = $user->data->user_email;
									$first_name = $user->data->user_nicename;
									$subject = 'Suspicious Login prevented';
									$body = '<!doctype html>' . 
									'<html>' . 
									'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
									'</head>' . 
									'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
										'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
										'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
										'<tr>' . 
											'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
											'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
											'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
												'<!-- START CENTERED WHITE CONTAINER -->' . 
												'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
												'<!-- START MAIN CONTENT AREA -->' . 
												'<tr>' . 
													'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
													'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
														'<tr>' . 
															'<td>' . 
																'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . '</strong>,</p>' . 
																'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have blocked a highly suspicious login activity.</p>' . 
															'</td>' . 
														'</tr>' . 
														'<tr>' . 
															'<td>' . 
																'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Within the next few minutes, you would receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
															'</td>' . 
														'</tr>' . 
														'<tr>' . 
															'<td>' . 
																'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
															'</td>' . 
														'</tr>' . 
														'<tr>' . 
															'<td>' . 
																'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
															'</td>' . 
														'</tr>' . 
													'</table>' . 
													'</td>' . 
												'</tr>' . 
												'<!-- END MAIN CONTENT AREA -->' . 
												'</table>' . 
												'<!-- START FOOTER -->' . 
												'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
												'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
													'<tr>' . 
													'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
														'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
													'</td>' . 
													'</tr>' . 
												'</table>' . 
												'</div>' . 
												'<!-- END FOOTER -->' . 
											'<!-- END CENTERED WHITE CONTAINER -->' . 
											'</div>' . 
											'</td>' . 
											'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'</tr>' . 
										'</table>' . 
									'</body>' . 
									'</html>';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									$mailResult = wp_mail( $to, $subject, $body, $headers );
									sleep(3);
									$results = retrieve_password( $user->data->user_login );
									if ( true === $results ) {
										$message = 'Your account is blocked due to suspicious activities. We have sent a password reset email, please reset your password to activate your account again.';
									} else {
										$message = $results->get_error_message();
									}
									$login_url = site_url( 'wp-login.php?action=as_ch_dn&as_msg=' . urlencode($message), 'login' );
									wp_logout();
									wp_safe_redirect( $login_url );
									exit;
								} else if ('allow' == $status && 'medium' == $severity && true == $checkbox_allow) {
									global $wpdb;
									$device = $res['device'];
									$device = (array) $device;
									$to = $user->data->user_email;
									$table_name = $wpdb->prefix . 'sensfrx_sleep'; 
									$sanitized_table_name = esc_sql($table_name);
									$current_time = gmdate('Y-m-d H:i:s');
									$user_id = $user->data->ID;
									$exist_one = false;
									$results = wp_cache_get('sensfrx_admin_24_data', 'sensfrx_group');
									if ($results !== false) {
										$results = $wpdb->get_results("SELECT * FROM wp_sensfrx_sleep");
										wp_cache_replace('sensfrx_admin_24_data', $results, 'sensfrx_group', 86400);
									} else {
										$results = $wpdb->get_results("SELECT * FROM wp_sensfrx_sleep");
										wp_cache_set('sensfrx_admin_24_data', $results, 'sensfrx_group', 86400);
									}
									// $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM `%s`', $sanitized_table_name));
									if ($results) {
										// Loop through the results and do something with each row
										foreach ($results as $row) {
											$cid = $row->cid;
											if ($cid == $user_id) {
												$exist_one = true;   // If record found then update query proceed 
												break;
											}
										}
									}
									if ( true === $exist_one ) {
										global $wpdb;
										$table_name = $wpdb->prefix . 'sensfrx_sleep';  
										$sanitized_table_name = esc_sql($table_name); 
										$results = wp_cache_get('sensfrx_admin_24_check_data', 'sensfrx_group');
										if ($results !== false) {
											$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM wp_sensfrx_sleep WHERE `cid` = %d', $user_id));
											wp_cache_replace('sensfrx_admin_24_check_data', $results, 'sensfrx_group', 86400);
										} else {
											$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM wp_sensfrx_sleep WHERE `cid` = %d', $user_id));
											wp_cache_set('sensfrx_admin_24_check_data', $results, 'sensfrx_group', 86400);
										}
										// $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM `%s` WHERE `cid` = %d', $sanitized_table_name, $user_id));
										$d_time = 23;
										// Check if there are any results
										if ($results) {
											foreach ($results as $row) {
												if ($user_id == $row->cid) {
													$d_time = $row->c_time;      
												}
											}
										}
										// Current Timestamp
										// $timestamp1 = $current_time;
										$timestamp1 = $current_time;
										// echo $timestamp1;
										// die();
										// Last Timestamp which store first time in database
										$timestamp2 = $d_time;
										// Convert the timestamps to DateTime objects
										$datetime1 = new DateTime($timestamp1);
										$datetime2 = new DateTime($timestamp2);
										// Calculate the difference between the two DateTime objects
										$interval = $datetime1->diff($datetime2);
										// $totalMinutes = ($interval->h * 60) + $interval->i;
										// Check if the difference is equal to or greater than 24 hours
										if ($interval->m > 0 || $interval->h >= 24 || $interval->d > 0) {
											// if($totalMinutes >= 5)
											// {
											$device = $res['device'];
											$device = (array) $device;
											$subject = 'Unusual activity detected on this account';
											$body = '<!doctype html>' .
											'<html>' .
												'<head>' .
													'<meta name="viewport" content="width=device-width">' .
													'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' .
													'<title>' . $subject . '</title>' .
												'</head>' .
												'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' .
													'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' .
													'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%; background-color: #f6f6f6;">' .
														'<tr>' .
															'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' .
															'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' .
																'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' .
																	'<table style="border-collapse: separate; width: 100%; background: #ffffff; border-radius: 3px;">' .
																		'<tr>' .
																			'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' .
																				'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%;">' .
																					'<tr>' .
																						'<td>' .
																							'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $user->data->display_name . '</strong>,</p>' .
																							'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">There is some unusual activity detected on this account. Did you recently use this device to perform some activity?</p>' .
																						'</td>' .
																					'</tr>' .
																					'<tr>' .
																						'<td>' .
																							'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' .
																								'<tr>' .
																									'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' .
																									'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' .
																									'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' .
																								'</tr>' .
																								'<tr>' .
																									'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' .
																									'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' .
																									'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' .
																								'</tr>' .
																							'</table>' .
																						'</td>' .
																					'</tr>' .
																					'<tr>' .
																						'<td>' .
																							'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #0d6efd; border: 1px solid #0d6efd; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem; margin-right: 10px;">This was me</a><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #bb2d3b; border: 1px solid #b02a37; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem;">This was not me</a></p>' .
																						'</td>' .
																					'</tr>' .
																					'<tr>' .
																						'<td>' .
																							'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' .
																						'</td>' .
																					'</tr>' .
																					'<tr>' .
																						'<td>' .
																							'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' .
																						'</td>' .
																					'</tr>' .
																				'</table>' .
																			'</td>' .
																		'</tr>' .
																	'</table>' .
																	'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' .
																		'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%;">' .
																			'<tr>' .
																				'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' .
																					'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' .
																				'</td>' .
																			'</tr>' .
																		'</table>' .
																	'</div>' .
																'</div>' .
															'</td>' .
															'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' .
														'</tr>' .
													'</table>' .
												'</body>' .
											'</html>';
											$headers = array('Content-Type: text/html; charset=UTF-8');
											$mailResult = wp_mail( $to, $subject, $body, $headers );
											// Update the timestamp
											global $wpdb;
											$table_name = $wpdb->prefix . 'sensfrx_sleep'; 
											$data = array(
												'c_time' => $current_time,
											);
											// The WHERE clause to specify the rows to be updated (for example, updating the row with 'id' equal to 1)
											$where = array(
												'cid' => $user_id,
											);
											// Execute the update query
											$results = wp_cache_get('sensfrx_admin_24_update_data', 'sensfrx_group');
											if ($results !== false) {
												$updated = $wpdb->update($table_name, $data, $where);
												wp_cache_replace('sensfrx_admin_24_update_data', $results, 'sensfrx_group', 86400);
											} else {
												$updated = $wpdb->update($table_name, $data, $where);
												wp_cache_set('sensfrx_admin_24_update_data', $results, 'sensfrx_group', 86400);
											}
											// $updated = $wpdb->update($table_name, $data, $where);
										} 
									} else {
										global $wpdb;
										$table_name = $wpdb->prefix . 'sensfrx_sleep'; 
										// Prepare data for insertion
										$data = array(
											array(
												'cid' => $user_id,
												'c_time' =>  $current_time,
											),
										);
										// Insert data into the custom table
										$results = wp_cache_get('sensfrx_admin_24_insert_data', 'sensfrx_group');
										if ($results !== false) {
											foreach ($data as $row) {
												$wpdb->insert($table_name, $row);
											}
											wp_cache_replace('sensfrx_admin_24_insert_data', $results, 'sensfrx_group', 86400);
										} else {
											foreach ($data as $row) {
												$wpdb->insert($table_name, $row);
											}
											wp_cache_set('sensfrx_admin_24_insert_data', $results, 'sensfrx_group', 86400);
										}
										// foreach ($data as $row) {
										// 	$wpdb->insert($table_name, $row);
										// }
										// Mail Trigger 
										$device = $res['device'];
										$device = (array) $device;
										$subject = 'Unusual activity detected on this account';
										$body = '<!doctype html>' .
										'<html>' .
											'<head>' .
												'<meta name="viewport" content="width=device-width">' .
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' .
												'<title>' . $subject . '</title>' .
											'</head>' .
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' .
												'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' .
												'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%; background-color: #f6f6f6;">' .
													'<tr>' .
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' .
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' .
															'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' .
																'<table style="border-collapse: separate; width: 100%; background: #ffffff; border-radius: 3px;">' .
																	'<tr>' .
																		'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' .
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%;">' .
																				'<tr>' .
																					'<td>' .
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $user->data->display_name . '</strong>,</p>' .
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">There is some unusual activity detected on this account. Did you recently use this device to perform some activity?</p>' .
																					'</td>' .
																				'</tr>' .
																				'<tr>' .
																					'<td>' .
																						'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' .
																							'<tr>' .
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' .
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' .
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' .
																							'</tr>' .
																							'<tr>' .
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' .
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' .
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' .
																							'</tr>' .
																						'</table>' .
																					'</td>' .
																				'</tr>' .
																				'<tr>' .
																					'<td>' .
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #0d6efd; border: 1px solid #0d6efd; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem; margin-right: 10px;">This was me</a><a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: #bb2d3b; border: 1px solid #b02a37; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem;">This was not me</a></p>' .
																					'</td>' .
																				'</tr>' .
																				'<tr>' .
																					'<td>' .
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' .
																					'</td>' .
																				'</tr>' .
																				'<tr>' .
																					'<td>' .
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' .
																					'</td>' .
																				'</tr>' .
																			'</table>' .
																		'</td>' .
																	'</tr>' .
																'</table>' .
																'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' .
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%;">' .
																		'<tr>' .
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' .
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' .
																			'</td>' .
																		'</tr>' .
																	'</table>' .
																'</div>' .
															'</div>' .
														'</td>' .
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' .
													'</tr>' .
												'</table>' .
											'</body>' .
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
									}
								}	
							} else {
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $user->data->user_email;
								$insert_data = "User ". $user_email_ato ." attempted a log in on " . $current_date_ato . ". Sensfrx encountered an error, so the log in was approved.";
								// $insert_data = $user_email_ato . " attempted log in on " . $current_date_ato . ". The log in is ". $res['status'] . " due to risk score level " . $res['severity'] .". Error";
								$data = array(
									'sensfrx_log_type' =>  'Account Security',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								}
							}
						} else {
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $user->data->user_email;
							$insert_data = "User " . $user_email_ato . " attempted to log in on " . $current_date_ato . ". Sensfrx flagged the log in with a " . $res['severity'] . " risk score. but since Shadow Mode was enabled, it was allowed.";
							// $insert_data = $user_email_ato . " attempted log in on " . $current_date_ato . ". But Shadow mode is on.";
							$data = array(
								'sensfrx_log_type' =>  'Account Security',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							}
						}
					} else {
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						$current_date_ato = date('Y-m-d H:i:s'); 
						$user_email_ato = $user->data->user_email;
						$insert_data = "User " . $user_email_ato . " attempted a log in on " . $current_date_ato . ". An error occurred while fetching Shadow Mode data.";
						// $insert_data = $user_email_ato . " attempted log in on " . $current_date_ato . ". Shodow Mode API response is unformatted. Type: " . gettype($res) . ".";
						$data = array(
							'sensfrx_log_type' =>  'Account Security',
							'sensfrx_log1' => $insert_data,
						);
						$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
						if ($results !== false) {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						} else {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						}
					} 
				} else {
					$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
					$current_date_ato = date('Y-m-d H:i:s'); 
					$user_email_ato = $user->data->user_email;
					$insert_data = "User " . $user_email_ato . " attempted a log in on " . $current_date_ato . ". However, Sensfrx returned an unformatted response, causing an issue.";
					// $insert_data = $user_email_ato . " attempted log in on " . $current_date_ato . ". API response is unformatted. Type: " . gettype($res) . ".";
					$data = array(
						'sensfrx_log_type' =>  'Account Security',
						'sensfrx_log1' => $insert_data,
					);
					$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
					if ($results !== false) {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					} else {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					}
				}
			}
		}
	}

	public function payment_attempt($order_id) {
		if ( is_user_logged_in() ) {
			global $wpdb;
			$sensfrx_custom_matched_fields = [];
			$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';			
			$sensfrx_fetch_results_custom = $wpdb->get_results("SELECT id, sensfrx_custom_field FROM $table_name_custom", ARRAY_A);
			if (!empty($sensfrx_fetch_results_custom)) {
				foreach ($sensfrx_fetch_results_custom as $row) {
					foreach ($_POST as $post_key => $post_value) {
						if ($post_key === $row['sensfrx_custom_field']) {
							if (!empty($post_value)) {
								array_push($sensfrx_custom_matched_fields, $post_key);
							}	
						} 
					}
				}
			}

			$device_id = null;
			$device_id_1 = null;
			$device_id_2 = null;
			$device_id_3 = null;
			$device_id_4 = null;

			if (isset($_COOKIE['device0'])) {   
				$device_id_1 = sanitize_text_field($_COOKIE['device0']);
				unset($_COOKIE['device0']);
				setcookie('device0', '', time() - 3600, '/');
				unset($_COOKIE['device0']);
			} 
			if (isset($_COOKIE['device1'])) {   
				$device_id_2 = sanitize_text_field($_COOKIE['device1']);
				unset($_COOKIE['device1']);
				setcookie('device1', '', time() - 3600, '/');
				unset($_COOKIE['device1']);
			}  
			if (isset($_COOKIE['device2'])) {   
				$device_id_3 = sanitize_text_field($_COOKIE['device2']);
				unset($_COOKIE['device2']);
				setcookie('device2', '', time() - 3600, '/');
				unset($_COOKIE['device2']);
			}  
			if (isset($_COOKIE['device3'])) {   
				$device_id_4 = sanitize_text_field($_COOKIE['device3']);
				unset($_COOKIE['device3']);
				setcookie('device3', '', time() - 3600, '/');
				unset($_COOKIE['device3']);
			}   
			$device_id = $device_id_1 . $device_id_2 . $device_id_3 . $device_id_4;
			if ( empty($device_id) ) {
				$device_id = '';
			}   
			$cart = WC()->cart;
			// Get the shipping total
			$shipping_cost = $cart->get_shipping_total();
			$options = get_option('sensfrx_options', SensFRX::default_policy_options());
			$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
			require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
			$obj = array();
			if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
				$obj = new SensFRX\SensFRX([
					'property_id' => $options['sensfrx_property_id'],
					'property_secret' => $options['sensfrx_property_secret']
				]);
			}
			$customer = WC()->customer;
			$username = $customer->get_username();
			$user_id = $customer->get_id(); 
			$first_name = $customer->get_first_name(); 
			$last_name = $customer->get_last_name(); 
			$email = $customer->get_email();
			$phone_number = WC()->customer->get_billing_phone(); 
			$formattedDate = gmdate('Y-F-d');
			$cart = WC()->cart;
			$product_total = $cart->get_total();
			// Remove the Indian Rupee symbol (₹) from the total value
			$total_value_without_rs = preg_replace('/[^\d.]/', '', $product_total);
			// Convert the modified total value to a numeric format (float)
			$total_float = (float) $total_value_without_rs;
			$total_array = str_split($total_float);
			// Remove the first,second,third and four value from the array
			$removedValue = array_shift($total_array);  
			$removedValue = array_shift($total_array);
			// Convert array into string 
			$total_price = implode('', $total_array);
			$current_user = wp_get_current_user();
			$user_login_id = $current_user->user_login;
			// -----------------------
			// Get current user IP and User Agent
			$customer_ip_address = WC_Geolocation::get_ip_address() ?: null;
			$customer_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		
			// Access the WooCommerce cart
			$cart = WC()->cart;
		
			// Get payment method details
			$payment_method = WC()->session->get('chosen_payment_method') ?: null;
			$payment_method_title = $payment_method ? WC()->payment_gateways->payment_gateways()[$payment_method]->get_title() : null;
		
			// Billing details
			$b_country = WC()->customer->get_billing_country() ?: null;
			$b_state = WC()->customer->get_billing_state() ?: null;
			$b_city = WC()->customer->get_billing_city() ?: null;
			$b_fname = WC()->customer->get_billing_first_name() ?: null;
			$b_lname = WC()->customer->get_billing_last_name() ?: null;
			$b_company = WC()->customer->get_billing_company() ?: null;
			$b_address1 = isset($_POST['billing_address_1']) ? sanitize_text_field($_POST['billing_address_1']) : null;
			$b_address2 = isset($_POST['billing_address_2']) ? sanitize_text_field($_POST['billing_address_2']) : null;
			$b_phone_number = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : null;
			$b_email = WC()->customer->get_billing_email() ?: null;
			$b_zipcode = WC()->customer->get_billing_postcode() ?: null;
		
			// Shipping details
			$sh_country = WC()->customer->get_shipping_country() ?: null;
			$sh_state = WC()->customer->get_shipping_state() ?: null;
			$sh_city = WC()->customer->get_shipping_city() ?: null;
			$sh_fname = WC()->customer->get_shipping_first_name() ?: null;
			$sh_lname = WC()->customer->get_shipping_last_name() ?: null;
			$sh_company = WC()->customer->get_shipping_company() ?: null;
			$sh_address1 = isset($_POST['shipping_address_1']) ? sanitize_text_field($_POST['shipping_address_1']) : null;
			$sh_address2 = isset($_POST['shipping_address_2']) ? sanitize_text_field($_POST['shipping_address_2']) : null;
			$sh_phone_number = isset($_POST['shipping_phone']) ? sanitize_text_field($_POST['shipping_phone']) : null; // If shipping phone number is used
			$sh_zipcode = WC()->customer->get_shipping_postcode() ?: null;
			
			// Get shipping method
			$shipping_methods = WC()->session->get('chosen_shipping_methods');
			$shipping_method = isset($shipping_methods[0]) ? $shipping_methods[0] : null;

			// Order details
			$currency = get_woocommerce_currency();
			$total_price = $cart->get_total('edit'); // Total amount
			$order_key = uniqid('order_'); // You can use this as a temporary key before the order is created
			$payment_date = current_time('mysql'); // Current time as payment date
			$date_paid = ''; // Not available until payment is confirmed

			$transaction_id = uniqid();

			// Get coupon codes
			$applied_coupons = $cart->get_applied_coupons();
			$coupon_code = !empty($applied_coupons) ? implode(', ', $applied_coupons) : null;

			// Get discount details
			$discount_amount = $cart->get_discount_total() ?: null;

			$currency = get_woocommerce_currency() ?: null;
			$transaction_currency = $currency; // Assuming it's the same as order currency

			// -----------------------
			$transactionId = uniqid();
			$item_p = array();
			$item_s = []; 
			function get_category_ancestors($term) {
				$ancestors = [];
				if (!is_wp_error($term) && !empty($term)) {
					$ancestors[] = $term->slug;
				}
				while (!is_wp_error($term) && $term->parent != 0) {
					$term = get_term($term->parent, 'product_cat');
					if (!is_wp_error($term) && !empty($term)) {
						$ancestors[] = $term->slug;
					} else {
						break; 
					}
				}
				return $ancestors;
			}
			foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
				$product_id = $cart_item['product_id'];
				$product = wc_get_product($product_id);
				$product_name = $product->get_name();
				$product_price = $product->get_price();
				$product_type = get_the_terms($product_id, 'product_tag');
				$tag_names = [];
				$tag_names_string = null;
				if ($product_type && !is_wp_error($product_type)) {
					foreach ($product_type as $tag) {
						$tag_names[] = $tag->name; 
					}
				}

				if (!empty($sensfrx_fetch_results_custom)) {
					foreach ($sensfrx_fetch_results_custom as $row) {
						foreach ($tag_names as $woo_tag) {
							if ($woo_tag === $row['sensfrx_custom_field']) {
								$tag_names_string .= ',' .$woo_tag;
							} 
						}
					}
				}
				
				$quantity = $cart_item['quantity'];
				$terms = wp_get_post_terms($product_id, 'product_cat');
				$category_slugs = [];
				foreach ($terms as $term) {
					$category_slugs = array_merge($category_slugs, get_category_ancestors($term));
				}
				$category_slugs = array_unique($category_slugs);
				$product_categories = implode(',', $category_slugs);
				$product_categories = $product_categories . $tag_names_string;
				$item_p = [
					'item_name' => $product_name,
					'item_price' => $product_price,
					'item_id' => $product_id,
					'item_quantity' => $quantity,
					'item_category' => $product_categories
				];
				array_push($item_s, $item_p);
			}
			$all_sensfrx_custom_matched_fields = array_unique($sensfrx_custom_matched_fields); 
			$sensfrx_custom_matched_fields_string = implode(',', $all_sensfrx_custom_matched_fields);

			if ($sensfrx_custom_matched_fields_string) {
				foreach ($item_s as &$item) {
					$item['item_category'] .= ',' . $sensfrx_custom_matched_fields_string; 
				}
			}

			$transactionExtras = array(
				'email' => $email,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'user_id' => $user_id,                    
				'username' => $username, 
				'transaction_type' => 'purchase',
				'transaction_amount' => $total_price,            
				'phone_no' => $phone_number,
				'transaction_id' => $transaction_id,
				'items' => wp_json_encode($item_s),
				'shipping_cost' => $shipping_cost,
				'OrderID' => '', // Not available at this stage
				'payment_status' => '',  // Not available at this stage 
				'currency' => $currency,
				'transaction_currency' => $transaction_currency, // This might be the same as $currency, depending on the gateway
				'payment_date' => $payment_date,
				'total_amount' => $total_price,
				'order_key' => $Order_Key,
				'payment_mode' => $payment_method,
				'payment_provider' => $payment_method_title,
				'customer_ip_address' => $customer_ip_address,
				'customer_user_agent' => $customer_user_agent,
				'date_paid' => $date_paid,
				'billing_country' => $b_country,
				'billing_state' => $b_state,
				'billing_city' => $b_city,
				'billing_fullname' => $b_fname.' '.$b_lname,
				'billing_company' => $b_company,
				'billing_address' => $b_address1.' '.$b_address2,
				'billing_email' => $b_email,
				'billing_zip' => $b_zipcode,
				'billing_phone' => $b_phone_number,
				'shipping_country' => $sh_country,
				'shipping_state' => $sh_state,
				'shipping_city' => $sh_city,
				'shipping_fullname' => $sh_fname.' '.$sh_lname,
				'shipping_company' => $sh_company,
				'shipping_address' => $sh_address1.' '.$sh_address2,
				'shipping_zip' => $sh_zipcode,
				'shipping_phone' => $sh_phone_number,
				'shipping_method' => $shipping_method,
				'discount_amount' => $discount_amount,
				'coupon_code' => $coupon_code
			);			
			global $wpdb;
			$sensfrx_approve = null;
			if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
				$obj = new SensFRX\SensFRX([
					'property_id' => $options['sensfrx_property_id'],
					'property_secret' => $options['sensfrx_property_secret']
				]);		
				$res = $obj->transactionAttempt('attempt_succeeded', $device_id, $transactionExtras);

				// Administrator Access Approved 
				$sensfrx_whitelisting_check = null;
				$sensfrx_whitelisting_check_email = $email;
				$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
				$sensfrx_white_list_admin_info_array = array();
				foreach ($sensfrx_white_list_admin_users as $user_data) {
					if ($user_data->user_email === $sensfrx_whitelisting_check_email) {
						$sensfrx_whitelisting_check = true;
						break;
					}
				}
				$sensfrx_whitelisting_check_checkbox_allow = $sensfrx_policy_options['sensfrx_whitle_list_email'];
				if ($sensfrx_whitelisting_check_checkbox_allow) {
					$sensfrx_whitelisting_check_checkbox_allow = true;
				} else {
					$sensfrx_whitelisting_check_checkbox_allow = false;
				}
				if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
					return;
				} else {
					if (isset($res) && gettype($res) !== 'string') {
						$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
						$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
						if (is_array($res_shadow)) {
							if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
								if (isset($res['status']) && null !== $res['status'] && isset($res['severity']) && null !== $res['severity']) {
									$status = $res['status'];
									$severity = $res['severity'];
									// $status = 'allow';
									// $severity = 'low';
									if($status == 'allow' || $status == 'challenge' || $status == 'low') {
										$sensfrx_approve = 'approved';
									} else {
										$sensfrx_approve = $status;
									}
									$site_title = get_bloginfo( 'name' ); 
									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
									$current_date_ato = date('Y-m-d H:i:s'); 
									$user_email_ato = $email;
									$insert_data = $user_email_ato . " attempted to transaction in on " . $current_date_ato . ". The transaction was ". $sensfrx_approve ." due to risk score level of ". $severity .".";
									$data = array(
										'sensfrx_log_type' =>  'Transaction (Before Payment)',
										'sensfrx_log1' => $insert_data,
									);
									$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
									if ($results !== false) {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									} else {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									}
									// $status = 'allow';
									// $severity = 'medium';
									/* The below code is checking the value of the variable . If the value is truthy
									(evaluates to true), it sets  to true. Otherwise, it sets  to
									false. */
									$checkbox_allow = $sensfrx_policy_options['sensfrx_allow_payment_email'];
									if ($checkbox_allow) {
										$checkbox_allow = true;
									} else {
										$checkbox_allow = false;
									}
									$checkbox_challenge = $sensfrx_policy_options['sensfrx_challenge_payment_email'];
									if ($checkbox_challenge) {
										$checkbox_challenge = true;
									} else {
										$checkbox_challenge = false;
									}
									$checkbox_deny = $sensfrx_policy_options['sensfrx_deny_payment_email'];
									if ($checkbox_deny) {
										$checkbox_deny = true;
									} else {
										$checkbox_deny = false;
									}
									if ('allow' == $status && 'medium' == $severity && true == $checkbox_allow) {			
										$device = $res['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Important: Recent Transaction and Security Notification';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<!-- START CENTERED WHITE CONTAINER -->' . 
																'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<!-- START MAIN CONTENT AREA -->' . 
																	'<tr>' . 
																		'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We wanted to bring to your attention a matter concerning a transaction on your account. </p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have identified a successful transaction from your account for <b>Amount</b> ' . $product_total . ' on <b>' . $formattedDate . '</b> that appeared to have some characteristics indicative of potential suspicious activity.</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">To ensure your account\'s security, we recommend taking prompt action. Consider changing your password and reviewing your account settings. </p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<!-- END MAIN CONTENT AREA -->' . 
																'</table>' . 
																'<!-- START FOOTER -->' . 
																'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
																'<!-- END FOOTER -->' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );		
									} 
									if ('challenge' == $status && 'high' == $severity && true == $checkbox_challenge) {
										$device = $res['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Important: Suspicious Transaction Detected on Your Account';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; font-size: 14px; line-height: 1.4; margin: 0; padding: 0;">' . 
												'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<table style="width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<tr>' . 
																		'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px 0;">We wanted to bring a matter to your immediate attention regarding a recent transaction activity from your account.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px 0;">We have identified a successful transaction from your account for <b>Amount</b> ' . $product_total . ' on <b>' . $formattedDate . '</b>.</p>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px 0;">Click below to let us know if this was you or not. If not, we advise changing your password immediately to protect your account.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
																							'<tr>' . 
																								'<th style="border: 1px solid #000; text-align: left;">Device</th>' . 
																								'<th style="border: 1px solid #000; text-align: left;">IP Address</th>' . 
																								'<th style="border: 1px solid #000; text-align: left;">Location</th>' . 
																							'</tr>' . 
																							'<tr>' . 
																								'<td style="border: 1px solid #000; text-align: center;">' . $device['name'] . '</td>' . 
																								'<td style="border: 1px solid #000; text-align: center;">' . $device['ip'] . '</td>' . 
																								'<td style="border: 1px solid #000; text-align: center;">' . $device['location'] . '</td>' . 
																							'</tr>' . 
																						'</table>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px 0;">' . 
																							'<a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; background-color: #0d6efd; border: 1px solid #0d6efd; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem; margin-right: 10px;">This was me</a>' . 
																							'<a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; background-color: #bb2d3b; border: 1px solid #b02a37; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem;">This was not me</a>' . 
																						'</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px 0;">Regards,<br>' . $site_title . ' Team</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
																'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">' . 
																		'<tr>' . 
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
										// throw new Exception('Sensfrx - We have detected unusual activity with your recent transaction, and as a precautionary measure, We cannot proceed with processing your order at this time due to detected unusual activity. Please try again later.');
									}
									if ('deny' == $status && 'critical' == $severity && true == $checkbox_deny) {		
										$device = $res['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Urgent: Highly Suspicious Transaction Detected on Your Account';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
												'<tr>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
														'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
															'<!-- START CENTERED WHITE CONTAINER -->' . 
															'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
															'<!-- START MAIN CONTENT AREA -->' . 
															'<tr>' . 
																'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have blocked a highly suspicious transaction from your account for <b>Amount</b> ' . $product_total . ' on ' . $formattedDate . '.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Within the next few minutes, you would receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'</table>' . 
																'</td>' . 
															'</tr>' . 
															'<!-- END MAIN CONTENT AREA -->' . 
															'</table>' . 
															'<!-- START FOOTER -->' . 
															'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																'<tr>' . 
																	'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																		'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																	'</td>' . 
																'</tr>' . 
																'</table>' . 
															'</div>' . 
															'<!-- END FOOTER -->' . 
														'<!-- END CENTERED WHITE CONTAINER -->' . 
														'</div>' . 
													'</td>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
										$current_user = wp_get_current_user();
										$results = retrieve_password($current_user->user_login);									
										throw new Exception('Sensfrx - We have detected suspicious activity with your recent transaction. As a precaution, we cannot proceed with processing your order at this time. Please try again later.');
									} 
								} else {
									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
									$current_date_ato = date('Y-m-d H:i:s'); 
									$user_email_ato = $email;
									$insert_data = "User ". $user_email_ato ." attempted a transaction on " . $current_date_ato . ". Sensfrx encountered an error, so the transaction was approved.";
									// $insert_data = $user_email_ato . " attempted transaction on " . $current_date_ato . ". The Transaction is ". $res['status'] . " due to risk score level " . $res['severity'] .". Error";
									$data = array(
										'sensfrx_log_type' =>  'Transaction (Before Payment)',
										'sensfrx_log1' => $insert_data,
									);
									$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
									if ($results !== false) {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									} else {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									}
								}	
							} else {
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $email;
								$insert_data = "User " . $user_email_ato . " attempted a transaction on " . $current_date_ato . ". Sensfrx flagged the transaction with a " . $res['severity'] . " risk score. but since Shadow Mode was enabled, it was allowed.";
								// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". But Shadow mode is on.";
								$data = array(
									'sensfrx_log_type' =>  'Transaction (Before Payment)',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								}
							}
						} else {
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $email;
							$insert_data = "User " . $user_email_ato . " attempted a transaction on " . $current_date_ato . ". An error occurred while fetching Shadow Mode data.";
							// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". Shodow Mode API response is unformatted. Type: " . gettype($res) . ".";
							$data = array(
								'sensfrx_log_type' =>  'Transaction (Before Payment)',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							}
						} 
					} else {
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						$current_date_ato = date('Y-m-d H:i:s'); 
						$user_email_ato = $email;
						$insert_data = "User " . $user_email_ato . " attempted a transaction on " . $current_date_ato . ". However, Sensfrx returned an unformatted response, causing an issue.";
						// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". API response is unformatted. Type: " . gettype($res) . ".";
						$data = array(
							'sensfrx_log_type' =>  'Transaction (Before Payment)',
							'sensfrx_log1' => $insert_data,
						);
						$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
						if ($results !== false) {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						} else {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						}
					}
				}
			}
		} 
	}

	public function payment_complete($order_id) {
		if ( is_user_logged_in() ) {
			global $wpdb;
			$order = wc_get_order($order_id);
			$sensfrx_entries = $order->get_meta_data();
			$sensfrx_custom_matched_fields = [];
			$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';			
			$sensfrx_fetch_results_custom = $wpdb->get_results("SELECT id, sensfrx_custom_field FROM $table_name_custom", ARRAY_A);
			if (!empty($sensfrx_fetch_results_custom)) {
				foreach ($sensfrx_fetch_results_custom as $row) {
					foreach ($sensfrx_entries as $sensfrx_mets_data) {
						$sensfrx_custom_value = $sensfrx_mets_data->get_data()['key'];
						$sensfrx_value = $sensfrx_mets_data->get_data()['value'];
						if (!empty($sensfrx_custom_value) && $sensfrx_custom_value == '_' . $row['sensfrx_custom_field']) {
							if(!empty($sensfrx_value)) {
								array_push($sensfrx_custom_matched_fields, $row['sensfrx_custom_field']);
								// wc_print_notice('Got', 'success');
							}	
						} 
					}
				}
			}

			$device_id = WC()->session->get('custom_field_value');
			// echo $device_id;
			// die();
			$options = get_option('sensfrx_options', SensFRX::default_policy_options());
			$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
			require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
			$obj = array();
			if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
				$obj = new SensFRX\SensFRX([
					'property_id' => $options['sensfrx_property_id'],
					'property_secret' => $options['sensfrx_property_secret']
				]);
			}
			$customer = WC()->customer;
			$user_id = $customer->get_id(); 
			$first_name = $customer->get_first_name(); 
			$last_name = $customer->get_last_name(); 
			$email = $customer->get_email(); 
			$order = wc_get_order($order_id);
			// echo '<pre>';
			// print_r($order);
			// $sh_state = $order->get_meta('billing_sensfrx_input');
			// echo $sh_state;
			// die();
			$userdata = get_userdata($user_id);
			$discount_total = $order->get_discount_total(); 
			$shipping_total = $order->get_shipping_total();
			$current_username = $userdata->user_login;
			$orderid = $order_id;
			$payment_status = $order->status;  // processing, 
			$currency = $order->currency;
			$payment_date = $order->date_created;
			$total_amount = $order->total;
			$Order_Key = $order->order_key;
			$payment_method = $order->payment_method;
			$payment_method_title = $order->payment_method_title;
			$transaction_id = $order->transaction_id;
			$customer_ip_address = $order->customer_ip_address;
			$customer_user_agent = $order->customer_user_agent;
			$date_paid = $order->date_paid;
			$b_fname = $order->data['billing']['first_name'];
			$b_lname = $order->data['billing']['last_name'];
			$b_company = $order->data['billing']['company'];
			$b_address1 = $order->data['billing']['address_1'];
			$b_address2 = $order->data['billing']['address_2'];
			$b_city = $order->data['billing']['city'];
			$b_state = $order->data['billing']['state'];
			$b_country = $order->data['billing']['country'];
			$b_email = $order->data['billing']['email'];
			$b_phone_number = $order->data['billing']['phone'];
			$sh_fname = $order->data['shipping']['first_name'];
			$sh_lname = $order->data['shipping']['last_name'];
			$sh_company = $order->data['shipping']['company'];
			$sh_address1 = $order->data['shipping']['address_1'];
			$sh_address2 = $order->data['shipping']['address_2'];
			$sh_city = $order->data['shipping']['city'];
			$sh_state = $order->data['shipping']['state'];
			$sh_country = $order->data['shipping']['country'];
			$sh_phone_number = $order->data['shipping']['phone'];
			$s_zipcode = $order->get_shipping_postcode();
			$b_zipcode = $order->get_billing_postcode();
			$shipping_methods = $order->get_shipping_methods();
			$shipping_method_names = [];
			foreach ($shipping_methods as $shipping_method) {
				$shipping_method_names[] = $shipping_method->get_name();
			}
			$first_shipping_method = !empty($shipping_method_names) ? $shipping_method_names[0] : null;
			$item_s = array();
			$item_p = array();
			$cart = WC()->cart;
			$product_total = $cart->get_total();
			$order = wc_get_order($order_id);
			$item_s = []; 
			$Total_Expense = 0; 
			function get_category_ancestors($term) {
				$ancestors = [];
				if (!is_wp_error($term) && !empty($term)) {
					$ancestors[] = $term->slug;
				}
				while (!is_wp_error($term) && $term->parent != 0) {
					$term = get_term($term->parent, 'product_cat');
					if (!is_wp_error($term) && !empty($term)) {
						$ancestors[] = $term->slug;
					} else {
						break; 
					}
				}
				return $ancestors;
			}
			$items = $order->get_items();
			foreach ($items as $item_id => $item) {
				$product_name = $item->get_name();
				$quantity = $item->get_quantity();
				$product_id = $item->get_product_id();

				$product_type = get_the_terms($product_id, 'product_tag');
				$tag_names = [];
				$tag_names_string = null;
				if ($product_type && !is_wp_error($product_type)) {
					foreach ($product_type as $tag) {
						$tag_names[] = $tag->name; 
					}
				}

				if (!empty($sensfrx_fetch_results_custom)) {
					foreach ($sensfrx_fetch_results_custom as $row) {
						foreach ($tag_names as $woo_tag) {
							if ($woo_tag === $row['sensfrx_custom_field']) {
								$tag_names_string .= ',' .$woo_tag;
							} 
						}
					}
				}

				$product = wc_get_product($product_id);
				$product_total = $product->get_price();
				$total = $item->get_total();
				$Total_Expense += $total;
				$terms = wp_get_post_terms($product_id, 'product_cat');
				$category_slugs = [];
				foreach ($terms as $term) {
					$category_slugs = array_merge($category_slugs, get_category_ancestors($term));
				}
				$category_slugs = array_unique($category_slugs);
				$product_categories = implode(',', $category_slugs);
				$product_categories = $product_categories . $tag_names_string;
				$item_p = [
					'item_id' => $product_id,
					'item_name' => $product_name,
					'item_price' => $product_total,
					'item_quantity' => $quantity,
					'item_category' => $product_categories
				];
				array_push($item_s, $item_p);
			}
			if ('cod' == $payment_method) {
				$transaction_id = uniqid();
			}
			$all_sensfrx_custom_matched_fields = array_unique($sensfrx_custom_matched_fields); 
			$sensfrx_custom_matched_fields_string = implode(',', $all_sensfrx_custom_matched_fields);

			if ($sensfrx_custom_matched_fields_string) {
				foreach ($item_s as &$item) {
					$item['item_category'] .= ',' . $sensfrx_custom_matched_fields_string; 
				}
			}
			$transactionFields = array(
				'user_id' => $user_id,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'username' => $current_username, 
				'transaction_type' => 'purchase',
				'order_id' => $orderid,
				'email' => $email,
				'payment_status' => $payment_status,  // ev
				'currency' => $currency,
				'transaction_currency' => $currency,
				'transaction_amount' => $total_amount,
				'payment_date' => $payment_date,
				'total_amount' => $total_amount,
				'order_key' => $Order_Key,
				'payment_mode' => $payment_method,
				'payment_provider' => $payment_method_title,
				'transaction_id' => $transaction_id,
				'customer_ip_address' => $customer_ip_address,
				'customer_user_agent' => $customer_user_agent,
				'date_paid' => $date_paid,
				'billing_country' => $b_country,
				'billing_state' => $b_state,
				'billing_city' => $b_city,
				'billing_fullname' => $b_fname.' '.$b_lname,
				'billing_company' => $b_company,
				'billing_address' => $b_address1.' '.$b_address2,
				'billing_email' => $b_email,
				'billing_zip' => $b_zipcode,
				'billing_phone' => $b_phone_number,
				'shipping_country' => $sh_country,
				'shipping_state' => $sh_state,
				'shipping_city' => $sh_city,
				'shipping_fullname' => $sh_fname.' '.$sh_lname,
				'shipping_company' => $sh_company,
				'shipping_address' => $sh_address1.' '.$sh_address2,
				'shipping_zip' => $s_zipcode,
				'shipping_phone' => $sh_phone_number,
				'shipping_method' => $first_shipping_method,
				'shipping_cost' => $shipping_total,
				'discount_amount' => $discount_total,
				'items' => wp_json_encode($item_s)
			);
		
			if ('pending' === $payment_status) {
				$payment_status = 'transaction_succeeded';
			} elseif ('failed' === $payment_status) {
				$payment_status = 'transaction_failed';
			} elseif ('processing' === $payment_status) {
				$payment_status = 'transaction_succeeded';
			} elseif ('completed' === $payment_status) {
				$payment_status = 'transaction_succeeded';
			} elseif ('on-hold' === $payment_status) {
				$payment_status = 'transaction_succeeded';
			} elseif ('cancelled' === $payment_status) {
				$payment_status = 'transaction_failed';
			} elseif ('refunded' === $payment_status) {
				$payment_status = 'transaction_failed';
			} else {
				$payment_status = 'transaction_succeeded';
			}
			if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
				$obj = new SensFRX\SensFRX([
					'property_id' => $options['sensfrx_property_id'],
					'property_secret' => $options['sensfrx_property_secret']
				]);
				global $wpdb;
				$sensfrx_approve = null;
				$res = $obj->transactionAttempt($payment_status, $device_id, $transactionFields);
				// echo '<pre>';
				// print_r($res);
				// die();
				// Administrator Access Approved 
				$sensfrx_whitelisting_check = null;
				$sensfrx_whitelisting_check_email = $email;
				$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
				$sensfrx_white_list_admin_info_array = array();
				foreach ($sensfrx_white_list_admin_users as $user_data) {
					if ($user_data->user_email === $sensfrx_whitelisting_check_email) {
						$sensfrx_whitelisting_check = true;
						break;
					}
				}
				$sensfrx_whitelisting_check_checkbox_allow = $sensfrx_policy_options['sensfrx_whitle_list_email'];
				if ($sensfrx_whitelisting_check_checkbox_allow) {
					$sensfrx_whitelisting_check_checkbox_allow = true;
				} else {
					$sensfrx_whitelisting_check_checkbox_allow = false;
				}
				if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
					return;
				} else {
					if (isset($res) && gettype($res) !== 'string') {	
						$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
						$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
						if (is_array($res_shadow)) {
							if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
								if (isset($res['status']) && null !== $res['status'] && isset($res['severity']) && null !== $res['severity']) {
									$status = $res['status'];
									$severity = $res['severity'];
									if($status == 'allow') {
										$sensfrx_approve = 'approved';
									} else if ( $status == 'challenge') {
										$sensfrx_approve = 'on hold';
									} else {
										$sensfrx_approve = $status;
									}
									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
									$current_date_ato = date('Y-m-d H:i:s'); 
									$user_email_ato = $b_email;
									$insert_data = $user_email_ato . " attempted to transaction in on " . $current_date_ato . ". The transaction was ". $sensfrx_approve ." due to risk score level of ". $severity .".";
									$data = array(
										'sensfrx_log_type' =>  'Transaction (After Payment)',
										'sensfrx_log1' => $insert_data,
									);
									$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
									if ($results !== false) {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									} else {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									}
									// $status = 'deny';
									// $severity = 'critical';
									$site_title = get_bloginfo( 'name' );
									/* The above code is checking the value of the variable . If the value is truthy
									(evaluates to true), it sets  to true. Otherwise, it sets  to false. */
									$checkbox_allow = $sensfrx_policy_options['sensfrx_allow_payment_email'];
									if ($checkbox_allow) {
										$checkbox_allow = true;
									} else {
										$checkbox_allow = false;
									}
									$checkbox_challenge = $sensfrx_policy_options['sensfrx_challenge_payment_email'];
									if ($checkbox_challenge) {
										$checkbox_challenge = true;
									} else { 
										$checkbox_challenge = false;
									}
									$checkbox_deny = $sensfrx_policy_options['sensfrx_deny_payment_email'];
									if ($checkbox_deny) {
										$checkbox_deny = true;
									} else { 
										$checkbox_deny = false;
									}
									$lost_pass_url = wp_lostpassword_url();
									$device = $res['device'];
									$device = (array) $device;
									if ( 'allow' == $status && 'medium' == $severity && true == $checkbox_allow ) {
										$lost_pass_url = wp_lostpassword_url();
										$device = $res['device'];
										$device = (array) $device;
										if ( empty($device) ) {
											$device['ip'] = 'NA';
										}
										$to = $email;
										$subject = 'Payment Successfully Done';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<tr>' . 
																		'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p>Thank you for your recent transaction. Your payment of ' . $Total_Expense . ' for ' . $site_title . ' has been successfully processed. If you have any questions or need assistance, please feel free to contact us.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Here is some detail about the last order:</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">1) Billing Email - ' . $b_email . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">2) IP - ' . $device['ip'] . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">3) Location - Country(' . $b_country . '), State(' . $b_state . '), City(' . $b_city . ')</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">4) Billing Phone Number - ' . $b_phone_number . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">5) Total Amount of Transaction - ' . $Total_Expense . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">6) Transaction ID - ' . $transaction_id . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">7) Order ID - ' . $order_id . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">8) Payment Status - ' . $payment_status . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
																							'<tr>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Status</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Severity</th>' . 
																							'</tr>' . 
																							'<tr>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $res['status'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $res['severity'] . '</td>' . 
																							'</tr>' . 
																						'</table>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Thank you for your order. We truly value our loyal customers. Thanks for making us who we are.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
																'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
									} 
									if ( 'challenge' == $status && 'high' == $severity && true == $checkbox_challenge ) {
										$lost_pass_url = wp_lostpassword_url();
										$device = $res['device'];
										$device = (array) $device;
										if ( empty($device) ) {
											$device['ip'] = 'NA';
										}
										$to = $email;
										$subject = 'Payment Successfully Done';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
													'<tr>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
															'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' . 
																'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																	'<tr>' . 
																		'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																			'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p>Thank you for your recent transaction. Your payment of ' . $Total_Expense . ' for ' . $site_title . ' has been successfully processed. If you have any questions or need assistance, please feel free to contact us.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Here is some detail about the last order:</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">1) Billing Email - ' . $b_email . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">2) IP - ' . $device['ip'] . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">3) Location - Country(' . $b_country . '), State(' . $b_state . '), City(' . $b_city . ')</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">4) Billing Phone Number - ' . $b_phone_number . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">5) Total Amount of Transaction - ' . $Total_Expense . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">6) Transaction ID - ' . $transaction_id . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">7) Order ID - ' . $order_id . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">8) Payment Status - ' . $payment_status . '</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
																							'<tr>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Status</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
																								'<th style="border: 1px solid rgba(0, 0, 0, 1);">Severity</th>' . 
																							'</tr>' . 
																							'<tr>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $res['status'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
																								'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $res['severity'] . '</td>' . 
																							'</tr>' . 
																						'</table>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Thank you for your order. We truly value our loyal customers. Thanks for making us who we are.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																				'<tr>' . 
																					'<td>' . 
																						'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																					'</td>' . 
																				'</tr>' . 
																			'</table>' . 
																		'</td>' . 
																	'</tr>' . 
																'</table>' . 
																'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</div>' . 
														'</td>' . 
														'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );

										if (!$order_id) {
											return;
										}

										$order = wc_get_order($order_id);
										$order->update_status('on-hold', 'Sensfrx - Order placed on hold due to detected issues.');
										wc_print_notice('Sensfrx - We have detected unusual activity with your recent transaction, and as a precautionary measure, your order status has been temporarily placed On Hold.', 'error');
									
									} 
									if ('deny' == $status && 'critical' == $severity && true == $checkbox_deny) {		
										$device = $res['device'];
										$device = (array) $device;
										$to = $email;
										$subject = 'Urgent: Highly Suspicious Transaction Detected on Your Account';
										$body = '<!doctype html>' . 
										'<html>' . 
											'<head>' . 
												'<meta name="viewport" content="width=device-width">' . 
												'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
												'<title>' . $subject . '</title>' . 
											'</head>' . 
											'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
												'<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
												'<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
												'<tr>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
														'<div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
															'<!-- START CENTERED WHITE CONTAINER -->' . 
															'<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
															'<!-- START MAIN CONTENT AREA -->' . 
															'<tr>' . 
																'<td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . ' ' . $last_name . '</strong>,</p>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">We have blocked a highly suspicious transaction from your account for <b>Amount</b> ' . $product_total . ' on ' . $formattedDate . '.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Your order is currently on hold, and we will get back to you shortly.</p>' . 
																		'</td>' . 
																	'</tr>';
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Within the next few minutes, you would receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'<tr>' . 
																		'<td>' . 
																			'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; Margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																		'</td>' . 
																	'</tr>' . 
																	'</table>' . 
																'</td>' . 
															'</tr>' . 
															'<!-- END MAIN CONTENT AREA -->' . 
															'</table>' . 
															'<!-- START FOOTER -->' . 
															'<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">' . 
																'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																'<tr>' . 
																	'<td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																		'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																	'</td>' . 
																'</tr>' . 
																'</table>' . 
															'</div>' . 
															'<!-- END FOOTER -->' . 
														'<!-- END CENTERED WHITE CONTAINER -->' . 
														'</div>' . 
													'</td>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'</tr>' . 
												'</table>' . 
											'</body>' . 
										'</html>';
										$headers = array('Content-Type: text/html; charset=UTF-8');
										$mailResult = wp_mail( $to, $subject, $body, $headers );
										$current_user = wp_get_current_user();
										$payment_method = $order->payment_method;
										
										$order->update_status('cancelled', 'Sensfrx - Order marked as cancelled due to fraudulent activity.');
										$refund_args = array(
											'amount'         => $order->get_total(),
											'order_id'       => $order_id,
											'reason'         => 'Refund due to detected fraudulent activity.',
											'refund_payment' => true, 
										);
										try {
											$refund = wc_create_refund($refund_args);
											if (is_wp_error($refund)) {
												$order->add_order_note('Sensfrx - Refund failed: ' . $refund->get_error_message());
											} else {
												$order->add_order_note('Sensfrx - Refund processed successfully for the cancelled order.');
											}
										} catch (Exception $e) {
											$order->add_order_note('Sensfrx - Error during refund: ' . $e->getMessage());
										}
										wc_print_notice('Sensfrx - We have detected suspicious activity with your recent transaction. As a precaution, we cannot proceed with your order and your order is cancelled. Please try again later.', 'error');
										sleep(3);
										$results = retrieve_password( $current_user->data->user_login );

										
										// $message = 'Your account is blocked due to suspicious transaction activities.';
										// $login_url = site_url( 'wp-login.php?action=as_ch_dn&as_msg=' . urlencode($message), 'login' );
										// wp_logout();
										// wp_safe_redirect( $login_url );
									}
								} else {
									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
									$current_date_ato = date('Y-m-d H:i:s'); 
									$user_email_ato = $b_email;
									$insert_data = "User ". $user_email_ato ." succeeded a transaction on " . $current_date_ato . ". Sensfrx encountered an error, so the transaction was approved.";
									// $insert_data = $user_email_ato . " attempted transaction on " . $current_date_ato . ". The Transaction is ". $res['status'] . " due to risk score level " . $res['severity'] .". Error";
									$data = array(
										'sensfrx_log_type' =>  'Transaction (After Payment)',
										'sensfrx_log1' => $insert_data,
									);
									$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
									if ($results !== false) {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									} else {
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
										wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
									}
								}	
							} else {
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $b_email;
								$insert_data = "User " . $user_email_ato . " succeeded in a transaction on " . $current_date_ato . ". Sensfrx flagged the transaction with a " . $res['severity'] . " risk score. but since Shadow Mode was enabled, it was allowed.";
								// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". But Shadow mode is on.";
								$data = array(
									'sensfrx_log_type' =>  'Transaction (After Payment)',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								}
							}
						} else {
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $b_email;
							$insert_data = "User " . $user_email_ato . " succeeded a transaction on " . $current_date_ato . ". An error occurred while fetching Shadow Mode data.";
							// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". Shodow Mode API response is unformatted. Type: " . gettype($res) . ".";
							$data = array(
								'sensfrx_log_type' =>  'Transaction (After Payment)',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							}
						} 
					} else {
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						$current_date_ato = date('Y-m-d H:i:s'); 
						$user_email_ato = $b_email;
						$insert_data = "User " . $user_email_ato . " succeeded a transaction on " . $current_date_ato . ". However, Sensfrx returned an unformatted response, causing an issue.";
						// $insert_data = $user_email_ato . " attempted transaction in on " . $current_date_ato . ". API response is unformatted. Type: " . gettype($res) . ".";
						$data = array(
							'sensfrx_log_type' =>  'Transaction (After Payment)',
							'sensfrx_log1' => $insert_data,
						);
						$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
						if ($results !== false) {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						} else {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						}
					}
				}
			}
		} 
	}	

	public function user_profile_updates($user) {
		// Old Data
		$user_id = get_current_user_id();
		$user_data = get_userdata($user_id);
		// Access user information
		$username = $user_data->user_login;
		$email = $user_data->user_email;
		$display_name = $user_data->display_name;
		$first_name = $user_data->first_name;
		$last_name = $user_data->last_name;
		// Get the new information from the form data.
		$new_first_name = ' ';
		$new_last_name = ' ';
		$new_display_name = ' ';
		$new_password = ' ';
		$device_id = ' ';
		$new_email = ' ';
		if ( isset($_POST['sensfrx_profile_up_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_profile_up_nonce']), 'sensfrx_profile_up_action') ) {
			$new_first_name = isset($_POST['account_first_name']) ? sanitize_text_field($_POST['account_first_name']) : sanitize_text_field('');
			$new_last_name = isset($_POST['account_last_name']) ? sanitize_text_field($_POST['account_last_name']) : sanitize_text_field('');
			$new_display_name = isset($_POST['account_display_name']) ? sanitize_text_field($_POST['account_display_name']) : sanitize_text_field('');
			$new_password = '';
			if ( isset($_POST['password_1']) && strlen( sanitize_text_field($_POST['password_1']) ) > 0 ) {
				$new_password = sanitize_text_field($_POST['password_1']);
			}
			if ($new_password) {
				$new_password = '1';
			} else {
				$new_password = '0';
			}
			// if (isset($_POST['profile_update'])) { 
			// 	$device_id = sanitize_text_field($_POST['profile_update']); 
			// } else { 
			// 	$device_id = ''; // Handle the case where 'profile_update' is not set 
			// }
			if (isset($_POST['account_email'])) { 
				$new_email = sanitize_email($_POST['account_email']); 
			} else { 
				$new_email = null; 
			}
			
			$device_id = null;
			$device_id_1 = null;
			$device_id_2 = null;
			$device_id_3 = null;
			$device_id_4 = null;
			$res_bot = null;
			if (isset($_COOKIE['device0'])) {   
				$device_id_1 = sanitize_text_field($_COOKIE['device0']);
				unset($_COOKIE['device0']);
				// setcookie("device0", "", time() - 3600, "/");
				// unset($_COOKIE["device0"]);
			} 
			if (isset($_COOKIE['device1'])) {   
				$device_id_2 = sanitize_text_field($_COOKIE['device1']);
				unset($_COOKIE['device1']);
				// setcookie("device1", "", time() - 3600, "/");
				// unset($_COOKIE["device1"]);
			}  
			if (isset($_COOKIE['device2'])) {   
				$device_id_3 = sanitize_text_field($_COOKIE['device2']);
				unset($_COOKIE['device2']);
				// setcookie("device2", "", time() - 3600, "/");
				// unset($_COOKIE["device2"]);
			}  
			if (isset($_COOKIE['device3'])) {   
				$device_id_4 = sanitize_text_field($_COOKIE['device3']);
				unset($_COOKIE['device3']);
				// setcookie("device3", "", time() - 3600, "/");
				// unset($_COOKIE["device3"]);
			}   
			$device_id = $device_id_1 . $device_id_2 . $device_id_3 . $device_id_4;
			if ( empty($device_id) ) {
				$device_id = ' ';
			} 
		}
		$options = get_option('sensfrx_options', SensFRX::default_policy_options());	
		$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = array();
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
		}
		$json_data = array(
			'name' => array(
				'from' => $first_name . ' ' . $last_name,
				'to' => $new_first_name . ' ' . $new_last_name
			),
			'username' => array(
				'from' => null,
				'to' => null
			),
			'email' => array(
				'from' => $email,
				'to' => $new_email
			),
			'phone' => array(
				'from' => null,
				'to' => null
			),
			'password' => array(
				'changed' => $new_password
			)
		);
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			global $wpdb;
			$sensfrx_approve = null;
			$update_profile = $obj->updateAttempt('profile_update_succeeded', $user_id, $device_id, $json_data);
			
			// Administrator Access Approved 
			$sensfrx_whitelisting_check = null;
			$sensfrx_whitelisting_check_email = $email;
			$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));
			$sensfrx_white_list_admin_info_array = array();
			foreach ($sensfrx_white_list_admin_users as $user_data) {
				if ($user_data->user_email === $sensfrx_whitelisting_check_email) {
					$sensfrx_whitelisting_check = true;
					break;
				}
			}
			$sensfrx_whitelisting_check_checkbox_allow = $sensfrx_policy_options['sensfrx_whitle_list_email'];
			if ($sensfrx_whitelisting_check_checkbox_allow) {
				$sensfrx_whitelisting_check_checkbox_allow = true;
			} else {
				$sensfrx_whitelisting_check_checkbox_allow = false;
			}
			if(true === $sensfrx_whitelisting_check && true === $sensfrx_whitelisting_check_checkbox_allow) {
				return;
			} else {
				if (isset($update_profile) && gettype($update_profile) !== 'string' ) {
					$table_name_shadow_table = $wpdb->prefix . 'sensfrx_shadow_activity';
					$res_shadow = $wpdb->get_results("SELECT sensfrx_shadow_status FROM $table_name_shadow_table", ARRAY_A); 
					if (is_array($res_shadow)) {
						if ( '0' == $res_shadow[0]['sensfrx_shadow_status'] ) {
							if (isset($update_profile['status']) && null !== $update_profile['status'] && isset($update_profile['severity']) && null !== $update_profile['severity']) {
								$status = $update_profile['status'];
								$severity = $update_profile['severity'];
								if($status == 'allow' || $status == 'challenge' || $status == 'low') {
									$sensfrx_approve = 'approved';
								} else {
									$sensfrx_approve = $status;
								}
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $email;
								$insert_data = $user_email_ato . " attempted to profile update in on " . $current_date_ato . ". The profile update was ". $sensfrx_approve ." due to risk score level of ". $severity .".";
								$data = array(
									'sensfrx_log_type' =>  'Profile Update',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								}
								// $status = 'deny';
								// $severity = 'critical';
								$site_title = get_bloginfo( 'name' );
								if ('allow' == $status && 'medium' == $severity) {
									$device = $update_profile['device'];
									$device = (array) $device;
									$to = $email;
									$subject = 'Unusual Profile Update Activity Detected on this Account';
									$body = '<!doctype html>' . 
									'<html>' . 
										'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
										'</head>' . 
										'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
										'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
										'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' . 
										'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $first_name . '</strong>,</p>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">There is some unusual profile updation activity detected on this account. Did you recently use this device to perform some activity?</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
										'<tr>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">Device</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">IP Address</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1);">Location</th>' . 
										'</tr>' . 
										'<tr>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' . 
										'</tr>' . 
										'</table>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">' . 
										'<a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=1" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; background-color: #0d6efd; border: 1px solid #0d6efd; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem; margin-right: 10px;">This was me</a>' . 
										'<a href="' . site_url() . '?as_did_1337575=' . $device['device_id'] . '&asda_ac=0" style="display: inline-block; font-weight: 400; line-height: 1.5; color: #fff; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; background-color: #bb2d3b; border: 1px solid #b02a37; padding: 4px 12px; font-size: 14px; border-radius: 0.25rem;">This was not me</a>' . 
										'</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
										'</td>' . 
										'</tr>' . 
										'</table>' . 
										'</td>' . 
										'</tr>' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'</tr>' . 
										'</table>' . 
										'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
										'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
										'</td>' . 
										'</tr>' . 
										'</table>' . 
										'</div>' . 
										'</body>' . 
									'</html>';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									$mailResult = wp_mail( $to, $subject, $body, $headers );
								}
								if ('challenge' == $status && 'high' == $severity) {
									$lost_pass_url = wp_lostpassword_url();
									$device = $update_profile['device'];
									$device = (array) $device;
									$to = $email;
									$subject = 'Suspicious Profile Update activity detected on this account';
									$body = '<!doctype html>' . 
									'<html>' . 
										'<head>' . 
										'<meta name="viewport" content="width=device-width">' . 
										'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
										'<title>' . $subject . '</title>' . 
										'</head>' . 
										'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 

										'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 

										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 

										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
										'<div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">' . 

										'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 

										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Hi <strong>' . $first_name . '</strong>,</p>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Suspicious Profile Updation detected on the below device:</p>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">' . 
										'<tr>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1); text-align: center; font-size: 16px;">Device</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1); text-align: center; font-size: 16px;">IP Address</th>' . 
										'<th style="border: 1px solid rgba(0, 0, 0, 1); text-align: center; font-size: 16px;">Location</th>' . 
										'</tr>' . 
										'<tr>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['name'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['ip'] . '</td>' . 
										'<td style="border: 1px solid rgba(0, 0, 0, 1); text-align: center;">' . $device['location'] . '</td>' . 
										'</tr>' . 
										'</table>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We highly recommend to reset your password on this device if it was you. Click below to reset your password:</p>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><a href="' . $lost_pass_url . '" style="color: #1a73e8; text-decoration: underline;">' . $lost_pass_url . '</a></p>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td>' . 
										'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is shot from sensfrx.ai.</p>' . 
										'</td>' . 
										'</tr>' . 

										'</table>' . 
										'</td>' . 
										'</tr>' . 

										'<tr>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'</tr>' . 
										'</table>' . 

										'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
										'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
										'<tr>' . 
										'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
										'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
										'</td>' . 
										'</tr>' . 
										'</table>' . 
										'</div>' . 

										'</div>' . 
										'</td>' . 
										'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
										'</tr>' . 
										'</table>' . 
										'</body>' . 
									'</html>';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									$mailResult = wp_mail( $to, $subject, $body, $headers );
								}
								if ('deny' == $status && 'critical' == $severity) {
									$device = $update_profile['device'];
									$device = (array) $device;
									$to = $email;
									$subject = 'High Suspicious Profile Update Prevent';
									$body = '<!doctype html>' . 
									'<html>' . 
										'<head>' . 
											'<meta name="viewport" content="width=device-width">' . 
											'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . 
											'<title>' . $subject . '</title>' . 
										'</head>' . 
										'<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">' . 
											'<span style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>' . 
											'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">' . 
												'<tr>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">' . 
														'<div style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">' . 
															'<table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">' . 
																'<tr>' . 
																	'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">' . 
																		'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 20px 0;">Dear <strong>' . $first_name . '</strong>,</p>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We have blocked a highly suspicious Profile Update Activity.</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Within the next few minutes, you would receive a follow-up email containing a secure link to initiate the password reset process. Please keep an eye on your inbox to set a new password.</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Regards,<br>' . $site_title . ' Team</p>' . 
																				'</td>' . 
																			'</tr>' . 
																			'<tr>' . 
																				'<td>' . 
																					'<p style="font-family: sans-serif; font-size: 10px; font-weight: normal; margin: 0; margin-bottom: 0px; color: #999999;">This email is sent from sensfrx.ai.</p>' . 
																				'</td>' . 
																			'</tr>' . 
																		'</table>' . 
																	'</td>' . 
																'</tr>' . 
																'<div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">' . 
																	'<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">' . 
																		'<tr>' . 
																			'<td style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">' . 
																				'Powered by <a href="http://sensfrx.ai" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">sensfrx.ai</a>.' . 
																			'</td>' . 
																		'</tr>' . 
																	'</table>' . 
																'</div>' . 
															'</table>' . 
														'</div>' . 
													'</td>' . 
													'<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>' . 
												'</tr>' . 
											'</table>' . 
										'</body>' . 
									'</html>';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									$mailResult = wp_mail( $to, $subject, $body, $headers );
									sleep(3);
									$results = retrieve_password( $user_data->user_login );
									if ( true === $results ) {
										$message = 'Your account is blocked due to suspicious activities. We have sent a password reset email, please reset your password to activate your account again.';
									} else {
										$message = $results->get_error_message();
									}
									$login_url = site_url( 'wp-login.php?action=as_ch_dn&as_msg=' . urlencode($message), 'login' );
									wp_logout();
									wp_safe_redirect( $login_url );
									exit;				
								}
							} else {
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
								$current_date_ato = date('Y-m-d H:i:s'); 
								$user_email_ato = $email;
								$insert_data = "User ". $user_email_ato ." attempted a profile update on " . $current_date_ato . ". Sensfrx encountered an error, so the profile update was approved.";
								// $insert_data = $user_email_ato . " attempted profile update on " . $current_date_ato . ". The Profile Update is ". $update_profile['status'] . " due to risk score level " . $update_profile['severity'] .". Error";
								$data = array(
									'sensfrx_log_type' =>  'Profile Update',
									'sensfrx_log1' => $insert_data,
								);
								$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
								if ($results !== false) {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								} else {
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
									wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
								}
							}	
						} else {
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
							$current_date_ato = date('Y-m-d H:i:s'); 
							$user_email_ato = $email;
							$insert_data = "User " . $user_email_ato . " attempted a profile update on " . $current_date_ato . ". Sensfrx flagged the profile update with a " . $update_profile['severity'] . " risk score. but since Shadow Mode was enabled, it was allowed.";
							// $insert_data = $user_email_ato . " attempted profile update in on " . $current_date_ato . ". But Shadow mode is on.";
							$data = array(
								'sensfrx_log_type' =>  'Profile Update',
								'sensfrx_log1' => $insert_data,
							);
							$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
							if ($results !== false) {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							} else {
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
								wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
							}
						}
					} else {
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						$current_date_ato = date('Y-m-d H:i:s'); 
						$user_email_ato = $email;
						$insert_data = "User " . $user_email_ato . " attempted a profile update on " . $current_date_ato . ". An error occurred while fetching Shadow Mode data.";
						// $insert_data = $user_email_ato . " attempted profile update in on " . $current_date_ato . ". Shodow Mode API response is unformatted. Type: " . gettype($update_profile) . ".";
						$data = array(
							'sensfrx_log_type' =>  'Profile Update',
							'sensfrx_log1' => $insert_data,
						);
						$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
						if ($results !== false) {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						} else {
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
							wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
						}
					} 
				} else {
					$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
					$current_date_ato = date('Y-m-d H:i:s'); 
					$user_email_ato = $email;
					$insert_data = "User " . $user_email_ato . " attempted a profile update on " . $current_date_ato . ". However, Sensfrx returned an unformatted response, causing an issue.";
					// $insert_data = $user_email_ato . " attempted profile update in on " . $current_date_ato . ". API response is unformatted. Type: " . gettype($update_profile) . ".";
					$data = array(
						'sensfrx_log_type' =>  'Profile Update',
						'sensfrx_log1' => $insert_data,
					);
					$results = wp_cache_get('sensfrx_admin_2444_insert_data', 'sensfrx_group');
					if ($results !== false) {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_replace('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					} else {
						$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						wp_cache_set('sensfrx_admin_2444_insert_data', $results, 'sensfrx_group', 86400);
					}
				}
			}
		}
	}

	public function sensfrx_settings_options() {
		$options = get_option('sensfrx_options', SensFRX::default_options());
		$property_id = ( isset($options['sensfrx_property_id']) && !empty($options['sensfrx_property_id']) ) ? $options['sensfrx_property_id'] : '';
		$property_secret = ( isset($options['sensfrx_property_secret']) && !empty($options['sensfrx_property_secret']) ) ? $options['sensfrx_property_secret'] : '';
		$options_array = array(
			'options'         => $options,
			'sensfrx_property_id' => $property_id,
			'sensfrx_property_secret' => $property_secret,
			// 'pixel_track' 	  => $pixel_track,
			// 'login_track'     => $login_track,
			// 'reset_track'     => $reset_track
		);
		return apply_filters('sensfrx_settings_options_array', $options_array);
	}

	public function as_login_message( $message ) {
		return $message;
	}

	public function wp_login_failed_track($username, WP_Error $error ) {
		$device_id = '';
		if ( isset($_POST['device_id']) && isset($_POST['woocommerce-login-nonce']) && wp_verify_nonce(sanitize_text_field($_POST['woocommerce-login-nonce']), 'woocommerce-login') ) {
			$device_id = sanitize_text_field($_POST['device_id']);
		}
		$options = get_option('sensfrx_options', SensFRX::default_options());
		if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$user = get_user_by('email', $username);
		} else {
			$user = get_user_by('login', $username);
		}
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = array();
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
		}
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			if ($user) {
				$res = $obj->loginAttempt('login_failed', $device_id, array('email'=>$user->data->user_email,'username'=>$user->data->user_login));
			} else {
				$res = $obj->loginAttempt('login_failed', '', $device_id);
			}
		}
	}

	// public function wp_logout_track($user_id)
	// {

	// 	$device_id = '';
	// 	if (isset($_REQUEST['did'])) {
	// 		$device_id = sanitize_text_field($_REQUEST['did']);
	// 	}

	// 	$user = get_userdata($user_id);
	// 	$options = get_option('sensfrx_options', SensFRX::default_options());
	// 	require_once(SENSFRX_DIR."sensfrx-php-sdk/SensFRX/autoload.php");
	// 	$obj = new SensFRX\SensFRX([
	// 	  'property_id' => $options['sensfrx_property_id'],
	// 	  'property_secret' => $options['sensfrx_property_secret']
	// 	]);

	// 	$res = $obj->loginAttempt('logout',$user->data->ID,$device_id,array('email'=>$user->data->user_email,'username'=>$user->data->user_login));
	// 	// echo '<pre';
	// 	// print_r($res);
	// 	// die();	

	// }

	public function password_reset_track(WP_User $user, $new_pass) {
		$device_id = '';
		if (isset($_POST['device_id'])) {
			if (!isset($_POST['sensfrx_password_profile_update_nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['sensfrx_password_profile_update_nonce']), 'sensfrx_password_profile_update_action')) {
				$device_id = sanitize_text_field($_POST['device_id']);
			} elseif (!isset($_POST['woocommerce-reset-password-nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['woocommerce-reset-password-nonce']), 'woocommerce-reset-password-action')) {
				$device_id = sanitize_text_field($_POST['device_id']);
			} else {
				$device_id = '';
			}
		}
		$options = get_option('sensfrx_options', SensFRX::default_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = new SensFRX\SensFRX([
		'property_id' => $options['sensfrx_property_id'],
		'property_secret' => $options['sensfrx_property_secret']
		]);
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			$res = $obj->passwordResetAttempt('reset_password_succeeded', $user->data->ID, $device_id, array('email'=>$user->data->user_email,'username'=>$user->data->user_login));
		}
	}

	public function password_reset_failed_track($errors, $user_data) {
		$device_id = '';
		if (isset($_POST['device_id'])) {
			if (!isset($_POST['woocommerce-lost-password-nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['woocommerce-lost-password-nonce']), 'woocommerce-lost-password-action')) {
				$device_id = sanitize_text_field($_POST['device_id']);
			} else {
				$device_id = '';
			}
		}
		$options = get_option('sensfrx_options', SensFRX::default_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = new SensFRX\SensFRX([
		'property_id' => $options['sensfrx_property_id'],
		'property_secret' => $options['sensfrx_property_secret']
		]);
		if (isset($options['sensfrx_property_id']) && isset($options['sensfrx_property_secret'])) {
			$obj = new SensFRX\SensFRX([
				'property_id' => $options['sensfrx_property_id'],
				'property_secret' => $options['sensfrx_property_secret']
			]);
			if (!$user_data) {
				$res = $obj->passwordResetAttempt('reset_password_failed', '', $device_id);
			}
		}
	}
}
